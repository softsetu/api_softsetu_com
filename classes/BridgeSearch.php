<?php 
/**
 * 
 */
class BridgeSearch extends Database {
	
	public function SearchBridgeHotel($table, $keyword, $order_by) {
        // $query = $this->_agentPDO->prepare("SELECT * FROM master_dist ORDER BY dist ASC ");
        $query = $this->_hotelPDO->prepare("SELECT * FROM ".$table." WHERE name_hotel_asper_gst LIKE '%".$keyword."%' OR office LIKE '%".$keyword."%' OR road LIKE '%".$keyword."%' OR landmark LIKE '%".$keyword."%' OR village LIKE '%".$keyword."%' OR city LIKE '%".$keyword."%' ORDER BY ".$order_by." ASC LIMIT 50");
        $query->execute() or die($this->_hotelPDO->error);
        $count = $query->rowCount();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        $array  = array("count" => $count, "rows" => $rows);
        return $array;
    }

    public function SearchBridgeCity($table, $where, $keyword, $order_by) {
        // $query = $this->_agentPDO->prepare("SELECT * FROM master_dist ORDER BY dist ASC ");
        $query = $this->_hotelPDO->prepare("SELECT * FROM ".$table." WHERE ".$where." LIKE '%".$keyword."%' ORDER BY ".$order_by." ASC LIMIT 50");
        $query->execute() or die($this->_hotelPDO->error);
        // $count = $query->rowCount();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function SearchBridgeState($table, $where, $keyword, $order_by) {
        // $query = $this->_agentPDO->prepare("SELECT * FROM master_dist ORDER BY dist ASC ");
        $query = $this->_hotelPDO->prepare("SELECT * FROM ".$table." WHERE ".$where." LIKE '%".$keyword."%' GROUP BY (state) ORDER BY ".$order_by." ASC");
        $query->execute() or die($this->_hotelPDO->error);
        // $count = $query->rowCount();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

     public function SearchBridgeCountry($table, $where, $keyword, $order_by) {
        // $query = $this->_agentPDO->prepare("SELECT * FROM master_dist ORDER BY dist ASC ");
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$where." LIKE '%".$keyword."%' ORDER BY ".$order_by." ASC");
        $query->execute() or die($this->_agentPDO->error);
        // $count = $query->rowCount();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function SearchBridgeByaddress($table, $where, $keyword, $order_by) {
       
       $query = $this->_hotelPDO->prepare("SELECT * FROM ".$table." WHERE (".$where." LIKE '%".$keyword."%') GROUP BY ".$order_by." ORDER BY ".$order_by." ASC");
        
        $query->execute() or die($this->_hotelPDO->error);
        // $count = $query->rowCount();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function like_match($pattern, $subject) {
        $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
        return (bool) preg_match("/^{$pattern}$/i", $subject);
    }


// SELECT * FROM master_hotel INNER JOIN master_hotel_price ON master_hotel.customer_id = master_hotel_price.customer_id WHERE (master_hotel.city LIKE '%Ever%' OR master_hotel.city_name LIKE '%Ever%' OR master_hotel.office LIKE '%Ever%' OR master_hotel.road LIKE '%Ever%' OR master_hotel.landmark LIKE '%Ever%' OR master_hotel.name_hotel_asper_gst LIKE '%Ever%') AND master_hotel.govt_rating = '".$rating."' AND (master_hotel_price.rack_epi BETWEEN '700' AND '2000' OR master_hotel_price.rack_cpi BETWEEN '700' AND '2000' OR master_hotel_price.rack_mapi BETWEEN '700' AND '2000' OR master_hotel_price.rack_apai BETWEEN '700' AND '2000' OR master_hotel_price.btb_epi BETWEEN '700' AND '2000' OR master_hotel_price.btb_cpi BETWEEN '700' AND '2000' OR master_hotel_price.btb_mapi BETWEEN '700' AND '2000' OR master_hotel_price.btb_apai BETWEEN '700' AND '2000' OR master_hotel_price.sea_epi BETWEEN '700' AND '2000' OR master_hotel_price.sea_cpi BETWEEN '700' AND '2000' OR master_hotel_price.sea_mapi BETWEEN '700' AND '2000' OR master_hotel_price.sea_apai BETWEEN '700' AND '2000' OR master_hotel_price.prom_epi BETWEEN '700' AND '2000' OR master_hotel_price.prom_cpi BETWEEN '700' AND '2000' OR master_hotel_price.prom_mapi BETWEEN '700' AND '2000' OR master_hotel_price.prom_apai BETWEEN '700' AND '2000'



