<?php
/**
* vObject derived from JObject Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
*
* @package	VirtueMart
* @subpackage Helpers
* @author Max Milbers
* @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_tsmart/COPYRIGHT.php for copyright notices and details.
*
* http://tsmart.net
*/

class vObject {

    protected $_errors = array();
	public function __toString() {
		return get_class($this);
	}

	public function get($prop, $def = null) {
		if (isset($this->$prop)) {
			return $this->$prop;
		}
		return $def;
	}

	public function set($prop, $value = null) {
		$prev = isset($this->$prop) ? $this->$prop : null;
		$this->$prop = $value;
		return $prev;
	}

	public function setProperties($props) {

		if (is_array($props) || is_object($props)) {

			foreach ( $props as $k => $v) {
				$this->$k = $v;
			}
			return true;
		} else {
			return false;
		}
	}
    /**
     * Get the most recent error message.
     *
     * @param   integer  $i         Option error index.
     * @param   boolean  $toString  Indicates if JError objects should return their error message.
     *
     * @return  string   Error message
     *
     * @since   11.1
     * @see     JError
     * @deprecated 12.3  JError has been deprecated
     */
    public function getError($i = null, $toString = true)
    {
        // Find the error
        if ($i === null)
        {
            // Default, return the last message
            $error = end($this->_errors);
        }
        elseif (!array_key_exists($i, $this->_errors))
        {
            // If $i has been specified but does not exist, return false
            return false;
        }
        else
        {
            $error = $this->_errors[$i];
        }

        // Check if only the string is requested
        if ($error instanceof Exception && $toString)
        {
            return (string) $error;
        }

        return $error;
    }
    /**
     * Return all errors, if any.
     *
     * @return  array  Array of error messages or JErrors.
     *
     * @since   11.1
     * @see     JError
     * @deprecated 12.3  JError has been deprecated
     */
    public function getErrors()
    {
        return $this->_errors;
    }
    /**
     * Sets a default value if not already assigned
     *
     * @param   string  $property  The name of the property.
     * @param   mixed   $default   The default value.
     *
     * @return  mixed
     *
     * @since   11.1
     */
    public function def($property, $default = null)
    {
        $value = $this->get($property, $default);

        return $this->set($property, $value);
    }
    /**
     * Add an error message.
     *
     * @param   string  $error  Error message.
     *
     * @return  void
     *
     * @since   11.1
     * @see     JError
     * @deprecated 12.3  JError has been deprecated
     */
    public function setError($error)
    {
        array_push($this->_errors, $error);
    }


}
