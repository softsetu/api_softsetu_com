<?php



/**
 * 
 */
class CityClass extends Database
{
	public function master_city($to, $from)
    {
        try {
            $query = $this->_agentPDO->prepare("SELECT * FROM master_city WHERE status = '1' AND id BETWEEN ".$to." AND ".$from);
            // SELECT * FROM ".$table." WHERE ".$condition." = '".$where."' ORDER BY ".$id." ".$type." LIMIT ".$limit
            // $query->bindParam(':where', $where);
            $query_result = $query->execute() or die($this->_agentPDO->error);
            $row = $query->fetchAll(PDO::FETCH_ASSOC);

            return $row;
        
           return $row;
        } catch (Exception $e) {
           return $e->getMessage(); 
        }
       
    }

    public function Upd(array $array)
    {
        $sql = '';
        foreach ($array as $key) {
            $sql .= "id = '".$key."' OR ";
        }
        $sql = substr($sql, 0, -3);
        try {
            $query = $this->_agentPDO->prepare("UPDATE master_city SET status = '0' WHERE ".$sql);
            $query->execute() or die($this->_agentPDO->error);

            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public function FetchGroupBy()
    {
        $query = $this->_agentPDO->prepare("SELECT * FROM master_city GROUP BY state");
        $query->execute() or die($this->_agentPDO->error);
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    public function insT($state)
    {
        $query = $this->_agentPDO->prepare("INSERT INTO master_state (state) VALUES ('$state')");
        $query->execute() or die($this->_agentPDO->error);
        
    }

}



?>