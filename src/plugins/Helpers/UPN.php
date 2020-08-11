<?php
//**************************************************************************************
//**************************************************************************************
/**
 * Universal Path Notation (UPN) Plugin
 *
 * @package		phpOpenFW
 * @author 		Christian J. Clark
 * @copyright	Copyright (c) Christian J. Clark
 * @license		https://mit-license.org
 **/
//**************************************************************************************
//**************************************************************************************

namespace phpOpenFW\Helpers;

//**************************************************************************************
/**
 * UPN Class
 */
//**************************************************************************************
class UPN
{
    //==================================================================================
    //==================================================================================
    // Get Method
    //==================================================================================
    //==================================================================================
    public static function Get($full_path, $in_subject=null)
    {
        //------------------------------------------------------------------------------
        // Trim Path
        //------------------------------------------------------------------------------
        $full_path = trim($full_path);

        //------------------------------------------------------------------------------
        // Get Path / Handler
        //------------------------------------------------------------------------------
        list($handler, $path) = self::GetPathAndHandler($full_path);

        //------------------------------------------------------------------------------
        // Determine Subject from Handler
        //------------------------------------------------------------------------------
        $subject = &self::GetSubject($handler, $in_subject);
        if ($path == '/') {
            return $subject;
        }

        //------------------------------------------------------------------------------
        // Get Path Parts
        //------------------------------------------------------------------------------
        $path_parts = self::GetPathParts($path);

        //------------------------------------------------------------------------------
        // Get Value
        //------------------------------------------------------------------------------
        if ($path_parts) {
            $tmp_subject = $subject;
            foreach ($path_parts as $key => $pp) {
                if ($pp == '') {
                    if (!$key) {
                        continue;
                    }
                    else {
                        return null;
                    }
                }
	            if (isset($tmp_subject[$pp])) {
		            $tmp_subject = $tmp_subject[$pp];
	            }
	            else {
		            return null;
	            }
            }
            return $tmp_subject;
        }

        //------------------------------------------------------------------------------
        // Default is NULL, which means not found.
        //------------------------------------------------------------------------------
        return null;
    }

    //==================================================================================
    //==================================================================================
    // Set Method
    //==================================================================================
    //==================================================================================
    public static function Set($full_path, $value, &$in_subject=null, $set_root=false)
    {
        //------------------------------------------------------------------------------
        // Trim Path
        //------------------------------------------------------------------------------
        $full_path = trim($full_path);

        //------------------------------------------------------------------------------
        // Get Path / Handler
        //------------------------------------------------------------------------------
        list($handler, $path) = self::GetPathAndHandler($full_path);

        //------------------------------------------------------------------------------
        // Determine Subject from Handler
        //------------------------------------------------------------------------------
        $subject = &self::GetSubject($handler, $in_subject);
        if ($path == '/' && $set_root) {
            $subject = $value;
        }

        //------------------------------------------------------------------------------
        // Get Path Parts
        //------------------------------------------------------------------------------
        $path_parts = self::GetPathParts($path);

        //------------------------------------------------------------------------------
        // Set Value
        //------------------------------------------------------------------------------
        if ($path_parts) {
            return self::_Set($subject, $path_parts, $value);
        }
        return false;
    }

    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    // Protected / Internal Methods
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //==================================================================================
    //==================================================================================
    // Get Path and Handler
    //==================================================================================
    //==================================================================================
    protected static function GetPathAndHandler($path)
    {
        //------------------------------------------------------------------------------
        // Is Path Valid?
        //------------------------------------------------------------------------------
        if (!$path) {
            throw new \Exception('Empty UPN path .');
        }

        //------------------------------------------------------------------------------
        // Get Handler
        //------------------------------------------------------------------------------
        $upn_parts = explode(':/', $path);
        if (count($upn_parts) < 2) {
            throw new \Exception('Invalid UPN path (1).');
        }

        //------------------------------------------------------------------------------
        // Validate Handler
        //------------------------------------------------------------------------------
        if (!self::ValidateHandler($upn_parts[0])) {
            throw new \Exception('Invalid UPN handler.');
        }

        //------------------------------------------------------------------------------
        // Validate Path is NOT empty
        //------------------------------------------------------------------------------
        //if ($upn_parts[1] == '') {}

        //------------------------------------------------------------------------------
        // Return Handler / Path
        //------------------------------------------------------------------------------
        return $upn_parts;
    }

