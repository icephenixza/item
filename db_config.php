<?php
	$dbHost = "localhost";
	$dbDatabase = "db_item";
	$dbPasswrod = "";
	$dbUser = "root";
	
	$mysqli = new mysqli($dbHost, $dbUser, $dbPasswrod, $dbDatabase);
    mysqli_set_charset($mysqli,"utf8");
	$con = mysqli_connect($dbHost, $dbUser, $dbPasswrod, $dbDatabase);
	$conn = mysqli_connect($dbHost, $dbUser, $dbPasswrod, $dbDatabase);
	date_default_timezone_set("Asia/bangkok");
	$now = date("Y-m-d H:i:s");

	/*
	ตัวอย่างการใช้งานฐานข้อมูล MySQLi (ทั้งหมดนี้มีปัญหาเรื่องความปลอดภัย sql injection)
	
	// SELECT example
	$sql="SELECT * FROM materials";
	if ($result = $mysqli->query($sql)) 
	{                  
		$num_rows = $result->num_rows;
		while ($row = $result->fetch_assoc()) 
		{   
			echo $row['material_name'];
			echo $row['material_type'];
			echo $row['quantity'];
		}
	}

	// INSERT example
	$material_name = "Steel";
	$material_type = "Metal";
	$quantity = 100;
	$sql = "INSERT INTO materials (material_name, material_type, quantity) VALUES ('$material_name', '$material_type', $quantity)";
	$mysqli->query($sql);

	// UPDATE example
	$id = 1;
	$material_name = "Updated Steel";
	$material_type = "Updated Metal";
	$quantity = 150;
	$sql = "UPDATE materials SET material_name = '$material_name', material_type = '$material_type', quantity = $quantity WHERE id = $id";
	$mysqli->query($sql);

	// DELETE example
	$id = 1;
	$sql = "DELETE FROM materials WHERE id = $id";
	$mysqli->query($sql);
	*/
?>