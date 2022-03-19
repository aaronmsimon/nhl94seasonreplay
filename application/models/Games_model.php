<?php

  class Games_model extends CI_Model {

    public function __construct() {
      parent::__construct();
      $this->load->database();
    }

    public function getGameStats($scheduleid) {
      $this->db->select("g.*,TIME_FORMAT(SEC_TO_TIME(g.ppseconds),'%i:%s') AS pptime,TIME_FORMAT(SEC_TO_TIME(g.attackzoneseconds),'%i:%s') AS attackzonetime,t.abbr,t.name,s.gamedate");
      $this->db->from('games AS g');
      $this->db->join('teams AS t','g.team_id = t.id');
      $this->db->join('schedule AS s','g.schedule_id = s.id');
      $this->db->where('g.schedule_id',$scheduleid);
      $this->db->order_by('g.id DESC');

      $query = $this->db->get();
      return $query->result();
    }

    public function getTeamsByScheduleID($scheduleid) {
        $this->db->select('g.id AS gameid,t.city,t.name,t.abbr');
        $this->db->from('games AS g');
        $this->db->join('schedule AS s','g.schedule_id = s.id');
        $this->db->join('teams AS t','g.team_id = t.id');
        $this->db->where('s.id',$scheduleid);
        $this->db->order_by('g.id DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getPeriodStatsByGameID($gameid) {
        $this->db->select('p.*');
        $this->db->from('periodstats AS p');
        $this->db->where('p.game_id',$gameid);
        $query = $this->db->get();
        return $query->result();
    }

    public function getPeriodStats($scheduleid) {
        $teams = $this->getTeamsByScheduleID($scheduleid);
        foreach ($teams as $team) {
            $team->periodstats = $this->getPeriodStatsByGameID($team->gameid);
        }
        return $teams;
    }

    public function getScoringSummary($scheduleid) {
      $this->db->select("ss.period, TIME_FORMAT(SEC_TO_TIME(ss.timeelapsed),'%i:%s') AS timeelapsed, t.abbr,
        CONCAT(g.firstname,' ',g.lastname) As goal,
        COALESCE(num.goals,0) + 1 AS goalnum,
        CONCAT(
          CASE WHEN a1.num IS NULL THEN 'Unassisted' ELSE
            CONCAT(SUBSTRING(a1.firstname,1,1),'. ',a1.lastname,
              CASE WHEN a2.num IS NOT NULL THEN CONCAT(', ',SUBSTRING(a2.firstname,1,1),'. ',a2.lastname) ELSE '' END
            )
          END) AS assists,
        CASE WHEN gt.category IS NOT NULL THEN UPPER(gt.category) ELSE '' END AS goalsuffix
      ");
      $this->db->from('scoringsummary AS ss');
      $this->db->join('players AS g','ss.goal_player_id = g.id');
      $this->db->join('teams AS t','g.team_id = t.id');
      $this->db->join('players AS a1','ss.assist1_player_id = a1.id','left');
      $this->db->join('players AS a2','ss.assist2_player_id = a2.id','left');
      $this->db->join('goaltypes AS gt','CONV(ss.goaltype,16,10) = gt.id');
      $this->db->join('(
          SELECT ss.id,ss.goal_player_id,COUNT(num.id) AS goals
          FROM scoringsummary ss
          JOIN scoringsummary num ON ss.goal_player_id = num.goal_player_id AND ss.id > num.id
          GROUP BY ss.id,ss.goal_player_id
          ) AS num'
          ,'ss.id = num.id AND ss.goal_player_id = num.goal_player_id','left');
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

    public function getPlayerStatsByGameID($gameid) {
      $this->db->select("p.pos, p.num, p.firstname, p.lastname,
        s.g,s.a,s.pts,s.sog,s.plusminus,s.chkf,s.chka,s.toi,
        COALESCE(ss.ppg,0) AS ppg,COALESCE(ss.shg,0) AS shg,
        COALESCE(pim.pim,0) AS pim");
      $this->db->from('players AS p');
      $this->db->join("
        (SELECT game_id,player_id,
          sum(goals) as g,
          sum(assists) as a,
          SUM(goals + assists) AS pts,
          sum(sog) as sog,
          sum(plusminus) AS plusminus,
          sum(checksfor) as chkf,
          sum(checksagainst) as chka,
          TIME_FORMAT(SEC_TO_TIME(sum(toi) / SUM(CASE WHEN toi > 0 THEN 1 ELSE 0 END)),'%i:%s') as toi
        FROM playerstats
        GROUP BY game_id,player_id
        HAVING SUM(toi) > 0) AS s",'p.id = s.player_id');
      $this->db->join(
        "(select ss.goal_player_id AS player_id,SUM(CASE WHEN gt.category = 'pp' THEN 1 ELSE 0 END) AS PPG,SUM(CASE WHEN gt.category = 'sh' THEN 1 ELSE 0 END) AS SHG
        from scoringsummary ss
        join goaltypes gt
        on CONV(ss.goaltype,16,10) = gt.id
        group by ss.goal_player_id) AS ss",'p.id = ss.player_id','left'
      );
      $this->db->join(
        "(select ps.player_id, sum(pim.minutes) as pim
        from penaltysummary ps
        join penalties pim
        on ps.penalty_id = pim.id
        group by ps.player_id) as pim",'p.id = pim.player_id','left'
      );
      $this->db->where(array(
          's.game_id' => $gameid,
          'p.pos <>' => 'G'
      ));
      $this->db->order_by('p.num ASC');

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
