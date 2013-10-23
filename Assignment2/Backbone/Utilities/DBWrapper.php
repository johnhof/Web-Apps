<?php

include_once 'Helpers.php';

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

//-------- INSERTS ----------------------------------------------------------------------------------------------------------------------

   	function insert($table, $tuple){
   	  $firstAtt = true;
   	  $query = 'insert into '.$table.' values(';
      foreach ($tuple as $attribute){
        ($firstAtt) ? $firstAtt=false : $query = $query.', ';
        $query = $query.' '.$attribute;
      } 

      $query = $query.')';

      echo '</br>'.$query.'</br>';
      
      return $this->exec($query);
   	}

    function insertPairs($table, $tuple){
      $firstAtt = true;
      $query = 'insert into '.$table.' (';

      $keys = '';
      $values = '';

      foreach ($tuple as $key => $value){
        if($firstAtt){
          $firstAtt = false;     
        }
        else{ 
          $keys = $keys.', ';
          $values = $values.', ';   
        }
        $keys = $keys.$key;
        $values = $values.$value;
      } 
      $query = $query.$keys.') values ('.$values;

      $query = $query.')';
      
      return $this->exec($query);
    }

//-------- SELECTS ----------------------------------------------------------------------------------------------------------------------

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

    function distinctSelect($table, $selectors){
      $firstSelector = true;
      $query = 'select distinct ';

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

    //If the tuple exists, return true
    function exists($table, $selectors){
      $reasult = false;
      if(func_num_args() > 2) {
        $result = $this->simpleSelect($table, $selectors, func_get_arg(2));
      }
      else{
        $result = $this->simpleSelect($table, $selectors);
      }
      return $result ? ($result->num_rows != 0) : false;
    }

//-------- TABLE MANIPULATION -----------------------------------------------------------------------------------------------------------

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

//-------- EXECUTE ----------------------------------------------------------------------------------------------------------------------

   	function exec($query){
      //println('Executing: ['.$query.']');
      $result = $this->db->query($query);
      if(!$result){
        println("Invalid query " . $this->db->error);
        return false;
      } else {
      	return $result;
      }
   	}

//-------- HELPERS ----------------------------------------------------------------------------------------------------------------------

   	function tableToString($table){
   		$result = $this->simpleSelect($table, ['*']);
   		print_r($result);
   	}

   	function dbToString(){
   	}

}

?>