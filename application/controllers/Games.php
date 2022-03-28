<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Games extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('games_model');
		$this->load->model('schedule_model');
		$this->load->model('standings_model');
		$this->load->model('stats_model');
	}

	public function index()
	{
    // header('Location: ' . $this->config->item('base_url') . 'schedule/upcoming-games');
	}

  public function boxscore($scheduleid) {
    $this->data['gamestats'] = $this->games_model->getGameStats($scheduleid);
	$this->data['periodstats'] = $this->games_model->getPeriodStats($scheduleid);
    $this->data['scoringsummary'] = $this->games_model->getScoringSummary($scheduleid);
    $this->data['penaltysummary'] = $this->games_model->getPenaltySummary($scheduleid);
	$teams = $this->games_model->getTeamsByScheduleID($scheduleid);
	foreach ($teams as $team) {
		$team->playerstats['skaters'] = $this->games_model->getPlayerStatsByGameID($team->gameid);
		$team->playerstats['goalies'] = $this->games_model->getGoalieStatsByGameID($team->gameid);
	}
	$this->data['playerstats'] = $teams;
	$this->data['sog'] = $this->games_model->getSOGByPeriodByScheduleID($scheduleid);
	$this->data['otflag'] = $this->games_model->doesGameHaveOT($scheduleid);
	$this->load->view('boxscore',$this->data);
  }

	public function play_game() {
		$scheduleid = $this->schedule_model->getCurrentGame();
		$this->data['gameinfo'] = $this->schedule_model->getScheduleByID($scheduleid);
		$this->data['home']['record'] = $this->standings_model->getRecordByTeamID($this->data['gameinfo']->hometeam_id);
		$this->data['away']['record'] = $this->standings_model->getRecordByTeamID($this->data['gameinfo']->awayteam_id);
		$this->data['home']['specialteams'] = $this->stats_model->getTeamStats($this->data['gameinfo']->hometeam_id);
		$this->data['away']['specialteams'] = $this->stats_model->getTeamStats($this->data['gameinfo']->awayteam_id);
		if (isset($_GET['playing_as'])) {
			$this->data['playing_as'] = $_GET['playing_as'];
			$homegoalie = $_GET['home'];
			$awaygoalie = $_GET['away'];
			$this->data['home']['goalies'] = $this->games_model->getRosterByTeamID($this->data['gameinfo']->hometeam_id)[$homegoalie];
			$this->data['away']['goalies'] = $this->games_model->getRosterByTeamID($this->data['gameinfo']->awayteam_id)[$awaygoalie];
		}
		$this->load->view('playgame',$this->data);
	}

	public function editgoals() {
		$this->data['goals'] = json_decode(exec('python www/python/getgoals.py'),true);
		$scheduleid = $this->schedule_model->getCurrentGame();
		$game = $this->schedule_model->getScheduleByID($scheduleid);
		$temp = $this->games_model->getRosterByTeamID($game->hometeam_id);
		foreach ($temp as $i => $row) {
			$temp[$i]->index = $i;
		}
		$this->data['homeroster'] = $temp;
		$temp = $this->games_model->getRosterByTeamID($game->awayteam_id);
		foreach ($temp as $i => $row) {
			$temp[$i]->index = $i;
		}
		$this->data['awayroster'] = $temp;
		$this->load->view('editgoals',$this->data);
	}

	/**
   *
   * AJAX Functions
   *
   */
  public function update_save() {
    $goals = $_POST['goals'];
    $this->games_model->editgoals($goals);
		system('python www/python/setgoals.py');
  }

}
