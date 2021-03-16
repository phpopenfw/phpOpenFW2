<?php
//**************************************************************************************
//**************************************************************************************
/**
 * SQL Formatting Class
 *
 * @package        phpOpenFW
 * @author         Christian J. Clark
 * @copyright    Copyright (c) Christian J. Clark
 * @license        https://mit-license.org
 **/
//**************************************************************************************
//**************************************************************************************

namespace phpOpenFW\Format;

//**************************************************************************************
/**
 * SQL Formatting Class
 */
//**************************************************************************************
class SQL
{

    //*************************************************************************
    //*************************************************************************
    // Build SQL Select Statement Method
    //*************************************************************************
    //*************************************************************************
    public static function SQLSelectStatement($args)
    {
        //==================================================================
        // Validate Arguments
        //==================================================================
        if (!is_array($args)) {
            trigger_error('Select statement components must be given as an array.');
            return false;
        }
        extract($args);

        //==================================================================
        // Build SQL Statement
        //==================================================================
        $strsql = self::SelectClause($args['select']);
        $strsql .= self::FromClause($args['from']);
        $strsql .= 'WHERE ' . self::Where($args['where']);
        if (isset($args['group_by'])) {
            $strsql .= self::GroupBy($args['group_by']);
        }
        if (isset($args['order_by'])) {
            $strsql .= self::OrderBy($args['order_by']);
        }
        if (isset($args['limit'])) {
            $strsql .= self::Limit($args['limit']);
        }

        //==================================================================
        // Return SQL and Bind Parameters
        //==================================================================
        return ['strsql' => $strsql, 'params' => $params];
    }

    //*************************************************************************
    //*************************************************************************
    // Format Select Clause Method
    //*************************************************************************
    //*************************************************************************
    public static function SelectClause($fields)
    {
        //==================================================================
        // Validate Arguments
        //==================================================================
        if (!is_array($fields)) {
            trigger_error('Fields must be given as an array.');
            return false;
        }

        //==================================================================
        // Return Select Clause
        //==================================================================
        return 'SELECT ' . implode(', ', $fields);
    }

    //*************************************************************************
    //*************************************************************************
    // Format From Clause Method
    //*************************************************************************
    //*************************************************************************
    public static function FromClause($tables)
    {
        //==================================================================
        // Validate Arguments
        //==================================================================
        if (!is_array($tables)) {
            trigger_error('Tables must be given as an array.');
            return false;
        }

        //==================================================================
        // Return From Clause
        //==================================================================
        return ' FROM ' . implode(', ', $tables);
    }

    //*************************************************************************
    //*************************************************************************
    // Format Where Clause Method
    //*************************************************************************
    //*************************************************************************
    public static function Where($fields, &$params=false, $args=false)
    {
        //==================================================================
        // Validate Arguments
        //==================================================================
        if (!is_array($fields)) {
            trigger_error('Fields must be given as an array.');
            return false;
        }

        //==================================================================
        // Defaults
        //==================================================================
        $db_type = 'mysql';
        if (defined('PHPOPENFW_DEFAULT_DB_TYPE')) {
            $db_type = PHPOPENFW_DEFAULT_DB_TYPE;
        }
        if (!is_array($params)) {
            $params = [''];
        }

        //==================================================================
        // Extract Arguments
        //==================================================================
        if (is_array($args)) { extract($args); }
        $db_type = strtolower($db_type);

        //==================================================================
        // Add params
        //==================================================================
        $strsql = '';
        foreach ($fields as $k => $v) {
            $bindingType = 'i';
            $no_bind = false;

            //---------------------------------------------------
            // See if this isn't an integer binding
            //---------------------------------------------------
            if ($db_type == 'mysql') {
                if (strpos($k, ':')) {
                    $parts = explode(':', $k);
                    $bindingType = $parts[1];
                    $k = $parts[0];
                }
            }

            //---------------------------------------------------
            // Determine Operator and Value
            //---------------------------------------------------
            $operator = '=';
            if (is_array($v)) {
                $operator = $v[0];
                $v = $v[1];
                if (!empty($v[2]) && $v[2] == 'no_bind') {
                    $no_bind = true;
                }
            }

            //---------------------------------------------------
            // Append to the query and bind parameters
            //---------------------------------------------------
            if ($strsql) { $strsql .= ' and'; }
            if ($no_bind) {
                $strsql .= "{$k} {$operator} {$v}";
            }
            else {
                $strsql .= "{$k} {$operator} ?";
                if ($db_type == 'mysql') {
                    $params[0] .= $bindingType;
                }
                $params[] = $v;
            }
        }

        //==================================================================
        // Empty Params?
        //==================================================================
        if ($params[0] == '') { $params = []; }

        //==================================================================
        // Return where clause
        //==================================================================
        return $strsql;
    }

    //*************************************************************************
    //*************************************************************************
    // Format Order By Clause Method
    //*************************************************************************
    //*************************************************************************
    public static function OrderBy($order_by)
    {
        if (!$order_by) { return false; }
        if (is_array($order_by)) {
            $order_by = implode(', ', $order_by);
        }
        return ' ORDER BY ' . $order_by;
    }

    //*************************************************************************
    //*************************************************************************
    // Format Group By Clause Method
    //*************************************************************************
    //*************************************************************************
    public static function GroupBy($group_by)
    {
        if (!$group_by) { return false; }
        if (is_array($group_by)) {
            $group_by = implode(', ', $group_by);
        }
        return ' GROUP BY ' . $group_by;
    }

    //*************************************************************************
    //*************************************************************************
    // Format Limit Clause Method
    //*************************************************************************
    //*************************************************************************
    public static function Limit($limit, &$params, $args=false)
    {
        if (!$limit) { return false; }

        //==================================================================
        // Defaults
        //==================================================================
        $db_type = 'mysql';
        if (defined('PHPOPENFW_DEFAULT_DB_TYPE')) {
            $db_type = PHPOPENFW_DEFAULT_DB_TYPE;
        }
        if (!is_array($params)) {
            $params = [''];
        }

        //==================================================================
        // Extract Arguments
        //==================================================================
        if (is_array($args)) { extract($args); }
        $db_type = strtolower($db_type);

        //==================================================================
        // Build Limit Clause
        //==================================================================
        if ($db_type == 'mysql') {
            $params[0] .= 'i';
        }
        $params[] = $limit;

        //==================================================================
        // Return Clause
        //==================================================================
        return $strsql;
    }

}
