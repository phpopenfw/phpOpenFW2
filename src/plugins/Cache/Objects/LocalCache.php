<?php
//*****************************************************************************
//*****************************************************************************
/**
* Local Data Cache Object
*
* @package		phpOpenPlugins
* @subpackage	Utilities
* @author 		Christian J. Clark
* @copyright	Copyright (c) Christian J. Clark
* @license		http://www.gnu.org/licenses/gpl-2.0.txt
* @link			http://www.emonlade.net/phpopenplugins/
* @version 		Started: 1/28/2012, Last updated: 2/23/2012
**/
//*****************************************************************************
//*****************************************************************************

//*******************************************************************************
//*******************************************************************************
// Local Level Data Cache Object
//*******************************************************************************
//*******************************************************************************
class LocalCache extends Core
{

	//*************************************************************************
	// Constructor Function
	//*************************************************************************
    public function __construct()
    {
        $this->container = array();
        $this->scope = 'local';
		$this->existed = false;
    }

}
