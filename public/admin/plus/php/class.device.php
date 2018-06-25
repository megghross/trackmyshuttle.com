<?php
	
class device extends core
{
    var $connector;
    var $error;
    
    function __construct() {
       // $this->connector = parent::db();
		$core = new core();
        $this->connector = $core->db();
    }   
		
	function getDevice($org_key,$serial) {		
		$sql = "SELECT * FROM device WHERE org_key='$org_key' AND device_serial='$serial'";	
		$result = $this->connector->query($sql);
		if($this->connector->getNumRows($result)) {			
			while($row = $this->connector->fetchAssoc($result)) {
                $retVal = $row; 
            }
            return $retVal;	
		}
		return false;		
	}
	
}
?>
