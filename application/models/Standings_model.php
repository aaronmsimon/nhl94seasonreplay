<?php

  class Standings_model extends CI_Model {

    public function __construct() {
      parent::__construct();
      $this->load->database();
    }

    public function getConferences($nhlonly) {
      $this->db->select('id, name, abbr');
      $this->db->from('conferences');
      $this->db->where('nhlflag', $nhlonly);
      $this->db->order_by('sortorder ASC');

      $query = $this->db->get();
      return $query->result();
    }

    public function getDivisionsByConferenceID($conferenceid) {
      $this->db->select('id, name, abbr');
      $this->db->from('divisions');
      $this->db->where('conference_id', $conferenceid);
      $this->db->order_by('sortorder ASC');

      $query = $this->db->get();
      return $query->result();
    }

    public function getTeamStandingsByDivisionID($divisionid) {
      $this->db->select('t.abbr, t.city, t.name,
        COALESCE(s.gamesplayed,0) AS gamesplayed,
        COALESCE(s.wins,0) AS wins,
        COALESCE(s.losses,0) AS losses,
        COALESCE(s.ties,0) AS ties,
        COALESCE(s.wins,0) * 2 + COALESCE(s.ties,0) AS points,
        COALESCE(s.goalsfor,0) AS goalsfor,
        COALESCE(s.goalsagainst,0) as goalsagainst'
      );
      $this->db->from('teams AS t');
      $this->db->join('divisions AS d','t.division_id = d.id');
      $this->db->join('(
        select g.team_id,
          count(*) as gamesplayed,
          sum(case when d.tieflag = 0 and g.goals = d.maxgoals then 1 else 0 end) as wins,
          sum(case when d.tieflag = 0 and g.goals <> d.maxgoals then 1 else 0 end) as losses,
          sum(case when d.tieflag = 1 then 1 else 0 end) as ties,
          sum(g.goals) as goalsfor,
          sum(case when d.tieflag = 0 and g.goals = d.maxgoals then d.mingoals else d.maxgoals end) as goalsagainst
        from games g
        join teams t
        on g.team_id = t.id
        join (
        select schedule_id,max(goals) as maxgoals, min(goals) as mingoals, case when max(goals) = min(goals) then 1 else 0 end as tieflag
        from games g
        group by schedule_id
        ) d
        on g.schedule_id = d.schedule_id
        group by g.team_id
        ) AS s','t.id = s.team_id','left');
      $this->db->where('d.id', $divisionid);
      $this->db->order_by('points DESC, wins DESC, losses ASC, city ASC, name ASC');

      $query = $this->db->get();
      return $query->result();
    }

    public function getStandings() {
      $conferences = array();
      foreach ($this->getConferences(true) as $conf) {
        $divisions = array();
        foreach ($this->getDivisionsByConferenceID($conf->id) as $div) {
          $div->teams = $this->getTeamStandingsByDivisionID($div->id);
          array_push($divisions,$div);
        }
        $conf->divisions = $divisions;
        array_push($conferences,$conf);
      }
      return $conferences;
    }

    public function getNextDate() {
      /*
      select str_to_date(concat(month(firstgameavailable),',1,',year(firstgameavailable)),'%m,%d,%Y') as datepicker_date
from (
select min(s.gamedate) as firstgameavailable
from schedule s
left join games g
on s.id = g.schedule_id
where g.schedule_id is null
) a
*/
    }

    public function getRecordByTeamID($teamid) {
      $this->db->select('sum(case when d.tieflag = 0 and g.goals = d.maxgoals then 1 else 0 end) as wins,sum(case when d.tieflag = 0 and g.goals <> d.maxgoals then 1 else 0 end) as losses,sum(case when d.tieflag = 1 then 1 else 0 end) as ties');
      $this->db->from('teams as t');
      $this->db->join('games as g','t.id = g.team_id','left');
      $this->db->join('(
        select schedule_id,max(goals) as maxgoals, min(goals) as mingoals, case when max(goals) = min(goals) then 1 else 0 end as tieflag
        from games g
        group by schedule_id
      ) as d','g.schedule_id = d.schedule_id','left');
      $this->db->where('t.id',$teamid);
      $this->db->group_by('g.team_id');
      $query = $this->db->get();
      return $query->row();
    }

  }
?>