    //==================================================================================
    //==================================================================================
    // Validate Handler
    //==================================================================================
    //==================================================================================
    protected static function ValidateHandler($handler)
    {
        //------------------------------------------------------------------------------
        // Empty Handler
        //------------------------------------------------------------------------------
        if (!$handler) {
            return false;
        }

        //------------------------------------------------------------------------------
        // Define Handlers
        //------------------------------------------------------------------------------
        $handlers = [
            'config',
            'array',
            'session',
            'post',
            'get',
            'request',
            'server',
            'globals'
        ];

        //------------------------------------------------------------------------------
        // Is Handler Valid
        //------------------------------------------------------------------------------
        if (!in_array($handler, $handlers)) {
            return false;
        }

        return true;
    }

    //==================================================================================
    //==================================================================================
    // Get Subject
    //==================================================================================
    //==================================================================================
    protected static function &GetSubject($handler, &$in_subject=null)
    {
        //------------------------------------------------------------------------------
        // Validate Handler
        //------------------------------------------------------------------------------
        switch ($handler) {

            case 'config':
                if (!isset($_SESSION['config'])) {
                    $_SESSION['config'] = [];
                }
                return $_SESSION['config'];
                break;

            case 'array':
                if (is_array($in_subject)) {
                    return $in_subject;
                }
                else {
                    throw new \Exception('Invalid subject (array).');
                }
                break;

            case 'session':
                return $_SESSION;
                break;

            case 'post':
                return $_POST;
                break;

            case 'get':
                return $_GET;
                break;

            case 'request':
                return $_REQUEST;
                break;

            case 'server':
                return $_SERVER;
                break;

            case 'globals':
                return $GLOBALS;
                break;

        }
    }

    //==================================================================================
    //==================================================================================
    // Get Path Parts
    //==================================================================================
    //==================================================================================
    public static function GetPathParts($path)
    {
        //------------------------------------------------------------------------------
        // Validate Path Type
        //------------------------------------------------------------------------------
        if (!$path || !is_string($path)) {
            throw new \Exception('Invalid UPN path.');
        }

        //------------------------------------------------------------------------------
        // Explode path from string to array
        //------------------------------------------------------------------------------
        $path_parts = explode('/', $path);

        //------------------------------------------------------------------------------
        // Is first element empty? (Caused by leading '/') Remove it.
        //------------------------------------------------------------------------------
        if ($path_parts && $path_parts[0] == '') {
            $path_parts = array_slice($path_parts, 1);
        }

        //------------------------------------------------------------------------------
        // Is last element empty? (Caused by trailing '/') Remove it.
        //------------------------------------------------------------------------------
        if ($path_parts) {
            $last_pos = count($path_parts) - 1;
            if ($path_parts[$last_pos] == '') {
    	        array_pop($path_parts);
            }
        }

        //------------------------------------------------------------------------------
        // Return Path Parts
        //------------------------------------------------------------------------------
        return $path_parts;
    }

    //==================================================================================
    //==================================================================================
    // Internal Set Method
    //==================================================================================
    //==================================================================================
    protected static function _Set(&$subject, Array $path_parts, $value)
    {
        $pp = array_shift($path_parts);
        if (!$path_parts) {
            $subject[$pp] = $value;
            return true;
        }
        else {
            if ($pp != '') {
                if (!isset($subject[$pp])) {
                    $subject[$pp] = [];
                }
                return self::_Set($subject[$pp], $path_parts, $value);
            }
		}
		return false;
    }

    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    // WARNING! DEPRECATED!
    // ---------------------------------------------------------------------------------
    // The below functionality is deprecated and will be removed in version 3.0
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //==================================================================================
    //==================================================================================
    // Main UPN Handler Method
    //==================================================================================
    //==================================================================================
    public static function _()
    {
        //----------------------------------------------------
        // Process Arguments
        //----------------------------------------------------
        $args = self::ProcessArgs(func_get_args());
        if (!$args) { return false; }
        if (is_array($args)) { extract($args); }
        else { return false; }

        //----------------------------------------------------
        // Process Path Parts
        //----------------------------------------------------
        foreach ($path_parts as $part) {

            //----------------------------------------------------
            // Set Data Element
            //----------------------------------------------------
            if ($mode == 'set') {
	            if ($path_parts) {
		            $recurse = function($subject, $path_parts, $value) use (&$recurse)
		            {
			            $pp = array_shift($path_parts);
			            if (!$path_parts) {
				            $subject[$pp] = $value;
			            }
			            else {
				            if (!isset($subject[$pp])) { $subject[$pp] = []; }
				            $subject[$pp] = $recurse($subject[$pp], $path_parts, $value);
						}
						return $subject;
		            };
					$subject = $recurse($subject, $path_parts, $value);
		            return true;
		        }
                return false;
            }
            //----------------------------------------------------
            // Get Data Element
            //----------------------------------------------------
            else {
	            if ($path_parts) {
		            $tmp_subject = &$subject;
		            foreach ($path_parts as $pp) {
			            if (isset($tmp_subject[$pp])) {
				            $tmp_subject = $tmp_subject[$pp];
			            }
			            else {
				            return null;
			            }
		            }
		            return $tmp_subject;
	            }
                return null;
            }
        }
    }

