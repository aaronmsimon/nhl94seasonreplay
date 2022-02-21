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
        COALESCE(s.homewins,0) AS homewins,
        COALESCE(s.homelosses,0) AS homelosses,
        COALESCE(s.hometies,0) AS hometies,
        COALESCE(s.awaywins,0) AS awaywins,
        COALESCE(s.awaylosses,0) AS awaylosses,
        COALESCE(s.awayties,0) AS awayties,
        COALESCE(s.wins,0) * 2 + COALESCE(s.ties,0) AS points,
        COALESCE(s.goalsfor,0) AS goalsfor,
        COALESCE(s.goalsagainst,0) as goalsagainst,
        COALESCE(s.last10wins,0) AS last10wins,
        COALESCE(s.last10losses,0) AS last10losses,
        COALESCE(s.last10ties,0) AS last10ties,
        s.streak'
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
          sum(case when d.tieflag = 0 and g.goals = d.maxgoals then d.mingoals else d.maxgoals end) as goalsagainst,
          sum(case when s.hometeam_id is not null and d.tieflag = 0 and g.goals = d.maxgoals then 1 else 0 end) as homewins,
          sum(case when s.hometeam_id is not null and d.tieflag = 0 and g.goals <> d.maxgoals then 1 else 0 end) as homelosses,
          sum(case when s.hometeam_id is not null and d.tieflag = 1 then 1 else 0 end) as hometies,
          sum(case when s.hometeam_id is null and d.tieflag = 0 and g.goals = d.maxgoals then 1 else 0 end) as awaywins,
          sum(case when s.hometeam_id is null and d.tieflag = 0 and g.goals <> d.maxgoals then 1 else 0 end) as awaylosses,
          sum(case when s.hometeam_id is null and d.tieflag = 1 then 1 else 0 end) as awayties,
          sum(case when l10.schedule_id is not null and d.tieflag = 0 and g.goals = d.maxgoals then 1 else 0 end) as last10wins,
          sum(case when l10.schedule_id is not null and d.tieflag = 0 and g.goals <> d.maxgoals then 1 else 0 end) as last10losses,
          sum(case when l10.schedule_id is not null and d.tieflag = 1 then 1 else 0 end) as last10ties,
          CONCAT(strk.gameresult,strk.streak) AS streak
        from games g
        join teams t
        on g.team_id = t.id
        join (
        select schedule_id,max(goals) as maxgoals, min(goals) as mingoals, case when max(goals) = min(goals) then 1 else 0 end as tieflag
        from games g
        group by schedule_id
        ) d
        on g.schedule_id = d.schedule_id
        left join schedule s
        on g.schedule_id =  s.id
        and t.id = s.hometeam_id
        left join (
            SELECT *
            FROM (
                SELECT t.id AS team_id, g.schedule_id,
                RANK() OVER(PARTITION BY t.id ORDER BY g.id DESC) AS row_num
                FROM games g
                JOIN teams t ON g.team_id = t.id
            ) g
            WHERE row_num <= 10
        ) l10
        on d.schedule_id = l10.schedule_id
        and t.id = l10.team_id
        join (
            SELECT r.team_id, r.gameresult, COUNT(r.rungroup) AS Streak
            FROM
            (
            	SELECT r.team_id, r.gameresult,
            		(SELECT COUNT(*)
            		FROM results r1
            		WHERE r1.gameresult <> r.gameresult
            		AND r1.schedule_id <= r.schedule_id
            		AND r1.team_id = r.team_id) as RunGroup
            	FROM results r
            ) r
            JOIN
            (
            	SELECT r.team_id, r.gameresult,
            		(SELECT COUNT(*)
            		FROM results r1
            		WHERE r1.gameresult <> r.gameresult
            		AND r1.schedule_id <= r.schedule_id
            		AND r1.team_id = r.team_id) as RunGroup
            	FROM results r
            	JOIN (SELECT team_id, MAX(schedule_id) AS lastScheduleID FROM games GROUP BY team_id) l ON r.team_id = l.team_id AND r.schedule_id = l.lastScheduleID
            ) m
            ON r.team_id = m.team_id AND r.rungroup = m.rungroup AND r.gameresult = m.gameresult
            GROUP BY r.team_id
        ) strk
        on g.team_id = strk.team_id
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
