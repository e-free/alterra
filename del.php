<?php 
	require_once("connect.php");
	$id = $_GET['id'];
	if ($id){
	$query = "DELETE FROM contacts WHERE id = '" . $id . "'";
	$res = mysqli_query($link, $query) or die(mysqli_error($link));
	}
