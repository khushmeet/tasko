<?php

namespace App\Controllers;

/**
* Home Class is the main frontend class it control login/register
* main screen of the app
*/
class Main extends BaseController
{

	/**
	* Main task list view
	*/
	public function index()
	{
		// helper : check if user is logged in?
		loginCheck();
		
		$data['body'] = view('main\index');

		return view('index', $data);

	}

	/*
	* Handle login and token 
	*/
	public function login()
	{
		// check if user is loged in by calling helper
		isLoggedIn();

		// if request is ajax validate the login credientials
		if ($this->request->isAJAX() ) {

			// validate request
			$this->validateUser();
			
        }

        // load login form
		$data['body'] = view('form\login');

		return view('index', $data);
	}

	/**
	* Validate the user from api on success set token in session
	* @return string
	*/
	protected function validateUser() {

		// Call the data libray 
		$LData = new \App\Libraries\Data();

		// Validate request
        $response = $LData->validateRequest($_POST);

        // if valid user set session 
        if($response['status_code'] === 200 && isset($response['response']['token'])) {
				
			$this->session->set($response['response']);
       	
        }

        // return api response
         $response = !empty($response['response']) ?$response['response']:['message'=>'Oops! Some error occured.'];

        	
		returnJson($response);

	}

	/*
	* Register User
	* @return string
	*/
	public function register()
	{
		// check if user is loged in 
		isLoggedIn();

		// if request is ajax validate the login credientials
		if ($this->request->isAJAX() ) {

			// validate request
			$this->registerUser();
			
        }

		$data['body'] = view('form\register');

		return view('index', $data);

	}

	/**
	* Register the user send details to api
	* @return string
	*/
	protected function registerUser() {

		// Call the data libray 
		$LData = new \App\Libraries\Data();

		// Validate request
        $response = $LData->registerUser($_POST);
        
        if(!empty($response['response'])) {
        	
        	$response = $response['response'];
        
        }else{

        	$response = ['message' => 'Oops! Some issue occured Please try again.' ];
        }

        returnJson($response);
	}

	/*
	* Unset session and send user to login screen 
	*/
	public function logout()
	{
		// destroy all session
		$this->session->destroy();

		header("Location:".base_url()."/login");
		
		exit();

	}

	
}
