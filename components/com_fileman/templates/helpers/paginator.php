<?php
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2011 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

class ComFilemanTemplateHelperPaginator extends KTemplateHelperPaginator
{
    public function pagination($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'total'      => 0,
            'display'    => 10,
            'offset'     => 0,
            'limit'      => 0,
            'attribs'    => array('onchange' => 'this.form.submit();')
        ));

        $this->_initialize($config);

        $html = '<div class="files-pagination">';
        if ($config->show_limit) {
            $html .= '<div class="limit" style="display: inline">'.$this->limit($config).'</div>';
        }
		$html .= $this->_pages($this->_items($config)).'</div>';
        	
        return $html;
    }

    protected function _pages($pages)
    {
    	$html = '';

        if($pages['previous']->active) {
            $html  .= '<span class="prev">'.$this->_link($pages['previous'], 'Prev').'</span>';
        }

        foreach($pages['pages'] as $page) {
            $html .= $this->_link($page, $page->page);
        }

        if($pages['next']->active) {
            $html  .= '<span class="next">'.$this->_link($pages['next'], 'Next').'</span>';
        }

        return $html;
    }

    protected function _link($page, $title)
    {
        $url   = clone KRequest::url();
        $query = $url->getQuery(true);

        //For compatibility with Joomla use limitstart instead of offset
        $query['limit']      = $page->limit;
        $query['limitstart'] = $page->offset;

        $url->setQuery($query);

        $class = $page->current ? 'class="active"' : '';

        if($page->active && !$page->current) {
            $html = '<a href="'.$url.'" '.$class.'>'.JText::_($title).'</a>';
        } else {
            $html = '<span '.$class.'>'.JText::_($title).'</span>';
        }

        return $html;
    }
}