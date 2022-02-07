<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		// Load any helpers here (such as url and form)
	}

	public function index()
	{
		$this->load->view('home',$this->data);
	}
}
