<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Standings extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('standings_model');
	}

	public function index()
	{
    $this->data['standings'] = $this->standings_model->getStandings();
		$this->load->view('standings',$this->data);
	}

}
