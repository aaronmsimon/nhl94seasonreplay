<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats extends MY_Controller {

	protected $limit = 20;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('stats_model');
	}

	public function index()
	{
		$this->load->view('stats',$this->data);
  }

  public function leaders() {
    $this->data['leaders'] = $this->stats_model->getLeagueLeaders('pts', 'DESC', $this->limit, 0);
		$this->data['page'] = 1;
    $this->load->view('leaders',$this->data);
  }

  /**
   *
   * AJAX Functions
   *
   */
  public function load_league_leaders() {
    $statistic = $_POST['statistic'];
    $sortorder = $_POST['sortorder'];
		$page = $_POST['page'];
		$offset = ($page - 1) * $this->limit;
    $leaders = $this->stats_model->getLeagueLeaders($statistic, $sortorder, $this->limit, $offset);
    echo json_encode($leaders);
  }

}
