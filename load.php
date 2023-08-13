<?php 
	require_once("connect.php");
	

	$query = "SELECT * FROM contacts";
	$res = mysqli_query($link, $query) or die(mysqli_error($link));

	$result = [
	'id' => '',
	'name' => '',
	'phone' => ''
	];
	

	for ($data = []; $row = mysqli_fetch_assoc($res); $data[] = $row);
	
	foreach ($data as $key){
		$result['id'] = $key['id'];
		$result['name'] = $key['name'];
		$result['phone'] = $key['phone'];
		}
	
	echo json_encode ($data,JSON_UNESCAPED_UNICODE);
