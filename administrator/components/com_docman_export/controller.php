<?php
/**
 * @package     DOCman Export
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class DocmanExportController extends JController
{
    protected $default_view = 'export';

    /**
     * Character used for quoting
     *
     * @var string
     */
    protected $quote = '"';

    /**
     * Character used for separating fields
     *
     * @var string
     */
    protected $separator = ',';

    protected static $_type_map = array(
        'documents'  => '#__docman',
        'categories' => '#__docman_categories'
    );

    /**
     * DB Connection
     * @var PDO
     */
    protected static $_db;

    /**
     * Returns a PDO connection to the current database
     *
     * We cannot use the Joomla connection since it's returning the entire result set in memory
     *
     * @return PDO
     */
    protected function _getDatabaseConnection()
    {
        if (!self::$_db)
        {
            $config = JFactory::getConfig();
            self::$_db = new PDO(
                sprintf('mysql:host=%s;dbname=%s', $config->getValue('host'), $config->getValue('db')),
                $config->getValue('user'),
                $config->getValue('password')
            );
            self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            self::$_db->exec("set names utf8");
        }

        return self::$_db;
    }

    /**
     * Forces format=raw and renders the HTML document
     *
     * @param bool $cachable
     * @param bool $urlparams
     * @return JController
     */
    public function display($cachable = false, $urlparams = false)
    {
        // We render the page ourselves so force format=raw
        if (JRequest::getCmd('format', 'html') === 'html')
        {
            JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_docman_export&format=raw', false));

            return $this;
        }

        $this->_resetDocument('html');

        ob_start();

        // Taken from 2.5
        $document = JFactory::getDocument();
        $viewType = $document->getType();
        $viewName = JRequest::getCmd('view', $this->default_view);
        $viewLayout = JRequest::getCmd('layout', 'default');

        $view = $this->getView($viewName, $viewType, '', array('base_path' => JPATH_COMPONENT, 'layout' => $viewLayout));

        $view->assignRef('document', $document);
        $view->display();

        // End of 2.5 code
        $result = ob_get_clean();

        $params = array(
            'directory'	=> dirname(__FILE__).'/views/export',
            'template'	=> 'tmpl',
            'file'		=> 'index.php',
            'params'	=> array()
        );

        $document = JFactory::getDocument();

        if (version_compare(JVERSION, '1.6', '>=')) {
            $document->parse($params);
        }

        $document->setBuffer($result, 'component');

        $result = $document->render(false, $params);

        // Revert back to JDocumentRaw to complete the request
        $this->_resetDocument('raw');

        echo $result;

        return $this;
    }

    /**
     * Prepares the archive file for download
     *
     * @throws RuntimeException
     */
    public function prepare()
    {
        if (!is_writable(JPATH_ROOT.'/tmp')) {
            throw new RuntimeException('Please make sure tmp directory in your site root is writable');
        }

        $path = JPATH_ROOT.'/tmp/docman_export.zip';

        if (file_exists($path)) {
            unlink($path);
        }

        if (!is_file($this->_getPath('categories')) || !is_file($this->_getPath('documents'))) {
            throw new RuntimeException('Data files are missing. Please run the export process again.');
        }

        $zip  = new ZipArchive();

        if ($zip->open($path, ZipArchive::CREATE) !== true) {
            throw new RuntimeException('Cannot create the archive file');
        }

        $zip->addFile($this->_getPath('categories'), 'docman_export_categories.csv');
        $zip->addFile($this->_getPath('documents'), 'docman_export_documents.csv');

        $zip->close();

        $result = array(
            'status' => file_exists($path)
        );

        echo json_encode($result);
    }

    /**
     * Runs the export process for the given chunk and writes it to the CSV file
     *
     * @throws RuntimeException
     */
    public function export()
    {
        $type   = JRequest::getCmd('type', 'documents');
        $table  = $this->_getTableName($type);
        $offset = JRequest::getInt('offset', 0);

        if (!is_writable(JPATH_ROOT.'/tmp')) {
            throw new RuntimeException('Please make sure tmp directory in your site root is writable');
        }

        $file = new SplFileObject($this->_getPath($type), 'a');

        if ($offset === 0)
        {
            $file->ftruncate(0);
            $this->_writeColumns($table, $file);
        }

        $data      = $this->_fetchNext($table, $offset);
        $exported  = $data->rowCount();
        $last_id   = $offset;

        while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
            $last_id = $row['id'];
            $file->fwrite($this->_arrayToString($row)."\n");
        }

        $total     = $this->_getTotal($table);
        $remaining = $this->_countRemaining($table, $last_id);

        $output = array(
            'exported'  => $exported,
            'type'      => $type,
            'total'     => $total,
            'remaining' => $remaining,
            'offset'    => $last_id
        );

        if ($remaining > 0)
        {
            $prefix = version_compare(JVERSION, '1.6', '<')
                ? JURI::base()
                : JURI::getInstance()->toString(array('scheme', 'user', 'pass', 'host', 'port'));
            $url = sprintf('index.php?option=com_docman_export&task=export&format=json&type=%s&offset=%d', $type, $last_id);
            $output['next'] = $prefix.JRoute::_($url, false);
        }

        echo json_encode($output);
    }

    protected function _resetDocument($format)
    {
        if (version_compare(JVERSION, '2.5', '>=')) {
            $input = JFactory::getApplication()->input;
            $input->set('format', $format);
        }

        if (version_compare(JVERSION, '3.0', '<')) {
            JRequest::setVar('format', $format);
        }

        if (version_compare(JVERSION, '1.6', '<')) {
            $document =& JFactory::getDocument();
            $document = null;
        } else {
            JFactory::$document = null;
        }

        JFactory::getDocument();
    }

    /**
     * Writes the table columns to the CSV file
     *
     * @param               $table
     * @param SplFileObject $file
     */
    protected function _writeColumns($table, SplFileObject $file)
    {
        if (version_compare(JVERSION, '1.6', '>=')) {
            $fields = array_keys(JFactory::getDbo()->getTableColumns($table, false));
        }
        else {
            $tables = JFactory::getDbo()->getTableFields($table, false);
            $fields = array_keys($tables[$table]);
        }

        $header = $this->_arrayToString($fields)."\n";

        $file->fwrite($header);
    }

    /**
     * Gets the table name for the given type e.g. documents, categories
     * @param $type
     * @return mixed
     * @throws RuntimeException
     */
    protected function _getTableName($type)
    {
        if (!array_key_exists($type, self::$_type_map)) {
            throw new RuntimeException('Invalid type');
        }

        $result = self::$_type_map[$type];

        if ($type === 'categories' && version_compare(JVERSION, '1.6', '<')) {
            $result = '#__categories';
        }

        $db = JFactory::getDbo();

        if (!in_array($db->replacePrefix($result), $db->getTableList())) {
            $version = version_compare(JVERSION, '1.6', '<') ? '1.5' : '1.6';
            throw new RuntimeException("Database table for $type is missing. Are you sure DOCman $version is installed?");
        }

        return $result;
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

        return JPATH_ROOT.'/tmp/docman_export_'.$type.'.csv';
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

        $db = JFactory::getDbo();
        $db->setQuery($query);

        return (int) $db->loadResult();
    }

    /**
     * Fetches the next result set starting from the given ID
     *
     * @param     $table
     * @param     $offset
     * @param int $limit
     * @return mixed
     */
    protected function _fetchNext($table, $offset, $limit = 300)
    {
        $where = '';

        if (strpos($table, 'categories') !== false) {
            $where = " AND section='com_docman'";
        }

        $query = sprintf('SELECT * FROM %s WHERE id > %d %s ORDER BY id LIMIT %d', $table, $offset, $where, $limit);
        $query = JFactory::getDbo()->replacePrefix($query);

        $result = $this->_getDatabaseConnection()->query($query);

        if (!$result) {
            throw new RuntimeException('Database query failed'.$query);
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

        $db = JFactory::getDbo();
        $db->setQuery($query);

        return (int) $db->loadResult();
    }

    /**
     * Render an array as CSV
     *
     * @param	array	$data Value
     * @return 	boolean
     */
    protected function _arrayToString($data)
    {
        $fields = array();
        foreach($data as $value)
        {
            //Implode array's
            if(is_array($value)) {
                $value = implode(',', $value);
            }

            // Escape the quote character within the field (e.g. " becomes "")
            if ($this->_quoteValue($value))
            {
                $quoted_value = str_replace($this->quote, $this->quote.$this->quote, $value);
                $fields[] 	  = $this->quote . $quoted_value . $this->quote;
            }
            else $fields[] = $value;
        }

        return  implode($this->separator, $fields);
    }

    /**
     * Check if the value should be quoted
     *
     * @param	string	$value Value
     * @return 	boolean
     */
    protected function _quoteValue($value)
    {
        if(is_numeric($value)) {
            return false;
        }

        if(strpos($value, $this->separator) !== false) { // Separator is present in field
            return true;
        }

        if(strpos($value, $this->quote) !== false) { // Quote character is present in field
            return true;
        }

        if (strpos($value, "\n") !== false || strpos($value, "\r") !== false ) { // Newline is present in field
            return true;
        }

        if(substr($value, 0, 1) == " " || substr($value, -1) == " ") {  // Space found at beginning or end of field value
            return true;
        }

        return false;
    }


    /**
     * Method to get a singleton controller instance.
     *
     * @param   string  $prefix  The prefix for the controller.
     * @param   array   $config  An array of optional constructor options.
     *
     * @return  JController
     *
     * @since   11.1
     * @throws  Exception if the controller cannot be loaded.
     */
    public static function getInstance($prefix, $config = array())
    {
        // Get the environment configuration.
        $basePath = array_key_exists('base_path', $config) ? $config['base_path'] : JPATH_COMPONENT;
        $format = JRequest::getWord('format');
        $command = JRequest::getVar('task', 'display');

        // Check for array format.
        $filter = JFilterInput::getInstance();

        if (is_array($command))
        {
            $command = $filter->clean(array_pop(array_keys($command)), 'cmd');
        }
        else
        {
            $command = $filter->clean($command, 'cmd');
        }

        // Check for a controller.task command.
        if (strpos($command, '.') !== false)
        {
            // Explode the controller.task command.
            list ($type, $task) = explode('.', $command);

            // Define the controller filename and path.
            $file = self::createFileName('controller', array('name' => $type, 'format' => $format));
            $path = $basePath . '/controllers/' . $file;

            // Reset the task without the controller context.
            JRequest::setVar('task', $task);
        }
        else
        {
            // Base controller.
            $type = null;
            $task = $command;

            // Define the controller filename and path.
            $file		 = self::createFileName('controller', array('name' => 'controller', 'format' => $format));
            $path		 = $basePath . '/' . $file;
            $backupfile  = self::createFileName('controller', array('name' => 'controller'));
            $backuppath  = $basePath . '/' . $backupfile;
        }

        // Get the controller class name.
        $class = ucfirst($prefix) . 'Controller' . ucfirst($type);

        // Include the class if not present.
        if (!class_exists($class))
        {
            // If the controller file path exists, include it.
            if (file_exists($path))
            {
                require_once $path;
            }
            elseif (isset($backuppath) && file_exists($backuppath))
            {
                require_once $backuppath;
            }
            else
            {
                throw new InvalidArgumentException(JText::sprintf('JLIB_APPLICATION_ERROR_INVALID_CONTROLLER', $type, $format));
            }
        }

        // Instantiate the class.
        if (!class_exists($class)) {
            throw new InvalidArgumentException(JText::sprintf('JLIB_APPLICATION_ERROR_INVALID_CONTROLLER_CLASS', $class));
        }

        return new $class($config);
    }

    protected static function createFileName($type, $parts = array())
    {
        $filename = '';

        switch ($type)
        {
            case 'controller':
                if (!empty($parts['format']))
                {
                    if ($parts['format'] == 'html')
                    {
                        $parts['format'] = '';
                    }
                    else
                    {
                        $parts['format'] = '.' . $parts['format'];
                    }
                }
                else
                {
                    $parts['format'] = '';
                }

                $filename = strtolower($parts['name']) . $parts['format'] . '.php';
                break;

            case 'view':
                if (!empty($parts['type']))
                {
                    $parts['type'] = '.' . $parts['type'];
                }

                $filename = strtolower($parts['name']) . '/view' . $parts['type'] . '.php';
                break;
        }

        return $filename;
    }
}