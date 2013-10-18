<?php

include 'utility.php';

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	DBWrapper
//--------------------------------------------------------------------------------------------------------------------------------------

Class DBWrapper{
	private $db;

	function __construct($host, $name, $passwd, $dbName) {
      $this->db = new mysqli($host, $name, $passwd, $dbName);

      if ($this->db->connect_error){
      	echo "failed to connect: create a new db";
      	return null;
      }
   	}

   	function insert($table, $tuple){
   	  $firstAtt = true;
   	  $query = 'insert into '.$table.' values (';
      foreach ($tuple as $attribute){
        ($firstAtt) ? $firstAtt=false : $query = $query.', ';
        $query = $query.' '.$attribute;
      } 

      return $this->exec($query);
   	}


   	function simpleSelect($table, $selectors){
   	  $firstSelector = true;
   	  $query = 'select ';

      foreach ($selectors as $selector){
        ($firstSelector) ? $firstSelector=false : $query = $query.', ';
        $query = $query.' '.$selector;
      } 

      $query = $query.' from '.$table;
   	  
   	  if(func_num_args() > 2) {
   	  	$query = $query.' where '.func_get_arg(2);
   	  }

      return $this->exec($query);
   	}

   	function dropTable($table){
   	  $query = 'drop table '.$table;
   	  return $this->exec($query);
   	}

   	function createTable($table, $attributes){
   	  $firstSelector = true;
   	  $query= 'create table '.$table.' (';

      foreach ($attributes as $attribute)
      {
        ($firstSelector) ? $firstSelector=false : $query = $query.', ';
        $query = $query.' '.$attribute;
      } 
      $query = $query.')';

	  return $this->exec($query);
   	}

   	function exec($query){
      $result = $this->db->query($query);
      if(!$result){
        println("Invalid query " . $this->db->error);
        return false;
      } else {
      	return $result;
      }
   	}

   	function tableToString($table){
   		$result = $this->simpleSelect($table, ['*']);
   		print_r($result);
   	}

   	function dbToString(){
   	}

}

?>