    public function SearchIntoAll($keyword, $rating, $qu, $price_to, $price_from) {


    	// $queryCity = $this->_hotelPDO->prepare("SELECT * FROM master_dist WHERE id = '$id'");
    	// $queryCity->execute() or die($this->_hotelPDO->error);
    	// $row = $queryCity->fetch(PDO::FETCH_ASSOC);
        if ($rating != "") {
            $query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel INNER JOIN master_hotel_price ON master_hotel.customer_id = master_hotel_price.customer_id WHERE (".$qu.") AND (master_hotel.city LIKE '%$keyword%' OR master_hotel.city_name LIKE '%".$keyword."%' OR master_hotel.office LIKE '%".$keyword."%' OR master_hotel.road LIKE '%".$keyword."%' OR master_hotel.landmark LIKE '%".$keyword."%' OR master_hotel.name_hotel_asper_gst LIKE '%".$keyword."%') AND (master_hotel_price.rack_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.rack_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.rack_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.rack_apai BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_apai BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_end_date BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_apai BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_end_date BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_apai BETWEEN '".$price_to."' AND '".$price_from."') AND master_hotel.govt_rating = '".$rating."' GROUP BY master_hotel.name_hotel_asper_gst");
        }
        else if($qu != "") {
            $query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel INNER JOIN master_hotel_price ON master_hotel.customer_id = master_hotel_price.customer_id WHERE (".$qu.") AND (master_hotel.city LIKE '%$keyword%' OR master_hotel.city_name LIKE '%".$keyword."%' OR master_hotel.office LIKE '%".$keyword."%' OR master_hotel.road LIKE '%".$keyword."%' OR master_hotel.landmark LIKE '%".$keyword."%' OR master_hotel.name_hotel_asper_gst LIKE '%".$keyword."%') AND (master_hotel_price.rack_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.rack_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.rack_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.rack_apai BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_apai BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_end_date BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_apai BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_end_date BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_apai BETWEEN '".$price_to."' AND '".$price_from."') GROUP BY master_hotel.name_hotel_asper_gst");
        }
        else {
            $query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel INNER JOIN master_hotel_price ON master_hotel.customer_id = master_hotel_price.customer_id WHERE (master_hotel.city LIKE '%$keyword%' OR master_hotel.city_name LIKE '%".$keyword."%' OR master_hotel.office LIKE '%".$keyword."%' OR master_hotel.road LIKE '%".$keyword."%' OR master_hotel.landmark LIKE '%".$keyword."%' OR master_hotel.name_hotel_asper_gst LIKE '%".$keyword."%') AND (master_hotel_price.rack_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.rack_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.rack_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.rack_apai BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_apai BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_end_date BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_apai BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_end_date BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_apai BETWEEN '".$price_to."' AND '".$price_from."') GROUP BY master_hotel.name_hotel_asper_gst");    
        }
		
    	// print_r($query);
    	$query->execute() or die($this->_hotelPDO->error);
    	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
    	$count = $query->rowCount();
    	return array("rows" => $rows, "count" => $count);
    }

    public function HotelWiseSearchResults($id, $qu) {

    	$queryHotel = $this->_hotelPDO->prepare("SELECT * FROM master_hotel WHERE id = '$id'");
    	$queryHotel->execute() or die($this->_hotelPDO->error);
    	$row = $queryHotel->fetch(PDO::FETCH_ASSOC);


		$query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel WHERE (".$qu.") AND city LIKE '%$row[city_name]%' OR city_name LIKE '%$row[city_name]%'");
    	$query->bindParam(":id", $id);
    	$query->execute() or die($this->_hotelPDO->error);
    	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
    	$count = $query->rowCount();
    	
    	return array('hotel' => $row, 'HotelRows' => $rows, 'count' => $count);
    }

