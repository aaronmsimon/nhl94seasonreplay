<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teams extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('teams_model');
	}

  public function roster($teamabbr) {
    $this->data['team'] = $this->teams_model->getTeamByAbbr($teamabbr);
		$this->data['roster'] = $this->teams_model->getRosterByTeamID($this->data['team']->id);
    $this->load->view('roster',$this->data);
  }

}
