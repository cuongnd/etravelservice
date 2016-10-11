<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Utilities
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * JUtility is a utility functions class
 *
 * @since  11.1
 */
class TSMUtility
{
	/**
	 * Method to extract key/value pairs out of a string with XML style attributes
	 *
	 * @param   string  $string  String containing XML style attributes
	 *
	 * @return  array  Key/Value pairs for the attributes
	 *
	 * @since   11.1
	 */
	public static function printDebugBacktrace($title = 'Debug Backtrace:')
	{
		$output = "";
		$output .= "<hr /><div>" . $title . '<br /><table border="1" cellpadding="2" cellspacing="2">';

		$stacks = debug_backtrace();

		$output .= "<thead><tr><th><strong>File</strong></th><th><strong>Line</strong></th><th><strong>Function</strong></th>" .
			"</tr></thead>";
		foreach ($stacks as $_stack) {
			if (!isset($_stack['file'])) $_stack['file'] = '[PHP Kernel]';
			if (!isset($_stack['line'])) $_stack['line'] = '';

			$output .= "<tr><td>{$_stack["file"]}</td><td>{$_stack["line"]}</td>" .
				"<td>{$_stack["function"]}</td></tr>";
		}
		$output .= "</table></div><hr /></p>";
		return $output;
	}
public static function clean($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

		return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}
    public  static function dateRange( $first, $last, $step = '+1 day', $format = 'Y/m/d' ) {

        $dates = array();
        $current = strtotime( $first );
        $last = strtotime( $last );

        while( $current <= $last ) {

            $dates[] = date( $format, $current );
            $current = strtotime( $step, $current );
        }

        return $dates;
    }
	public static function parseAttributes($string)
	{
		$attr = array();
		$retarray = array();

		// Let's grab all the key/value pairs using a regular expression
		preg_match_all('/([\w:-]+)[\s]?=[\s]?"([^"]*)"/i', $string, $attr);

		if (is_array($attr))
		{
			$numPairs = count($attr[1]);

			for ($i = 0; $i < $numPairs; $i++)
			{
				$retarray[$attr[1][$i]] = $attr[2][$i];
			}
		}

		return $retarray;
	}
	public  static function remove_string_javascript($str)
	{
		preg_match_all('/<script type=\"text\/javascript">(.*?)<\/script>/s', $str, $estimates);
		return $estimates[1][0];

	}

    public static function random_code($length=6)
    {
        $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $base = strlen($salt);
        $makepass = '';

        /*
         * Start with a cryptographic strength random string, then convert it to
         * a string with the numeric base of the salt.
         * Shift the base conversion on each character so the character
         * distribution is even, and randomize the start shift so it's not
         * predictable.
         */
        $random = JCrypt::genRandomBytes($length + 1);
        $shift = ord($random[0]);

        for ($i = 1; $i <= $length; ++$i)
        {
            $makepass .= $salt[($shift + ord($random[$i])) % $base];
            $shift += ord($random[$i]);
        }

        return $makepass;
    }
}