    //=========== Get Minimum Price ================//
    public function GetMinimumPrice($customer_id, $value1, $value2) {

    	$query = $this->_hotelPDO->prepare("SELECT *  FROM `master_hotel_price` WHERE `customer_id` = '$customer_id' AND ((`rack_epi`  BETWEEN '$value1' AND '$value2') OR (`rack_cpi`  BETWEEN '$value1' AND '$value2') OR (`rack_mapi`  BETWEEN '$value1' AND '$value2') OR (`rack_apai`  BETWEEN '$value1' AND '$value2') OR (`btb_epi`  BETWEEN '$value1' AND '$value2') OR (`btb_cpi`  BETWEEN '$value1' AND '$value2') OR (`btb_mapi`  BETWEEN '$value1' AND '$value2') OR (`sea_epi`  BETWEEN '$value1' AND '$value2') OR (`sea_cpi`  BETWEEN '$value1' AND '$value2') OR (`sea_mapi`  BETWEEN '$value1' AND '$value2') OR (`sea_apai`  BETWEEN '$value1' AND '$value2') OR (`prom_epi`  BETWEEN '$value1' AND '$value2') OR (`prom_cpi`  BETWEEN '$value1' AND '$value2') OR (`prom_mapi`  BETWEEN '$value1' AND '$value2') OR (`prom_apai`  BETWEEN '$value1' AND '$value2'))");
    	$query->execute() or die($this->_hotelPDO->error);
    	$rows = $query->fetchAll(PDO::FETCH_ASSOC);

    	return $rows;
    }

    //========== Fetch Room Type and Meal plan ================//
    public function FetchRoomType($values, $customer_id) {
    	$query = $this->_hotelPDO->prepare("SELECT * FROM `master_hotel_price` WHERE customer_id = '$customer_id' AND (`rack_epi` = '$values' OR `rack_cpi` = '$values' OR `rack_mapi` = '$values' OR `rack_apai` = '$values' OR `btb_epi` = '$values' OR `btb_cpi` = '$values' OR `btb_mapi` = '$values' OR `sea_epi` = '$values' OR `sea_cpi` = '$values' OR `sea_mapi` = '$values' OR `sea_apai` = '$values' OR `prom_epi` = '$values' OR `prom_cpi` = '$values' OR `prom_mapi` = '$values' OR `prom_apai` = '$values')");
    	$query->execute() or die($this->_hotelPDO->error);
    	$row = $query->fetch(PDO::FETCH_ASSOC);


    	foreach ($row as $key => $value) {
    		if ($value == $values) {
    			$data = array("plan" => $this->MealPlan($key));
    			break;
    		}
    	}

    	return array("data" => $data, "type" => $row['room_type']);

    }

    //========== Get Meal Plan Prefix ==============//
    public function MealPlan($plan_name) {
    	switch ($plan_name) {
    		case 'rack_epi':
    			$plan = 'EPAI';
    		break;
	    	case 'rack_cpi':
	    			$plan = 'CPAI';
	    		break;
	    	case 'rack_mapi':
	    			$plan = 'MAPAI';
	    		break;
	    	case 'rack_apai':
	    			$plan = 'APAI';
	    		break;
	    	case 'btb_epi':
	    			$plan = 'EPAI';
	    		break;
	    	case 'btb_cpi':
	    			$plan = 'CPAI';
	    		break;
	    	case 'btb_mapi':
	    			$plan = 'MAPAI';
	    		break;
	    	case 'btb_apai':
	    			$plan = 'APAI';
	    		break;
	    	case 'sea_epi':
	    			$plan = 'EPAI';
	    		break;
	    	case 'sea_cpi':
	    			$plan = 'CPAI';
	    		break;
	    	case 'sea_mapi':
	    			$plan = 'MAPAI';
	    		break;
	    	case 'sea_apai':
	    			$plan = 'APAI';
	    		break;
	    	case 'prom_epi':
	    			$plan = 'EPAI';
	    		break;
	    	case 'prom_cpi':
	    			$plan = 'CPAI';
	    		break;
	    	case 'prom_mapi':
	    			$plan = 'MAPAI';
	    		break;
	    	case 'prom_apai':
	    			$plan = 'APAI';
	    		break;
    	}

    	return $plan;
    	
    }
	

