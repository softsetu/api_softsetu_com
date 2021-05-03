<?php


/**
 * 
 */
class HotelDatabase extends Database {
	
	public function FetchAllRecord($table) {

		$query = $this->_hotelPDO->prepare("SELECT * FROM ".$table);
		$query->execute() or die($this->conObject->error);
		$rows = $query->fetchAll();

		return $rows;
	}

	public function FetchAllRecordById($table, $where, $condition) {

		$query = $this->_hotelPDO->prepare("SELECT * FROM ".$table." WHERE ".$where." = '".$condition."'");
        // $query->bindParam(':where', $where);
        $query_result = $query->execute() or die($this->_hotelPDO->error);
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
	}

	public function selectSingelRow($table, $condition, $where)
    {
        $query = $this->_hotelPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = :where");
        $query->bindParam(':where', $where);
        $query_result = $query->execute() or die($this->_hotelPDO->error);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        //Activity Log
		//act = $this->activity($query);

        
        return $row;
       
    }

}





?>