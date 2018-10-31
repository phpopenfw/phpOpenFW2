<?php
//**************************************************************************************
//**************************************************************************************
/**
 * SQL Union Trait
 *
 * @package		phpOpenFW
 * @author 		Christian J. Clark
 * @copyright	Copyright (c) Christian J. Clark
 * @license		https://mit-license.org
 **/
//**************************************************************************************
//**************************************************************************************

namespace phpOpenFW\Builders\SQL\Statements\Traits;

//**************************************************************************************
/**
 * SQL Union Trait
 */
//**************************************************************************************
trait Union
{
    //=========================================================================
	// Trait Memebers
    //=========================================================================
	protected $unions = [];

    //=========================================================================
    //=========================================================================
	// Union Clause Method
    //=========================================================================
    //=========================================================================
	public function Union(\phpOpenFW\Builders\SQL\Statements\Select $union)
	{
    	$bind_params = $union->GetBindParams();
    	if ($this->GetDbType() != $union->GetDbType()) {
            throw new \Exception('Unions can only be performed on select statements of the same database type.');
        }
        $this->unions[] = ['union', $union, $bind_params];
    	return $this;
	}

    //=========================================================================
    //=========================================================================
	// Union All Clause Method
    //=========================================================================
    //=========================================================================
	public function UnionAll(\phpOpenFW\Builders\SQL\Statements\Select $union)
	{
    	$bind_params = $union->GetBindParams();
    	if ($this->GetDbType() != $union->GetDbType()) {
            throw new \Exception('Unions can only be performed on select statements of the same database type.');
        }
        $this->unions[] = ['union all', $union, $bind_params];
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
    // Format Unions Method
    //=========================================================================
    //=========================================================================
    protected function FormatUnions()
    {
        $clause = '';
        foreach ($this->unions as $union) {
            $union_query = (string)$union[1];
            if (!empty($union[2])) {
                if ($this->db_type == 'pgsql' || $this->db_type == 'oracle') {
                    $num_bind_params = count($this->bind_params);
                    foreach ($union[2] as $find_bp => $tmp_bp) {
                        if ($this->db_type == 'pgsql') {
                            $find_bp = '$' . $find_bp;
                            $replace_bp = '$' . $num_bind_params;
                        }
                        else if ($this->db_type == 'oracle') {
                            $find_bp = ':' . $find_bp;
                            $replace_bp = ':p' . $num_bind_params;                            
                        }
                        $union_query = str_replace($find_bp, $replace_bp, $union_query);
                        $num_bind_params++;
                    }
                }
                $this->MergeBindParams($union[2]);
            }
            if ($union[0] == 'union all') {
                $clause .= "\nUNION ALL\n\n{$union_query}";
            }
            else {
                $clause .= "\nUNION\n\n{$union_query}";
            }
            
        }
        return $clause;
    }

}
