<?php
//**************************************************************************************
//**************************************************************************************
/**
 * SQL Auxillary Class
 *
 * @package		phpOpenFW
 * @author 		Christian J. Clark
 * @copyright	Copyright (c) Christian J. Clark
 * @license		https://mit-license.org
 **/
//**************************************************************************************
//**************************************************************************************

namespace phpOpenFW\Builders\SQL;

//**************************************************************************************
/**
 * Aux Class
 */
//**************************************************************************************
class Aux
{
    //=========================================================================
    //=========================================================================
    // DB Type Is Valid?
    //=========================================================================
    //=========================================================================
    public static function DbTypeIsValid(String $db_type)
    {
        if (!in_array($db_type, ['mysql', 'pgsql', 'oracle', 'sqlsrv'])) {
            return false;
        }
        return true;
    }

    //=========================================================================
    //=========================================================================
    // Add a Bind Parameter
    //=========================================================================
    //=========================================================================
    public static function AddBindParam(String $db_type, Array &$params, $value, $type='s')
    {
        //-----------------------------------------------------------------
        // Is Database Type Valid?
        //-----------------------------------------------------------------
        if (!self::DbTypeIsValid($db_type)) {
            throw new \Exception("Invalid database type.");
        }

        //-----------------------------------------------------------------
        // Validate that Value is Scalar
        //-----------------------------------------------------------------
        if (!is_scalar($value)) {
            throw new \Exception("Value must be a scalar value.");
        }

        //-----------------------------------------------------------------
        // Which Class is using this trait?
        //-----------------------------------------------------------------
        // (i.e. How do we add the bind parameter?)
        //-----------------------------------------------------------------
        switch ($db_type) {

            //-----------------------------------------------------------------
            // MySQL
            //-----------------------------------------------------------------
            case 'mysql':
            case 'mysqli':
                if (count($params) == 0) {
                    $params[] = '';
                }
                $params[0] .= $type;
                $params[] = $value;
                return '?';
                break;

            //-----------------------------------------------------------------
            // PgSQL
            //-----------------------------------------------------------------
            case 'pgsql':
                $index = count($params);
                $ph = '$' . $index;
                if (isset($params[$index])) {
                    throw new \Exception("An error occurred trying to add the PostgreSQL bind parameter. Parameter index already in use.");
                }
                $params[$index] = $value;
                return $ph;
                break;

            //-----------------------------------------------------------------
            // Oracle
            //-----------------------------------------------------------------
            case 'oracle':
                $index = count($params);
                $ph = 'p' . $index;
                if (isset($params[$ph])) {
                    throw new \Exception("An error occurred trying to add the Oracle bind parameter. Parameter index already in use.");
                }
                $params[$ph] = $value;
                return ':' . $ph;
                break;

            //-----------------------------------------------------------------
            // Default
            //-----------------------------------------------------------------
            default:
                $params[] = $value;
                return '?';
                break;

        }
    }

    //=========================================================================
    //=========================================================================
    // Add a Bind Parameters
    //=========================================================================
    //=========================================================================
    public static function AddBindParams(String $db_type, Array &$params, Array $values, $type='s')
    {
        $place_holders = '';
        foreach ($values as $value) {
            $tmp_ph = self::AddBindParam($db_type, $params, $value, $type);
            $place_holders .= ($place_holders) ? (', ' . $tmp_ph) : ($tmp_ph);
        }
        return $place_holders;
    }
}