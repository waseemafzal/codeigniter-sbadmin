<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		
		parent::__construct();
		
		$this->Admin_model->verifyUser(); 
	}
	
	public function index()
	{
		$this->load->view('dashboard');
	}
	public function dashboard()
	{
		$this->load->view('dashboard');
	}
	
	
}
