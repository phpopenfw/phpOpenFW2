<?php
//*****************************************************************************
/**
* Content Formatting Class
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
 * Content Formatting Class
 * @package		phpOpenFW
 * @subpackage	Format
 */
//*****************************************************************************
class Content
{
	//=============================================================================
	//=============================================================================
	// Sanitize and Escape for HTML Output Function
	//=============================================================================
	//=============================================================================
	public static function html_sanitize($s)
	{
		$s = preg_replace('/[\x00-\x1F\x7F]/', '', (string)$s);
		return htmlspecialchars(strip_tags($s));
	}

	//=============================================================================
	//=============================================================================
	// Escape for HTML Output Function
	//=============================================================================
	//=============================================================================
	public static function html_escape($s)
	{
		$s = preg_replace('/[^\xA|\xC|(\x20-\x7F)]*/', '', (string)$s);
		return htmlspecialchars($s);
	}

	//=============================================================================
	//=============================================================================
	// Fill If Empty Function
	//=============================================================================
	//=============================================================================
	public static function fill_if_empty(&$data, $empty_val='--')
	{
		$data = (trim((string)$data) == '') ? ($empty_val) : (trim($data));
	}

	//=============================================================================
	//=============================================================================
	/**
	* Return a CSS based Icon
	*
	* @param string Icon to use i.e. 'fa fa-check'
	*
	* @return string HTML CSS Icon
	*/
	//=============================================================================
	//=============================================================================
	public static function css_icon($i)
	{
		if (empty($i)) { return false; }
		return "<i class=\"{$i}\"></i>";
	}

	//=============================================================================
	//=============================================================================
	// Display Error Function
	//=============================================================================
	//=============================================================================
	public static function display_error($scope, $error_msg, $error_type=E_USER_NOTICE)
	{
		$tmp_msg = "Error :: {$scope}() - {$error_msg}";
		return trigger_error($tmp_msg, $error_type);
	}

	//=============================================================================
	//=============================================================================
	// Format Filesize Function
	//=============================================================================
	//=============================================================================
	public static function FormatFilesize($bytes)
	{
	    if ($bytes < 1024) {
	        return $bytes .' B';
	    }
	    elseif ($bytes < 1048576) {
	        return round($bytes / 1024, 2) .' KB';
	    }
	    elseif ($bytes < 1073741824) {
	        return round($bytes / 1048576, 2) . ' MB';
	    }
	    else {
	        return round($bytes / 1073741824, 2) . ' GB';
	    }
	}
	
	//=============================================================================
	//=============================================================================
	// Get Saveable Password Function
	//=============================================================================
	//=============================================================================
	public static function GetSaveablePassword($pass, $aps=false)
	{
		if (!$aps && isset($_SESSION['auth_pass_security'])) {
			$aps = strtolower($_SESSION['auth_pass_security']);
		}
	
		if ($aps) {
			switch ($aps) {
	
				case 'sha1':
					return sha1($pass);
					break;
	
				case 'sha256':
					return hash('sha256', $pass);
					break;
	
				case 'md5':
					return md5($pass);
					break;
			}
		}
	
		return $pass;
	}

	//=============================================================================
	//=============================================================================
	// Print Code Function
	//=============================================================================
	//=============================================================================
	public static function PrintCode($code, $return=false)
	{
		if ($return) { ob_start(); }
		print div(nl2br($code), array("class" => "code_box"));
		if ($return) { return ob_get_clean(); }
	}

	//=============================================================================
	//=============================================================================
	// Generate a globally unique identifier (GUID) Function
	//=============================================================================
	//=============================================================================
	public static function GUID()
	{
	    if (function_exists('com_create_guid') === true) {
	        return trim(com_create_guid(), '{}');
	    }
	
	    return sprintf(
	    	'%04X%04X-%04X-%04X-%04X-%04X%04X%04X', 
	    	mt_rand(0, 65535), 
	    	mt_rand(0, 65535), 
	    	mt_rand(0, 65535), 
	    	mt_rand(16384, 20479), 
	    	mt_rand(32768, 49151), 
	    	mt_rand(0, 65535), 
	    	mt_rand(0, 65535), 
	    	mt_rand(0, 65535)
	    );
	}

}
