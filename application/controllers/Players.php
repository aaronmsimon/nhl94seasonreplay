<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Players extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

    $this->load->model('players_model');
		$this->load->model('stats_model');
	}

	public function index()
	{
		$this->load->view('players',$this->data);
	}

  public function profile($playerid) {
    $this->data['player'] = $this->players_model->getPlayerByID($playerid);

    if ($this->data['player']->pos <> 'G') {
      $this->data['stats'] = $this->stats_model->getPlayerStatsByPlayerID($playerid);
      $this->data['games'] = $this->stats_model->getPlayerStatsByGameByPlayerID($playerid);
      $this->load->view('playerlookup',$this->data);
    } else {
      $this->data['stats'] = $this->stats_model->getGoalieStatsByPlayerID($playerid);
      $this->data['games'] = $this->stats_model->getGoalieStatsByGameByPlayerID($playerid);
      $this->load->view('goalielookup',$this->data);
    }
  }

  /**
   *
   * AJAX Functions
   *
   */
   public function search_player() {
     $name = $_POST['name'];
     $results = $this->players_model->getPlayerByName($name);
     echo json_encode($results);
   }

}
