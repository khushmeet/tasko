<?php

/*
* This function make sure no unauthorised access is there
*/
function loginCheck()
{

    if(!isset($_SESSION['token'])) {
		
		header("Location:".base_url()."/logout");
		exit();
    
    }

}

/*
* This function make sure user does not land to login / register page once loged in
*/
function isLoggedIn() 
{

	 if(isset($_SESSION['token'])) {
		
		header("Location:".base_url()."/main");
		exit();
    
    }

}

/*
* This function return json
*/
function returnJson($response)
{
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;

}

/*
* This function set response
* @param array | string $message
* @return array
*/
function setAPIResponse($message, $status, $other=null)
{
	$response = [
		'messages' => $message,
		'status' =>  $status
	];

	if($other) {
		
		$response = array_merge($response, $other);
	}

	return $response;

}

function convertDate($timestamp) 
{

	return  $timestamp;//gmdate("d/mY H:i", $timestamp);
}

function convertTime($date)
{

	$d = $_POST['start_time'];//date('m/d/y h:i',strtotime($_POST['start_time']));
	return  $d;
	
}