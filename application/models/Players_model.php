<?php

  class Players_model extends CI_Model {

    public function __construct() {
      parent::__construct();
      $this->load->database();
    }

    public function getPlayerByName($name) {
      $this->db->select('p.*,t.abbr');
      $this->db->from('players as p');
      $this->db->join('teams as t','p.team_id = t.id');
      $this->db->where("firstname LIKE '%$name%' OR lastname LIKE '%$name%'");

      $query = $this->db->get();
      return $query->result();
    }

    public function getPlayerByID($playerid) {
      $this->db->select('p.*,t.abbr');
      $this->db->from('players as p');
      $this->db->join('teams as t','p.team_id = t.id');
      $this->db->where('p.id',$playerid);

      $query = $this->db->get();
      return $query->row();
    }

  }
?>
