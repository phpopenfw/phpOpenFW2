<?php
//**************************************************************************************
//**************************************************************************************
/**
 * SQL Select Trait
 *
 * @package		phpOpenFW
 * @author 		Christian J. Clark
 * @copyright	Copyright (c) Christian J. Clark
 * @license		https://mit-license.org
 **/
//**************************************************************************************
//**************************************************************************************

namespace phpOpenFW\Builders\SQL\Traits;

//**************************************************************************************
/**
 * SQL Select Trait
 */
//**************************************************************************************
trait Select
{
    //=========================================================================
	// Trait Memebers
    //=========================================================================
	protected $fields = [];

    //=========================================================================
    //=========================================================================
	// Select Clause Method
    //=========================================================================
    //=========================================================================
	public function Select($field)
	{
    	return $this->CSC_AddItem($this->fields, $field);
	}

    //=========================================================================
    //=========================================================================
	// Raw Select Clause Method
    //=========================================================================
    //=========================================================================
	public function SelectRaw($field)
	{
    	return $this->CSC_AddItemRaw($this->fields, $field);
	}

    //##################################################################################
    //##################################################################################
    //##################################################################################
    // Protected / Internal Methods
    //##################################################################################
    //##################################################################################
    //##################################################################################

    //=========================================================================
    //=========================================================================
    // Format Fields Method
    //=========================================================================
    //=========================================================================
    protected function FormatFields()
    {
        $select = $this->FormatCSC('SELECT', $this->fields);
        if (!$select) {
            $select = 'SELECT *';
        }
        return $select;
    }

}