<?php

  class Teams_model extends CI_Model {

    public function __construct() {
      parent::__construct();
      $this->load->database();
    }

    public function getTeamByAbbr($teamabbr) {
      $this->db->select();
      $this->db->from('teams');
      $this->db->where('abbr',$teamabbr);

      $query = $this->db->get();
      return $query->row();
    }

    public function getRosterByTeamID($teamid) {
      $this->db->select();
      $this->db->from('players');
      $this->db->where('team_id',$teamid);

      $query = $this->db->get();
      return $query->result();
    }

    public function getResultsByTeamID($teamid) {
      /*
      date
      opponent
      result / time
      w-l-t total / tv
      goalie / play game
      */
      $this->db->select("
        s.id AS schedule_id, s.gamedate, opp.abbr, opp.name AS opponent, r.GameResult, gpos.id AS goalieid, gpos.lastname AS goalie,
        g.goals AS goals_this, opp.goals AS goals_opp,
        CONCAT(
          SUM(CASE WHEN r.GameResult = 'W' THEN 1 ELSE 0 END) OVER (ORDER BY s.id),'-',
          SUM(CASE WHEN r.GameResult = 'L' THEN 1 ELSE 0 END) OVER (ORDER BY s.id),'-',
          SUM(CASE WHEN r.GameResult = 'T' THEN 1 ELSE 0 END) OVER (ORDER BY s.id)
        ) AS record
      ");
      $this->db->from('games AS g');
      $this->db->join('schedule AS s','g.schedule_id = s.id');
      $this->db->join('teams AS t','g.team_id = t.id');
      $this->db->join('results AS r','s.id = r.schedule_id and t.id = r.team_id');
      $this->db->join(
        '(SELECT g.schedule_id, g.goals, t.id as team_id, t.abbr, t.name
          FROM games g
          JOIN teams t ON g.team_id = t.id) AS opp',
        'g.schedule_id = opp.schedule_id and t.id <> opp.team_id'
      );
      $this->db->join(
        "(SELECT ps.game_id, p.id, p.lastname, ps.toi, RANK() OVER(PARTITION BY ps.game_id ORDER BY ps.toi DESC) AS toirank
          FROM playerstats ps
          JOIN players p ON ps.player_id = p.id
          WHERE p.pos = 'G') AS gpos",
        'gpos on g.id = gpos.game_id and gpos.toirank = 1'
      );
      $this->db->where('t.id',$teamid);

      $query = $this->db->get();
      return $query->result();
    }
  }
?>
