<?php
//*****************************************************************************
/**
* Output Formatting Class
*
* @package		phpOpenFW
* @subpackage	Format
* @author 		Christian J. Clark
* @copyright	Copyright (c) Christian J. Clark
* @license		http://www.gnu.org/licenses/gpl-2.0.txt
* @version 		Started: 1-4-2005 Updated: 4-2-2013
**/
//*****************************************************************************

//*****************************************************************************
/**
 * Output Formatting Class
 * @package		phpOpenFW
 * @subpackage	Format
 */
//*****************************************************************************
class Output
{

	//*****************************************************************************
	//*****************************************************************************
	/**
	* Print a preformatted array or Simple XML Element Object (nicely viewable in HTML or CLI)
	* @param array Array to Print. Multiple Arrays can be passed.
	*/
	//*****************************************************************************
	//*****************************************************************************
	public static function PrintArray()
	{
		$sapi = strtoupper(php_sapi_name());
		$arg_list = func_get_args();
		foreach ($arg_list as $in_array) {
			if (
				is_array($in_array) 
				|| (gettype($in_array) == 'object' 
				&& (get_class($in_array) == 'SimpleXMLElement' || get_class($in_array) == 'stdClass'))
			) {
				if ($sapi != 'CLI') { print "<pre>\n"; }
				print_r($in_array);
				if ($sapi != 'CLI') { print "</pre>\n"; }
			}
		}
	}
	
}
