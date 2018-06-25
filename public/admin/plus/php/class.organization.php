<?php
	
class organization extends core
{
    var $connector;
    var $error;
    
    function __construct() {
       // $this->connector = parent::db();
		$core = new core();
        $this->connector = $core->db();
    }
  	
  	function getData($org_key){
		$result = $this->connector->query("SELECT * FROM organization WHERE org_key = '".$org_key."'");
		if($this->connector->getNumRows($result)) {
			$row = $this->connector->fetchArray($result);
			return $row;			
		}
		return false;	
	}

}

?>
