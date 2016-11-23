<?php
/**
 * @package     DOCman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComDocman_importControllerImport extends ComDefaultControllerResource
{
    protected static $_type_map = array(
        'documents'  => '#__docman',
        'categories' => '#__docman_categories'
    );

    protected static $_table_map = array(
        'docman_export_documents'  => 'docman',
        'docman_export_categories' => 'docman_categories'
    );

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'model' => 'empty'
        ));

        parent::_initialize($config);
    }

    /**
     * Uploads and extracts the migration file
     *
     * @param KCommandContext $context
     */
    protected function _actionUpload(KCommandContext $context)
    {
        $result = array(
            'status' => true
        );

        if (KRequest::has('files.file.tmp_name')) {
            $context->data->file = KRequest::get('files.file.tmp_name', 'raw');
        }

        if (!is_writable(JPATH_ROOT.'/tmp')) {
            throw new RuntimeException('Please make sure tmp directory in your site root is writable');
        }

        if (empty($context->data->file)) {
            throw new RuntimeException('Cannot find uploaded file');
        }

        $zip  = new ZipArchive();

        if ($zip->open($context->data->file) !== true) {
            throw new RuntimeException("Cannot open uploaded file");
        }

        if (!$zip->extractTo(JPATH_ROOT.'/tmp/docman_export/')) {
            throw new RuntimeException('Unable to extract uploaded file');
        }

        $zip->close();

        if (!is_file($this->_getPath('categories')) || !is_file($this->_getPath('documents'))) {
            throw new RuntimeException('Data files are missing. Please run the export process again.');
        }

        $this->getView()->output = $result;
    }

    /**
     * Create temporary tables to import CSV files in an intermediate step
     *
     * @param KCommandContext $context
     */
    protected function _actionPrepare(KCommandContext $context)
    {
        $path = JPATH_ROOT.'/administrator/components/com_docman_import/install/migrator.sql';
        $db   = $this->getService('koowa:database.adapter.mysqli');

        $query = file_get_contents($path);
        $query = $db->replaceTableNeedle($query);

        $result = array(
            'status' => $db->getConnection()->multi_query($query)
        );

        $this->getView()->output = $result;
    }

    protected function _actionCleanup(KCommandContext $context)
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        $result = true;
        $path   = JPATH_ROOT.'/tmp/docman_export';

        if (JFolder::exists($path)) {
            $result = JFolder::delete($path);
        }

        $queries = array(
            'DROP TABLE IF EXISTS `#__docman_tmp`',
            'DROP TABLE IF EXISTS `#__docman_categories_tmp`'
        );

        foreach ($queries as $query) {
            $result = JFactory::getDbo()->setQuery($query)->query() && $result;
        }

        $this->getView()->output = array(
            'status' => $result
        );
    }

    /**
     * Imports CSV files in little chunks
     *
     * @param KCommandContext $context
     */
    protected function _actionInsert(KCommandContext $context)
    {
        $limit  = 100;
        $offset = $this->getRequest()->offset;
        $type   = $this->getRequest()->type;

        $file = new SplFileObject($this->_getPath($type));

        $imported = $this->_importCSV($file, $offset, $limit);

        // Rewind to count the total number of lines
        $file->rewind();

        $total     = iterator_count($file)-1;
        $last_line = $offset+$imported;
        $remaining = $total-$last_line;

        $output = array(
            'completed' => $imported,
            'type'      => $type,
            'total'     => $total,
            'remaining' => $remaining,
            'offset'    => $last_line
        );

        if ($remaining > 0)
        {
            $url = sprintf('index.php?option=com_docman_import&view=import&format=json&type=%s&offset=%d', $type, $last_line);
            $output['next'] = $this->_createRoute($url);
        }

        $this->getView()->output = $output;
    }

    /**
     * Returns a fully qualified URL after running it through JRoute
     *
     * @param $url
     * @return string
     */
    protected function _createRoute($url)
    {
        $prefix = JURI::getInstance()->toString(array('scheme', 'user', 'pass', 'host', 'port'));

        return $prefix.JRoute::_($url, false);
    }

    /**
     * Creates the recursive structure of the category tree for easy migration
     *
     * @param KCommandContext $context
     */
    protected function _actionCache_tree(KCommandContext $context)
    {
        $dir = JPATH_ROOT.'/tmp/docman_export';

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $file       = new SplFileObject($dir.'/tree_cache', 'w');
        $connection = JFactory::getDbo()->getConnection();
        $table      = $this->_getTableName('categories', $context->data->from_backup ? 'bkp' : 'tmp');

        $this->_insertChildren(0, $file, $connection, $table);

        $result = array(
            'status' => true
        );

        $this->getView()->output = $result;
    }

    protected function _insertChildren($id, &$file, &$connection, &$table)
    {
        $query = "SELECT id FROM $table WHERE parent_id = %d ORDER BY ordering";

        $result = $connection->query(sprintf($query, $id));

        $children = array();
        while ($row = $result->fetch_assoc()) {
            $children[] = $row['id'];
        }
        $result->close();

        foreach ($children as $child) {
            $file->fwrite($child."\n");
            $this->_insertChildren($child, $file, $connection, $table);
        }
    }

    /**
     * Import documents from the temporary table to the actual table
     *
     * @param KCommandContext $context
     */
    protected function _actionImport_documents(KCommandContext $context)
    {
        $limit   = 30;
        $offset  = $this->getRequest()->offset;
        $table   = $this->_getTableName('documents', $context->data->from_backup ? 'bkp' : 'tmp');
        $last_id = $offset;

        $ids = array();
        $result = $this->_fetchNext($table, $offset, $limit);
        while ($row = $result->fetch_assoc()) {
            $ids[] = $row['id'];
        }

        // Fetch the rows that were already imported (probably during a chunk that failed at some point)
        $query = sprintf('SELECT docman_document_id FROM #__docman_documents
            WHERE docman_document_id IN (%s)', implode(', ', $ids));
        $existing = JFactory::getDbo()->setQuery($query)->loadColumn();

        $result   = $this->_fetchNext($table, $offset, $limit);
        $imported = $result->num_rows;
        $row      = $this->getService('com://admin/docman.database.row.document');

        while ($data = $result->fetch_assoc())
        {
            $this->_convertDocument($data);

            /*
             * Update existing rows - since we checked that no rows are present when starting the migration
             * it is safe to assume that the row was created during a partially failed request
             */
            $new = in_array($data['id'], $existing) ? KDatabase::STATUS_UPDATED : null;
            $row->setStatus($new)->reset();

            $row->setData($data);

            $row->save();

            $last_id = $row->id;
        }

        $remaining = $this->_countRemaining($table, $last_id);

        $output = array(
            'completed' => $imported,
            'type'      => 'documents',
            'total'     => $this->_getTotal($table),
            'remaining' => $remaining,
            'offset'    => $last_id
        );

        if ($remaining > 0)
        {
            $url = sprintf('index.php?option=com_docman_import&view=import&format=json&offset=%d', $last_id);
            $output['next'] = $this->_createRoute($url);
        }

        $this->getView()->output = $output;
    }

    /**
     * Import categories from the temporary table to the actual table using the cached tree information
     *
     * @param KCommandContext $context
     */
    protected function _actionImport_categories(KCommandContext $context)
    {
        $limit  = 10;
        $offset = $this->getRequest()->offset; // Line number NOT id
        $db     = JFactory::getDbo();
        $table  = $this->_getTableName('categories', $context->data->from_backup ? 'bkp' : 'tmp');
        $query  = "SELECT * FROM $table WHERE id = %d";
        $row       = $this->getService('com://admin/docman.database.row.category');
        $last_line = $offset;

        $file = new SplFileObject(JPATH_ROOT.'/tmp/docman_export/tree_cache');
        $file->setFlags(SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

        // Collect IDs to insert
        $ids = array();
        foreach ($file as $i => $id)
        {
            if ($offset && $i < $offset) {
                continue;
            }

            $ids[$i+1] = $id;

            if ($limit && count($ids) === $limit) {
                break;
            }
        }

        $id_query = sprintf('SELECT docman_category_id FROM #__docman_categories WHERE docman_category_id IN (%s)', implode(', ', $ids));
        $existing = $db->setQuery($id_query)->loadColumn();
        $imported = 0;
        foreach ($ids as $line => $id)
        {
            $data = $db->setQuery(sprintf($query, $id))->loadAssoc();
            $this->_convertCategory($data);

            /*
             * Update existing rows - since we checked that no rows are present when starting the migration
             * it is safe to assume that the row was created during a partially failed request
             */
            $new = in_array($id, $existing) ? KDatabase::STATUS_UPDATED : null;

            $db->setQuery(sprintf('DELETE FROM #__docman_category_orderings WHERE docman_category_id = %d', $id))->query();
            $db->setQuery(sprintf('DELETE FROM #__docman_category_relations WHERE descendant_id = %d', $id))->query();

            $row->setStatus($new)->reset();

            $row->setData($data);
            $row->save();

            $last_line = $line;

            $imported++;
        }

        // Rewind to count the total number of lines
        $file->rewind();

        $total     = iterator_count($file);
        $remaining = $total-$last_line;

        $output = array(
            'completed' => $imported,
            'total'     => $total,
            'remaining' => $remaining,
            'offset'    => $last_line
        );

        if ($remaining > 0)
        {
            $url = sprintf('index.php?option=com_docman_import&view=import&format=json&offset=%d', $last_line);
            $output['next'] = $this->_createRoute($url);
        }

        $this->getView()->output = $output;
    }

    /**
     * Returns a list of array offsets to ignore in CSV rows
     *
     * @param array $fields Header row of the CSV file as an array
     * @param string $table Table name to get the base columns from
     * @return array
     */
    protected function _getIgnoredOffsets(array $fields, $table)
    {
        $table_columns = array_keys(JFactory::getDbo()->getTableColumns($table));
        $result = array();

        foreach ($fields as $i => $field)
        {
            if (!in_array($field, $table_columns)) {
                $result[] = $i;
            }
        }

        return $result;
    }

    /**
     * Unsets a list of offsets from an array
     *
     * @param array $data
     * @param array $unset An array of offsets to unset
     */
    protected function _unsetOffsets(&$data, array $unset)
    {
        foreach ($unset as $offset) {
            unset($data[$offset]);
        }
    }

    /**
     * Imports the selected chunk from the CSV file into the database
     *
     * @param SplFileObject $file
     * @param int           $offset
     * @param int           $limit
     * @return int
     * @throws RuntimeException
     */
    protected function _importCSV(SplFileObject $file, $offset = 0, $limit = 100)
    {
        $db      = $this->getService('koowa:database.adapter.mysqli');
        $query   = $this->getService('com://admin/docman_import.database.query.insert');
        $name    = $file->getBasename('.csv');

        if (!isset(self::$_table_map[$name])) {
            throw new RuntimeException('Unrecognized export file');
        }

        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
        $query->table(self::$_table_map[$name].'_tmp');

        $unset = array();

        $rows_per_query = 20;
        $queue_count = 0;
        $total_count = 0;

        foreach ($file as $i => $row)
        {
            if ($i === 0)
            {
                $unset = $this->_getIgnoredOffsets($row, JFactory::getDbo()->getPrefix().$query->table);

                $this->_unsetOffsets($row, $unset);

                $query->columns($row);

                continue;
            }

            if ($offset && $i <= $offset) {
                continue;
            }

            $this->_unsetOffsets($row, $unset);

            $query->values($row);
            $total_count++;
            $queue_count++;

            if ($queue_count === $rows_per_query)
            {
                $db->execute($query);
                $query->values = array();
                $queue_count = 0;
            }

            if ($limit && $total_count === $limit) {
                break;
            }

        }

        // There are some rows left to insert
        if ($queue_count) {
            $db->execute($query);
        }

        return $total_count;
    }

    /**
     * Gets the table name for the given type e.g. documents, categories
     *
     * @param $type
     * @param $suffix
     * @return mixed
     * @throws RuntimeException
     */
    protected function _getTableName($type, $suffix = null)
    {
        if (!array_key_exists($type, self::$_type_map)) {
            throw new RuntimeException('Invalid type');
        }

        $table = JFactory::getDbo()->replacePrefix(self::$_type_map[$type]);

        if ($suffix) {
            $table .= '_'.$suffix;
        }

        return $table;
    }

    /**
     * Gets the CSV path for the given type e.g. documents, categories
     *
     * @param $type
     * @return string
     * @throws RuntimeException
     */
    protected function _getPath($type)
    {
        if (!array_key_exists($type, self::$_type_map)) {
            throw new RuntimeException('Invalid type');
        }

        return JPATH_ROOT.'/tmp/docman_export/docman_export_'.$type.'.csv';
    }

    /**
     * Converts category data to Docman 2 structure
     *
     * @param $data
     */
    protected function _convertCategory(&$data)
    {
        $data['enabled'] = $data['published'];

        unset($data['access']);
    }

    /**
     * Converts document data to Docman 2 structure
     *
     * @param $data
     */
    protected function _convertDocument(&$data)
    {
        $data['docman_document_id'] = $data['id'];
        $data['docman_category_id'] = $data['catid'];
        $data['title']              = $data['dmname'];
        $data['description']        = $data['dmdescription'];
        $data['created_on']         = $data['dmdate_published'];
        $data['enabled']            = $data['published'];
        $data['hits']               = $data['dmcounter'];
        $data['modified_on']        = $data['dmlastupdateon'];
        $data['created_by']         = $data['dmsubmitedby'];

        if (strpos($data['dmfilename'], 'Link:') === 0) {
            $data['storage_type'] = 'remote';
            $data['storage_path'] = substr($data['dmfilename'], 6);
        } else {
            $data['storage_type'] = 'file';
            $data['storage_path'] = $data['dmfilename'];
        }

        unset($data['access']);
    }

    /**
     * Counts the number of rows in the given table
     *
     * @param $table
     * @return int
     */
    protected function _getTotal($table)
    {
        $query = sprintf('SELECT COUNT(*) FROM %s', $table);

        return (int) JFactory::getDbo()->setQuery($query)->loadResult();
    }

    /**
     * Fetches the next result set starting from the given ID
     *
     * @param     $table
     * @param     $offset
     * @param int $limit
     * @return mixed
     */
    protected function _fetchNext($table, $offset, $limit)
    {
        $db     = $this->getService('koowa:database.adapter.mysqli');
        $query = sprintf('SELECT * FROM %s WHERE id > %d ORDER BY id LIMIT %d', $table, $offset, $limit);

        $result = $db->execute($query);

        if (!$result) {
            throw new RuntimeException('Database query failed.');
        }

        return $result;
    }

    /**
     * Counts remaining rows in the table starting from the given ID
     * @param $table
     * @param $offset
     * @return int
     */
    protected function _countRemaining($table, $offset)
    {
        $query = sprintf('SELECT COUNT(*) FROM %s WHERE id > %d ORDER BY id', $table, $offset);

        return (int) JFactory::getDbo()->setQuery($query)->loadResult();
    }
}