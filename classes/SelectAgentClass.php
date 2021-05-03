<?php

/**
 * 
 */
class SelectAgentClass extends Database
{
	private $_Mobile,
			$_Email,
			$_agent_id;

	public function FetchListAllAgents($customer_id) {
		
		try {

			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE ref_id = '$customer_id' ORDER BY comp_name ASC");
			$query_result = $query->execute() or die($this->_agentPDO->error);
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			throw $e;
		}

		return $rows;

	}

	public function SelectSingelRow($table, $condition, $where) {
		try {

			$query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = '".$where."'");
			$query_result = $query->execute() or die($this->_agentPDO->error);
			$row = $query->fetch(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			throw $e;
		}
		return $row;
	}

	public function FetchListAllAgentsByKeyword()	{

		try {

			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents ORDER BY comp_name ASC");
			$query_result = $query->execute() or die($this->_agentPDO->error);
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			throw $e;
		}
		return $rows;

	}

}





?>