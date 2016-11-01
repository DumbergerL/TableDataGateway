<?php
	include("TableDataGateway.php");

	global $pdo;			           
	try {
	  $pdo = new PDO("mysql:host=localhost;dbname=MyDB", "myUserName", "password205");
	}catch(PDOException $e){
	    echo $e->getMessage();
	}     //initialize connection to the Database

	$Employee = new TableDataGateway($pdo, "Employee", array("EmployeeID", "Name", "Surname", "UnitID"));
	$Unit = new TableDataGateway($pdo, "Unit", array("UnitID", "Description", "Pay"));

	//-----------------Get------------------	
	$employeeID = 2;
  $record = $Employee->Get($employeeID);
	echo "Surname of employee 2: ".$record["Surname"];

	//-----------------GetAll--------------
	$allUnit = $Unit->GetAll("Description");
	for($i = 0; i < sizeof($allUnit); $i++){
		echo $allUnit[$i]."<br>";
	}

	//-----------------Add-----------------
	$newEmployee = array(null, "Jobs", "Steve", 2);
	$Employee->Add($newEmployee);

	$newUnit = array (null, "Accounting", 12.43);
	if($Unit->Add($newUnit)){
		echo "Added record successfully";
	}else{
		echo "An error occurred!";
	}

	//----------------Del------------------
	$Employee->Del(2);	//Delete Employee with ID 2

	//----------------Set-----------------
	$newRecord = array(null, "Smith",null,null);
	$employeeID = 2;
	$Employee->Set($newRecord, $employeeID);

	//----------------Search------------
	$name = "Alex";
	$employeeArray = $Employee->Search("Name", $name);
	for($i = 0; i < sizeof($employeeArray); $i++){
		echo "Alex ".$employeeArray[$i]["Surname"]." works in Unit ".$employeeArray[$i]["UnitID"];
	}

	//---------------CountRows----------
	$number = $Empoyee->CountRows();
	echo "We have ".$number." Employees";

?>
