<?php
//*****************************************************************************
//*****************************************************************************
/**
* Content Delivery Plugin
*
* @package		phpOpenPlugins
* @subpackage	Content
* @author 		Christian J. Clark
* @copyright	Copyright (c) Christian J. Clark
* @license		http://www.gnu.org/licenses/gpl-2.0.txt
* @link			http://www.emonlade.net/phpopenplugins/
* @version 		Started: 8/25/2015, Last updated: 6/7/2016
**/
//*****************************************************************************
//*****************************************************************************

//*******************************************************************************
//*******************************************************************************
// Content Delivery Object
//*******************************************************************************
//*******************************************************************************
class CDN
{

	//*****************************************************************************
	//*****************************************************************************
	// Output Content Type Header
	//*****************************************************************************
	//*****************************************************************************
	public static function OutputContentType($file)
	{
		$path_parts = pathinfo($file);
		if (empty($path_parts['extension'])) {
			return false;
		}
		$ext = strtolower($path_parts['extension']);

		switch ($ext) {

			//======================================================
			// Javascript
			//======================================================
			case 'js':
				header('Content-type: text/javascript');
				break;

			//======================================================
			// CSS
			//======================================================
			case 'css':
				header('Content-type: text/css');
				break;

			//======================================================
			// Images
			//======================================================
			case 'jpg':
			case 'jpeg':
			case 'gif':
			case 'png':
				header("Content-type: image/{$ext}");
				break;

			//======================================================
			// Scalable Vector Graphics (SVG)
			//======================================================
			case 'svg':
			case 'svgz':
				header("Content-type: image/svg+xml");
				if ($ext == 'svgz') {
					header("Content-Encoding: gzip");	
				}
				break;

			//======================================================
			// XML
			//======================================================
			case 'xml':
			case 'xsl':
				header('Content-type: text/xml');
				break;

			//======================================================
			// HTML / XHTML
			//======================================================
			case 'html':
			case 'xhtml':
				header('Content-type: text/html');
				break;

			//======================================================
			// Text
			//======================================================
			case 'txt':
			case 'json':
				header('Content-type: text/plain');
				break;

			//======================================================
			// Default: File Not Found (i.e. 404)
			//======================================================
			default:
				return false;
				break;
		}

		return true;
	}

}

