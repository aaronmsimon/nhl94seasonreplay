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

  }
?>
