<?php  
	
	$postData = json_decode(file_get_contents("php://input"));
	$userName = $postData->name;
	$userPhone = $postData->phone;
	
	if (isset($userName) && isset($userPhone)){
	
		require_once("connect.php");		
		$query = "INSERT INTO contacts (name, phone) VALUES ('" . $userName . "', '" . $userPhone . "')";
		$res = mysqli_query($link, $query) or die(mysqli_error($link));	
		
	}
	
	
	