    public function SaveSearch($search, $type, $id, $created_by) {
        
        try {
            $query = $this->_agentPDO->prepare("INSERT INTO search_history (search, type, type_id, created_by) VALUES (:search, :type, :type_id, :created_by)");
            $query->bindParam(":search", $search);
            $query->bindParam(":type", $type);
            $query->bindParam(":type_id", $id);
            $query->bindParam(":created_by", $created_by);
            $query->execute() or die($this->_agentPDO->error);
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public function FetchSimilerSearch($company, $price_to, $price_from) {
        $query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel WHERE name_hotel_asper_gst = '$company'");
        $query->execute() or die($this->_hotelPDO->error);
        $city = $query->fetch(PDO::FETCH_ASSOC);
        $name = $city['city'];

        try {
            $query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel INNER JOIN master_hotel_price ON master_hotel.customer_id = master_hotel_price.customer_id WHERE (master_hotel.city LIKE '%".$name."%' OR master_hotel.city_name LIKE '%".$name."%' OR master_hotel.office LIKE '%".$name."%' OR master_hotel.road LIKE '%".$name."%' OR master_hotel.landmark LIKE '%".$name."%' OR master_hotel.name_hotel_asper_gst LIKE '%".$name."%') AND (master_hotel_price.rack_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.rack_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.rack_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.rack_apai BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_apai BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.btb_end_date BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_apai BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.sea_end_date BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_epi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_cpi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_mapi BETWEEN '".$price_to."' AND '".$price_from."' OR master_hotel_price.prom_apai BETWEEN '".$price_to."' AND '".$price_from."') AND master_hotel.name_hotel_asper_gst != '$company' GROUP BY master_hotel.name_hotel_asper_gst");
            $query->execute() or die($this->_hotelPDO->error);
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            return $rows;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

     //============= Agent Search Function ==============//
    public function SearchAgentComp($table, $keyword, $order_by) {
        // $query = $this->_agentPDO->prepare("SELECT * FROM master_dist ORDER BY dist ASC ");
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE comp_name LIKE '%".$keyword."%' OR city LIKE '%".$keyword."%' OR address LIKE '%".$keyword."%' ORDER BY ".$order_by." ");
        $query->execute() or die($this->_agentPDO->error);
        $count = $query->rowCount();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        $array  = array("count" => $count, "rows" => $rows);
        return $array;
    }

    public function SearchAgentCity($table, $where, $keyword, $order_by) {
        // $query = $this->_agentPDO->prepare("SELECT * FROM master_dist ORDER BY dist ASC ");
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$where." LIKE '%".$keyword."%' ORDER BY ".$order_by." ");
        $query->execute() or die($this->_agentPDO->error);
        // $count = $query->rowCount();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
     public function SearchAgentState($table, $where, $keyword, $order_by) {
        // $query = $this->_agentPDO->prepare("SELECT * FROM master_dist ORDER BY dist ASC ");
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$where." LIKE '%".$keyword."%' GROUP BY (state) ORDER BY ".$order_by." ASC");
        $query->execute() or die($this->_agentPDO->error);
        // $count = $query->rowCount();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    
    public function SearchAgentData($table, $keyword,$GSTData, $BizIDs,$order_by) {
    // $query = $this->_agentPDO->prepare("SELECT * FROM master_dist ORDER BY dist ASC ");
      //echo $BizIDs;
        if($BizIDs == '0' OR $BizIDs ==''  )
       {
        // echo "hello";
        // echo $BizIDs;
   
         if($GSTData =="Without GST")
         {
            $query = $this->_agentPDO->prepare("SELECT * FROM ".$table."  WHERE gst = '' AND (city LIKE '%".$keyword."%' OR address LIKE '%".$keyword."%')  ORDER BY ".$order_by." ASC LIMIT 50");
            $query->execute() or die($this->_agentPDO->error);
            $count = $query->rowCount();
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            $array  = array("count" => $count, "rows" => $rows);
            return $array;
        }
         elseif($GSTData == 'With GST')
        {
             $query = $this->_agentPDO->prepare("SELECT * FROM ".$table."  WHERE gst != '' AND (city LIKE '%".$keyword."%' OR address LIKE '%".$keyword."%')  ORDER BY ".$order_by." ASC LIMIT 50");
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $array  = array("count" => $count, "rows" => $rows);
                return $array;
        }
         elseif($GSTData == 'All')
        {
                $query = $this->_agentPDO->prepare("SELECT * FROM ".$table."  WHERE city LIKE '%".$keyword."%' OR address LIKE '%".$keyword."%'  ORDER BY ".$order_by." ASC LIMIT 50");
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $array  = array("count" => $count, "rows" => $rows);
                return $array;

        }
        //return 1;
    }
    else{
            //echo "<br>else....".$BizIDs;
            $str='';
            $c=preg_replace("/[1]/", ",", trim($BizIDs));
            $c1=rtrim($c,",");
            $strings = explode(",",$c1);                            
            $str .= "'".implode("' AND master_biz_type.abbr = '",$strings)."'";
            $str = 'master_biz_type.abbr = '.$str;
            if($GSTData =="Without GST") {

                $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." 
                    INNER JOIN agents_biz_type ON  master_agents.customer_id = agents_biz_type.customer_id  
                    INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE ".$str." AND (master_agents.city LIKE '%".$keyword."%' OR master_agents.address LIKE '%".$keyword."%') AND master_agents.gst = '' GROUP BY master_agents.comp_name");         
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $array  = array("count" => $count, "rows" => $rows);
                return $array;
               // print_r($query);
            }
             elseif($GSTData == 'With GST')
             {
                $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." 
                    INNER JOIN agents_biz_type ON  master_agents.customer_id = agents_biz_type.customer_id  
                    INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE ".$str." AND (master_agents.city LIKE '%".$keyword."%' OR master_agents.address LIKE '%".$keyword."%') AND master_agents.gst != '' GROUP BY master_agents.comp_name");         
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $array  = array("count" => $count, "rows" => $rows);
                return $array;
             }
            elseif($GSTData == 'All')
            {
                $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." 
                    INNER JOIN agents_biz_type ON  master_agents.customer_id = agents_biz_type.customer_id  
                    INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE ".$str." AND (master_agents.city LIKE '%".$keyword."%' OR master_agents.address LIKE '%".$keyword."%') GROUP BY master_agents.comp_name");         
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $array  = array("count" => $count, "rows" => $rows);
                return $array;
            }
        }
    }


//==================Fetch ALL DMC Agents===============//
    public function SearchDMCAgents($keyword, $DMCfor)
    { 
        if($DMCfor == "India")
        {
            $query = $this->_agentPDO->prepare("SELECT * FROM master_agents AS agents
            INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
            INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE 
            master_biz_type_id IN (30,31) AND agents.city LIKE '%".$keyword."%' OR agents.address LIKE '%".$keyword."%' GROUP BY agents.comp_name");
            $query->execute() or die($this->_agentPDO->error);
            $count = $query->rowCount();
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            $array  = array("count" => $count, "rows" => $rows);
            return $array;
           // print_r($array); 
        }
        elseif($DMCfor == 'International')
        {
             $query = $this->_agentPDO->prepare("SELECT * FROM master_agents AS agents INNER JOIN master_premiumservice_que_ans ON agents.customer_id = master_premiumservice_que_ans.customer_id WHERE master_premiumservice_que_ans.master_que_id = 15 AND master_premiumservice_que_ans.answer LIKE '%".$keyword."%' GROUP BY agents.comp_name ");
            $query->execute() or die($this->_agentPDO->error);
            $count = $query->rowCount();
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            $array1  = array("count" => $count, "rows" => $rows);
            return $array1;
        }
        
    } 
//==================Fetch ALL DMC Primum Answer Agetns ==============//
public function SelectDMCLink($table, $customer_id, $keyword){


        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE customer_id = '$customer_id' AND master_que_id IN (14,15) ");
        $query->execute() or die($this->_agentPDO->error);
        $count = $query->rowCount();
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        // print_r($row);
        return $row;
}
//==================Fetch ALL OAT Agetns ==============//

    public function SearchOTA_Agents($table, $keyword,$StateName){

        if($StateName == 'All')
        {
            if($keyword == '0')
            {
                $query = $this->_agentPDO->prepare("SELECT * FROM master_agents AS agents
                INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE master_biz_type_id IN (26,27) GROUP BY agents.comp_name ");
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $array  = array("count" => $count, "rows" => $rows);
               return $array;    
            }
            elseif ($keyword == '1') {
                $query = $this->_agentPDO->prepare("SELECT * FROM master_agents AS agents
                INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE master_biz_type_id = 26 GROUP BY agents.comp_name ");
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $array  = array("count" => $count, "rows" => $rows);
               return $array;  
            }
            elseif($keyword == '2') {
                $query = $this->_agentPDO->prepare("SELECT * FROM master_agents AS agents
                INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE master_biz_type_id = 27 GROUP BY agents.comp_name ");
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $array  = array("count" => $count, "rows" => $rows);
               return $array;
            }      
        }
        else
        {
            $str='';
            $c=preg_replace("/[1]/", ",", trim($StateName));
            $c1=rtrim($c,",");
            $strings = explode(",",$c1);                            
            $str .= implode("%' OR city LIKE '%",$strings);
            $str = "city LIKE '%".$str."%'";

            // echo $str;
             if($keyword == '0')
            {
                $query = $this->_agentPDO->prepare("SELECT * FROM master_agents AS agents
                INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id 
                WHERE master_biz_type_id IN (26,27) AND (".$str.") GROUP BY agents.comp_name ");
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $array  = array("count" => $count, "rows" => $rows);
                return $array;  
            }
            elseif ($keyword == '1') {
                $query = $this->_agentPDO->prepare("SELECT * FROM master_agents AS agents
                INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE master_biz_type_id = 26 AND (".$str." ) GROUP BY agents.comp_name ");
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $array  = array("count" => $count, "rows" => $rows);
                return $array;  
            }
            elseif($keyword == '2') {
                $query = $this->_agentPDO->prepare("SELECT * FROM master_agents AS agents
                INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE master_biz_type_id = 27 AND (".$str.") GROUP BY agents.comp_name ");
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $array  = array("count" => $count, "rows" => $rows);
                return $array;
            }      
        }
    }   


//==================Fetch ALL OAT Agetns ==============//
public function SelectOTALink($table, $customer_id, $keyword){

    if($keyword == '0'){
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE customer_id = '$customer_id' AND master_que_id IN (19,18) ");
        $query->execute() or die($this->_agentPDO->error);
        $count = $query->rowCount();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        // print_r($row);
        return $row;
    }
    elseif($keyword == '1'){
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE customer_id = '$customer_id' AND master_que_id = 18 ");
        $query->execute() or die($this->_agentPDO->error);
        $count = $query->rowCount();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    elseif($keyword == '2'){
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE customer_id = '$customer_id' AND master_que_id = 19 ");
        $query->execute() or die($this->_agentPDO->error);
        $count = $query->rowCount();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
}

//==================Fetch ALL Transport Agents===============//

    public function SearchTransportAgents($table, $keyword, $BizID , $DistName){
        
        if($DistName == '0')
        {
            if($BizID == "All")
            {
                $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." AS agents
                    INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                    INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE master_biz_type_id IN (54,55) AND agents.city LIKE '%".$keyword."%' GROUP BY agents.comp_name ");
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                // print_r($rows);
                $array  = array("count" => $count, "rows" => $rows);
               return $array;   
            }
            else{
                $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." AS agents
                    INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                    INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE master_biz_type_id IN (".$BizID.") AND agents.city LIKE '%".$keyword."%' GROUP BY agents.comp_name");
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                // print_r($rows);
                $array  = array("count" => $count, "rows" => $rows);
               return $array;   
            }
        }
        else
        {
            
            $str='';
            $c=preg_replace("/[1]/", ",", trim($DistName));
            $c1=rtrim($c,",");
            $strings = explode(",",$c1);                            
            $str .="'".implode("','",$strings)."'";


            if($BizID == "All")
            {
                $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." AS agents
                    INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                    INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE master_biz_type_id IN (54,55) AND agents.city_name IN (".$str.") GROUP BY agents.comp_name");
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                // print_r($rows);
                $array  = array("count" => $count, "rows" => $rows);
               return $array;   
            }
            else{
                $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." AS agents
                    INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                    INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE master_biz_type_id IN (".$BizID.") AND agents.city_name IN (".$str.") GROUP BY agents.comp_name");
                $query->execute() or die($this->_agentPDO->error);
                $count = $query->rowCount();
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                // print_r($rows);
                $array  = array("count" => $count, "rows" => $rows);
               return $array;   

            return $str;
            }
        }
    } 

// =============Fetch Similer Distict For Transport Agents===================//

public function SimilerTransportAgents($table, $keyword, $BizID)
{    
    $query = $this->_agentPDO->prepare("SELECT * FROM master_dist WHERE dist = '".$keyword."'");
        $query->execute() or die($this->_agentPDO->error);
        $count = $query->rowCount();
        $rows = $query->fetch(PDO::FETCH_ASSOC);

        if($count > 0 )
        {
            $lan = $rows['latitude'];
            $long = $rows['longitude'];

            $query = $this->_agentPDO->prepare("SELECT * FROM (SELECT *,(((acos(sin(( ".$lan." * pi() / 180))
                            * sin(( `latitude` * pi() / 180)) + cos(( ".$lan." * pi() /180 ))
                            * cos(( `latitude`  * pi() / 180)) * cos(((".$long." - `longitude`) * pi()/180)))
                            ) * 180/pi() ) * 60 * 1.1515 * 1.609344) as distance FROM `master_dist`) city WHERE distance <=150 LIMIT 15");
            $query->execute() or die($this->_agentPDO->error);
            $count = $query->rowCount();
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            $city ='';
            foreach ($rows as $key ) {
                $city .= $key['dist'] ."','";
            }
            $str = "'".$city;
            $c1=rtrim($str,"',");
            $str_city = $c1."'"; 

            if($BizID == "All"){
                $query_result = $this->_agentPDO->prepare("SELECT * FROM ".$table." AS agents
                INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE master_biz_type_id IN (54,55) AND agents.city_name IN (".$str_city.") AND agents.city_name NOT IN ('".$keyword."') GROUP BY agents.comp_name");
            $query_result->execute() or die($this->_agentPDO->error);
            $count_result = $query_result->rowCount();
            $rows_result = $query_result->fetchAll(PDO::FETCH_ASSOC);
            $array  = array("count" => $count_result, "rows" => $rows_result);
            return $array; 
            }
            else{
                $query_result = $this->_agentPDO->prepare("SELECT * FROM ".$table." AS agents
                INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE master_biz_type_id IN (".$BizID.") AND agents.city_name IN (".$str_city.") AND agents.city_name NOT IN ('".$keyword."') GROUP BY agents.comp_name");
                $query_result->execute() or die($this->_agentPDO->error);
                $count_result = $query_result->rowCount();
                $rows_result = $query_result->fetchAll(PDO::FETCH_ASSOC);
                $array  = array("count" => $count_result, "rows" => $rows_result);
                return $array; 
            } 
        }
        else {
            return array("count" => 0);
        }
    }

//==================Fetch Vehicle total of Agents===============//

    public function TotalVehicle($table, $customer_id, $type_id){
        
        $query = $this->_agentPDO->prepare("SELECT SUM(`number_of_vehicle`) AS NUM FROM ".$table."
            INner join master_vehicle_subcategory ON ".$table.".master_type_subcat_id=master_vehicle_subcategory.id 
            WHERE master_vehicle_subcategory.master_vehicle_category_id = ".$type_id." AND ".$table.".customer_id = '".$customer_id."'");
        $query->execute() or die($this->_agentPDO->error);
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row;     
    }
//==============Fetch All Distict For transport Agents=================//
    public function SelectAllDist($keyword)
    {
        if($keyword != '')
        {
            $query = $this->_agentPDO->prepare("SELECT state FROM master_dist WHERE dist = '".$keyword."' OR state = '".$keyword."'");
            $query->execute() or die($this->_agentPDO->error);
            $row = $query->fetch(PDO::FETCH_ASSOC);

            $query = $this->_agentPDO->prepare("SELECT * FROM master_dist WHERE state = '".$row['state']."'");
            $query->execute() or die($this->_agentPDO->error);
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            return $rows;
        }
    }
///==================Fetch ALL B2B Visa Agents===============//
    public function SearchVisaAgents($keyword){
        
        $query = $this->_agentPDO->prepare("SELECT * FROM master_agents AS agents
                INNER JOIN agents_biz_type ON agents.customer_id = agents_biz_type.customer_id 
                INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id WHERE master_biz_type_id = 56 AND agents.city LIKE '%".$keyword."%' OR agents.address LIKE '%".$keyword."%'  GROUP BY agents.comp_name");
        $query->execute() or die($this->_agentPDO->error);
        $count = $query->rowCount();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        $array  = array("count" => $count, "rows" => $rows);
       return $array;     
    }
//==================Fetch ALL B2B Visa Primum Answer Agetns ==============//
    public function SelectVisaLink($table, $customer_id, $keyword){


            $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE customer_id = '$customer_id' AND master_que_id IN (65) ");
            $query->execute() or die($this->_agentPDO->error);
            $count = $query->rowCount();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            // print_r($row);
            return $row;
    } 
}

?>