<?php
//**************************************************************************************
//**************************************************************************************
/**
 * MySQL Conditions Class
 *
 * @package		phpOpenFW
 * @author 		Christian J. Clark
 * @copyright	Copyright (c) Christian J. Clark
 * @license		https://mit-license.org
 **/
//**************************************************************************************
//**************************************************************************************

namespace phpOpenFW\Builders\SQL\Conditions;

//**************************************************************************************
/**
 * MySQL Class
 */
//**************************************************************************************
class MySQL
{
    use Condition;
    protected static $db_type = 'mysql';
}