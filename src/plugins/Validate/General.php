<?php
//*****************************************************************************
//*****************************************************************************
/**
* General Validations Class
*
* @package		phpOpenFW
* @subpackage	Validate
* @author 		Christian J. Clark
* @copyright	Copyright (c) Christian J. Clark
* @license		http://www.gnu.org/licenses/gpl-2.0.txt
* @version 		Started: 1-4-2005 Updated: 4-2-2013
**/
//*****************************************************************************
//*****************************************************************************

//*****************************************************************************
/**
 * General Validations Class
 * @package		phpOpenFW
 * @subpackage	Validate
 */
//*****************************************************************************
class General
{

	//=============================================================================
	//=============================================================================
	// Is Valid Username Function
	//=============================================================================
	//=============================================================================
	public static function IsValidUserName($username)
	{
		$userid_regex = '/^[a-z\d_]{4,28}$/i';
		return (preg_match($userid_regex, $username)) ? (1) : (0);
	}
	
	//=============================================================================
	//=============================================================================
	// Is Function Function :)
	//=============================================================================
	//=============================================================================
	public static function IsFunction($f)
	{
		return (gettype($f) == 'object') ? (true) : (false);
	}
	
}
