<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('schedule_model');
	}

	public function index()
	{
    header('Location: ' . $this->config->item('base_url') . 'schedule/upcoming-games');
	}

  public function upcoming_games() {
		$gamestatus = $this->schedule_model->getGameStatus($this->schedule_model->getCurrentGame());
		if ($gamestatus == 0) {
			header('Location: ' . $this->config->item('base_url') . 'games/play-game');
		} else {
			$this->data['games'] = $this->schedule_model->getNextGame(10);
	    $this->data['gamestatus'] = $gamestatus;
	    $this->data['currentgame'] = $this->schedule_model->getCurrentGame();
			$this->load->view('upcoming',$this->data);
		}
  }

  public function full_schedule($teamabbr = null) {
    $this->data['schedule'] = $this->schedule_model->getSchedule($teamabbr);
		$this->data['teams'] = $this->schedule_model->getTeams(true);
    $this->load->view('schedule',$this->data);
  }

  /**
   *
   * AJAX Functions
   *
   */
  public function log_game() {
    $scheduleid = $_POST['gameid'];
    $this->schedule_model->logGame($scheduleid);
		$goalies = $this->schedule_model->getGoaliesByScheduleID($scheduleid);
		$rand = rand(1,100);
		$home = $goalies[0];
		$away = $goalies[1];
		log_message('info',"Python call: python www/python/setteams.py $rand $home $away");
		system("python www/python/setteams.py $rand $home $away");
  }

  public function finish_game() {
		$scheduleid = $this->schedule_model->getCurrentGame();
    system('python www/python/statextractor_db.py');
		echo $scheduleid;
  }

	public function switch_sides() {
    $result = system('python www/python/switchsides.py');
  }

}
