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
    	$fields = explode(',', $field);
        foreach ($fields as $tmp_field) {
            $tmp_field = trim($tmp_field);
            if ($tmp_field) {
            	$this->AddItem($this->fields, $tmp_field);
            }
        }
        return $this;
	}

    //=========================================================================
    //=========================================================================
	// Raw Select Clause Method
    //=========================================================================
    //=========================================================================
	public function SelectRaw($field)
	{
    	if ($field && is_scalar($field)) {
            $this->AddItem($this->fields, $field);
        }
        return $this;
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
        if ($this->fields) {
    		return "SELECT \n  " . implode(",\n  ", $this->fields);
        }
        else {
            return 'SELECT *';
        }
    }

}