    //==================================================================================
    //==================================================================================
    // Process Arguments
    //==================================================================================
    //==================================================================================
    protected static function ProcessArgs($args)
    {
        //----------------------------------------------------
        // Pull / Set Args
        //----------------------------------------------------
        $num_args = count($args);
        $args_0 = (!empty($args[0])) ? ($args[0]) : (false);
        $args_1 = (!empty($args[1])) ? ($args[1]) : (false);
        $args_2 = (!empty($args[2])) ? ($args[2]) : (false);

        //----------------------------------------------------
        // Valid Data Element
        //----------------------------------------------------
        if ($args_0 == '') {
            trigger_error('UPN path not given.');
            return false;
        }
        $full_upn = $args_0;

        //----------------------------------------------------
        // Get Handler
        //----------------------------------------------------
        $upn_parts = explode(':/', $full_upn);
        if (count($upn_parts) < 2) {
            trigger_error('Invalid UPN Path.');
            return false;
        }
        $handler = $upn_parts[0];
        $mode = false;
        $value = false;

        //----------------------------------------------------
        // Validate Handler
        //----------------------------------------------------
        switch ($handler) {

            case 'config':
                $mode = ($num_args > 1) ? ('set') : ('get');
                $subject = (isset($_SESSION['config'])) ?: (false);
                if ($mode == 'set') { $value = $args_1; }
                break;

            case 'json':
                $mode = ($num_args > 2) ? ('set') : ('get');
                $subject = json_decode($args_2);
                if ($mode == 'set') { $value = $args_2; }
                break;

            case 'array':
                $mode = ($num_args > 2) ? ('set') : ('get');
                $subject = (is_array($args_2)) ? ($args_2) : (false);
                if ($mode == 'set') { $value = $args_2; }
                break;

            case 'session':
            case 'post':
            case 'get':
            case 'request':
            case 'server':
            case 'globals':
                $mode = ($num_args > 1) ? ('set') : ('get');
                if ($mode == 'set') { $value = $args_1; }
                switch ($handler) {
                    case 'session':
                        $subject =& $_SESSION;
                        break;
                    case 'post':
                        $subject =& $_POST;
                        break;
                    case 'get':
                        $subject =& $_GET;
                        break;
                    case 'request':
                        $subject =& $_REQUEST;
                        break;
                    case 'server':
                        $subject =& $_SERVER;
                        break;
                    case 'globals':
                        $subject =& $GLOBALS;
                        break;
                }
                break;

            default:
                trigger_error('Unknown UPN path type.');
                return false;
                break;

        }

        //----------------------------------------------------
        // Get Path Parts
        //----------------------------------------------------
        $path_parts = explode('/', $upn_parts[1]);
        if ($path_parts[0] == '') {
	        array_shift($path_parts);
        }
        $last_pos = count($path_parts) - 1;
        if ($path_parts[$last_pos] == '') {
	        array_pop($path_parts);
        }

        //----------------------------------------------------
        // Return Data
        //----------------------------------------------------
        return [
            'full_upn' => $full_upn,
            'handler' => $handler,
            'path_parts' => $path_parts,
            'mode' => $mode,
            'subject' => $subject,
            'value' => $value
        ];
    }

    //==================================================================================
    //==================================================================================
}
