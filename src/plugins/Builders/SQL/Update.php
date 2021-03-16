<?php
//**************************************************************************************
//**************************************************************************************
/**
 * SQL Update Statement Class
 *
 * @package        phpOpenFW
 * @author         Christian J. Clark
 * @copyright    Copyright (c) Christian J. Clark
 * @license        https://mit-license.org
 **/
//**************************************************************************************
//**************************************************************************************

namespace phpOpenFW\Builders\SQL;

//**************************************************************************************
/**
 * SQL Update Class
 */
//**************************************************************************************
class Update extends Statement
{
    //=========================================================================
    // Traits
    //=========================================================================
    use Traits\Where;
    use Traits\Values;

    //=========================================================================
    // Class Memebers
    //=========================================================================
    protected $sql_type = 'update';

    //=========================================================================
    //=========================================================================
    // Get SQL Method
    //=========================================================================
    //=========================================================================
    public function GetSQL()
    {
        //----------------------------------------------------------------
        // Get Formatted Where Clause
        //----------------------------------------------------------------
        // Require a where clause to prevent updating all table rows
        //----------------------------------------------------------------
        $where = $this->FormatWhere();
        if (!$where) {
            trigger_error("SQL Update statement must have at least one qualifying condition.");
            return '';
        }

        //----------------------------------------------------------------
        // Format Values
        //----------------------------------------------------------------
        $set = $this->FormatValues();

        //-------------------------------------------------------
        // Are there values?
        //-------------------------------------------------------
        if (empty($set)) {
            trigger_error("No update values could be found.");
            return '';
        }

        //----------------------------------------------------------------
        // Start SQL Update Statement
        //----------------------------------------------------------------
        return "UPDATE {$this->table} SET {$set} \n{$where}";
    }

}
