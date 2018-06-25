<?php

class dbconnector extends core {

    var $link;
    var $theQuery;

	// dbconnector constructor
	function __construct(){
		// Connect to the database
	//	$this->link = mysql_connect(DBHOST, DBUSER, DBPASS) or die(mysql_error());
	//	mysql_select_db(DBNAME, $this->link) or die(mysql_error());
        $this->link = mysqli_connect(DBHOST, DBUSER, DBPASS,DBNAME) or die(mysqli_error($this->link));
        register_shutdown_function(array(&$this, 'close'));
	}

	// Function: query
	// Purpose: Execute a database query
	function query($query) {
		$this->theQuery = $query;
		//return mysql_query($query, $this->link);
        return mysqli_query($this->link,$query);
	}

	// Function: getQuery
	// Purpose: Returns the last database query, for debugging
	function getQuery() {
		return $this->theQuery;
	}

	// Function: getNumRows
	// Purpose: Return row count
	function getNumRows($result){
		return mysqli_num_rows($result);
	}

	// Function: fetchArray
	// Purpose: Get array of query results
	function fetchArray($result) {
		return mysqli_fetch_array($result);
	}
	
	// Function: fetchAssoc
	// Purpose: Get associative array of query results
	function fetchAssoc($result) {
		return mysqli_fetch_assoc($result);
	}
	
	function lastInsertId() {
		return mysqli_insert_id($this->link);
	}

	// Function: close
	// Purpose: Close the connection
	function close() {
		mysqli_close($this->link);
	}
}
?>