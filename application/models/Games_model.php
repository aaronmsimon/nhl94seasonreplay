<?php

  class Games_model extends CI_Model {

    public function __construct() {
      parent::__construct();
      $this->load->database();
    }

    public function getGameStats($scheduleid) {
      $this->db->select("g.*,TIME_FORMAT(SEC_TO_TIME(g.ppseconds),'%i:%s') AS pptime,TIME_FORMAT(SEC_TO_TIME(g.attackzoneseconds),'%i:%s') AS attackzonetime,t.abbr");
      $this->db->from('games AS g');
      $this->db->join('teams AS t','g.team_id = t.id');
      $this->db->where('g.schedule_id',$scheduleid);
      $this->db->order_by('g.id ASC');

      $query = $this->db->get();
      return $query->result();
    }

    public function getScoringSummary($scheduleid) {
      $this->db->select("ss.period, TIME_FORMAT(SEC_TO_TIME(ss.timeelapsed),'%i:%s') AS timeelapsed, t.abbr,
        CONCAT(g.num,' ',SUBSTRING(g.firstname,1,1),'. ',g.lastname) As goal,
        CONCAT('(',
          CASE WHEN a1.num IS NULL THEN 'Unassisted' ELSE
            CONCAT(a1.num,' ',SUBSTRING(a1.firstname,1,1),'. ',a1.lastname,
              CASE WHEN a2.num IS NOT NULL THEN CONCAT(', ',a2.num,' ',SUBSTRING(a2.firstname,1,1),'. ',a2.lastname) ELSE '' END
            )
          END,')') AS assists,
        CASE WHEN gt.category IS NOT NULL THEN CONCAT(' (',gt.category,')') ELSE '' END AS goalsuffix
      ");
      $this->db->from('scoringsummary AS ss');
      $this->db->join('players AS g','ss.goal_player_id = g.id');
      $this->db->join('teams AS t','g.team_id = t.id');
      $this->db->join('players AS a1','ss.assist1_player_id = a1.id','left');
      $this->db->join('players AS a2','ss.assist2_player_id = a2.id','left');
      $this->db->join('goaltypes AS gt','CONV(ss.goaltype,16,10) = gt.id');
      $this->db->where('ss.schedule_id',$scheduleid);
      $this->db->order_by("ss.period ASC, TIME_FORMAT(SEC_TO_TIME(ss.timeelapsed),'%i:%s') ASC");

      $query = $this->db->get();
      return $query->result();
    }

    public function getPenaltySummary($scheduleid) {
      $this->db->select("ps.period, TIME_FORMAT(SEC_TO_TIME(ps.timeelapsed),'%i:%s') AS timeelapsed, t.abbr,
        CONCAT(pl.num,' ',SUBSTRING(pl.firstname,1,1),'. ',pl.lastname) AS player, p.penalty, p.minutes
      ");
      $this->db->from('penaltysummary AS ps');
      $this->db->join('penalties AS p','ps.penalty_id = p.id');
      $this->db->join('players AS pl','ps.player_id = pl.id');
      $this->db->join('teams AS t','pl.team_id = t.id');
      $this->db->where('ps.schedule_id',$scheduleid);
      $this->db->order_by("ps.period ASC, TIME_FORMAT(SEC_TO_TIME(ps.timeelapsed),'%i:%s') ASC, t.abbr ASC");

      $query = $this->db->get();
      return $query->result();
    }

    public function getRosterByTeamID($teamid) {
      $this->db->select();
      $this->db->from('players as p');
      $this->db->where('p.team_id',$teamid);

      $query = $this->db->get();
      return $query->result();
    }

    public function editgoals($goals) {
      // $result = print_r($json,true);
      // file_put_contents('www/scoringsummary.txt', $result);
      $fp = fopen('www/scoringsummary.txt','w');
      foreach ($goals as $goal) {
        fputcsv($fp,$goal);
      }
    }

  }
?>
