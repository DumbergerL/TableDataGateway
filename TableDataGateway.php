<?php

/*
TableDataGateway
	constructor(pdo :Object, tableName :String, columnName :String[])
	Get(id :Int) :String[]
	GetAll(columnName :String) :String[]
	Add(data :Array[]) :Bool
	Del(id :Int) :Bool
	Set(data :Array[], id :Int) :Bool
	Search(columnName :String, SearchTerm :String) :Array[][]
	CountRows() :Int 
*/


class TableDataGateway{
  /*
  Author: Lukas Dumberger
  Version: 2016-10-25
  Important: DataTable need an Primary Key called [TableName]ID (e.g.: CustomerID)
  */
  
  private $pdo;
  private $tableName;
  private $columnName;
  
  function __construct($pdo, $tableName, $columnName){
      $this->pdo = $pdo;    
      $this->tableName = $tableName;
      $this->columnName = $columnName;
  }
  
  public function Get($id){
      $statement = $this->pdo->prepare('SELECT * FROM '.$this->tableName.' WHERE '.$this->tableName.'ID='.$id.';');
      $statement->execute();   
      return $statement->fetch();
  }
  
  public function GetAll($columnName){
      $returnVal = array();
      $statement = $this->pdo->prepare('SELECT * FROM '.$this->tableName.';');
      $statement->execute();
      while($row = $statement->fetch()) {
        array_push($returnVal, $row[$columnName]);
      }
      return $returnVal;
  
  }
  
  public function Add($data){ //nur die Daten (keine Spaltenangaben)
      if(sizeof($data) != sizeof($this->columnName))return false;
      $sqlA = 'INSERT INTO '.$this->tableName.' (';
      $sqlB = ') VALUES (';
      $sqlC = ');';
                                              
      for($i = 0; $i < sizeof($data); $i= $i+1){
        $sqlA = $sqlA.''.$this->columnName[$i];
        $arrErsetzen1 = array("\\");   //SONDERZEICHEN ERSETZEN
        $arrErsetzen2 = array("&#92;");   //SONDERZEICHEN ERSETZEN
          $data[$i] = str_replace($arrErsetzen1,$arrErsetzen2, $data[$i]); 
          
        if(is_string($data[$i])){           //STRING
          $sqlB = $sqlB.'"'.$data[$i].'"';
        }else if(is_int(intval($data[$i])) && $data[$i] != null){//INT
          $sqlB = $sqlB.$data[$i];            
        }else{                              //NULL
          $sqlB = $sqlB.'" "';      
        }
        if($i != sizeof($data)-1){  //Komma Algorithmus
          $sqlA = $sqlA.",";
          $sqlB = $sqlB.",";
        }
      }
      $sql = $sqlA.$sqlB.$sqlC;
      return $this->doSQL($sql);
  }
  
  public function Del($id){
      $sql = 'DELETE FROM '.$this->tableName.' WHERE '.$this->tableName.'ID='.$id.';';
      return $this->doSQL($sql);
  }
  
  public function Set($data, $id){
      if(sizeof($data) != sizeof($this->columnName))return false;
      $sqlA = 'UPDATE '.$this->tableName.'  SET ';
      $sqlB = ' WHERE '.$this->tableName.'ID ='.$id.';';
      for($i = 0; $i < sizeof($data); $i=$i+1){
        if(is_string($data[$i]))                        $sqlA = $sqlA.$this->columnName[$i].'="'.$data[$i].'"'; //String
        else if($data[i] == null && !is_int($data[$i])) $nichtstun = true;                                      //null
        else                                            $sqlA = $sqlA.$this->columnName[$i].'='.$data[$i];      //Int
        if($i != sizeof($data)-1 && !($data[i] == null && !is_int($data[$i]))){  //Komma Algorithmus
          $sqlA = $sqlA.", ";
        }        
      }        
      $sql = $sqlA.$sqlB;
      return $this->doSQL($sql);
  }
  
  public function Search($columnName, $searchTerm){
      $retVal = array();
      $statement = $this->pdo->prepare('SELECT * FROM '.$this->tableName.' WHERE '.$columnName.' LIKE "%'.$searchTerm.'%";');
      $statement->execute();
      while($row = $statement->fetch()) {
        array_push($retVal, $row);
      }
      return $retVal;
  }
  
  private function doSQL($sql){
      try{
        $this->pdo->exec($sql);
        return true;
      }catch(PDOExeption $e){
        return false;
      }
  }
  
  public function CountRows(){
      $numberRows = false;
      $statement = $this->pdo->prepare('SELECT COUNT(*) FROM '.$this->tableName.';');
      $statement->execute();   
      return $statement->fetch()[0];
  }
}


?>
