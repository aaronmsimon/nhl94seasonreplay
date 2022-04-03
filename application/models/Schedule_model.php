<?php

  class Schedule_model extends CI_Model {

    protected $filename = 'www/currentgame.txt';

    public function __construct() {
      parent::__construct();
      $this->load->database();
    }

    public function getCurrentDate() {
        $this->db->select('s.gamedate');
        $this->db->distinct();
        $this->db->from('schedule AS s');
        $this->db->join('games AS g','s.id = g.schedule_id','left');
        $this->db->where('g.schedule_id is null');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    public function getNextGame($numgames) {
      $this->db->select('s.id, s.gamedate, CONCAT("(",COALESCE(ra.wins,0),"-",COALESCE(ra.losses,0),"-",COALESCE(ra.ties,0),") ",a.abbr," at ",h.abbr," (",COALESCE(rh.wins,0),"-",COALESCE(rh.losses,0),"-",COALESCE(rh.ties,0),")") AS gamedesc');
      $this->db->from('schedule AS s');
      $this->db->join('teams AS h','s.hometeam_id = h.id');
      $this->db->join('teams AS a','s.awayteam_id = a.id');
      $this->db->join('(SELECT DISTINCT schedule_id FROM games) AS g','s.id = g.schedule_id','left');
      $this->db->join('(select g.team_id,sum(case when d.tieflag = 0 and g.goals = d.maxgoals then 1 else 0 end) as wins,sum(case when d.tieflag = 0 and g.goals <> d.maxgoals then 1 else 0 end) as losses,sum(case when d.tieflag = 1 then 1 else 0 end) as ties
          from games g
          join teams t on g.team_id = t.id
          join (
            select schedule_id,max(goals) as maxgoals, min(goals) as mingoals, case when max(goals) = min(goals) then 1 else 0 end as tieflag
            from games g
            group by schedule_id
          ) d on g.schedule_id = d.schedule_id
          group by g.team_id) as rh','h.id = rh.team_id','left');
      $this->db->join('(select g.team_id,sum(case when d.tieflag = 0 and g.goals = d.maxgoals then 1 else 0 end) as wins,sum(case when d.tieflag = 0 and g.goals <> d.maxgoals then 1 else 0 end) as losses,sum(case when d.tieflag = 1 then 1 else 0 end) as ties
          from games g
          join teams t on g.team_id = t.id
          join (
            select schedule_id,max(goals) as maxgoals, min(goals) as mingoals, case when max(goals) = min(goals) then 1 else 0 end as tieflag
            from games g
            group by schedule_id
          ) d on g.schedule_id = d.schedule_id
          group by g.team_id) as ra','a.id = ra.team_id','left');
      $this->db->where(array('g.schedule_id IS NULL' => null));
      $this->db->order_by('s.id ASC');
      $this->db->limit($numgames);
      $query = $this->db->get();
      return $query->result();
    }

    public function getNextGames($dayCount, $currentDate) {
        $gamesbydate = array();
        for ($i = 0; $i < $dayCount; $i++) {
            $date["gamedate"] = date_format(date_add(date_create($currentDate),date_interval_create_from_date_string("$i days")),'Y-m-d');
            $date["games"]['unplayed'] = $this->getGamesByDate($date["gamedate"]);
            $date["games"]['played'] = $this->getGamesByDate($date["gamedate"],true);
            array_push($gamesbydate,$date);
        }
        return $gamesbydate;
    }

    public function getGamesByDate($gameDate,$completed = false) {
        $this->db->select('s.id, h.abbr AS homelogo, h.city AS home, gh.goals AS homegoals, a.abbr AS awaylogo, a.city AS away, ga.goals AS awaygoals, CONCAT(sp.firstname," ",sp.lastname) AS topplayer');
        $this->db->from('schedule AS s');
        $this->db->join('teams AS h','s.hometeam_id = h.id');
        $this->db->join('teams AS a','s.awayteam_id = a.id');
        $this->db->join('games AS gh','s.id = gh.schedule_id AND gh.team_id = h.id','left');
        $this->db->join('games AS ga','s.id = ga.schedule_id AND ga.team_id = a.id','left');
        $this->db->join('(SELECT schedule_id, firstname, lastname FROM starpoints WHERE starrank = 1) AS sp','s.id = sp.schedule_id','left');
        $where = array('s.gamedate' => $gameDate);
        if (!$completed) {
          $where['gh.goals IS NULL'] = null;
        } else {
          $where['gh.goals IS NOT NULL'] = null;
        }
        $this->db->where($where);
        $this->db->order_by('s.id ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getCurrentGame() {
      $myfile = fopen($this->filename,'r') or die('Unable to open file');
      $gamenumber = fread($myfile,filesize($this->filename));
      fclose($myfile);
      return $gamenumber;
    }

    public function getGameStatus($gameid) {
      if ($gameid > 0) {
        $this->db->select('CASE WHEN g.schedule_id IS NULL THEN 0 ELSE s.id END AS gamestatus',false);
        $this->db->from('schedule AS s');
        $this->db->join('(SELECT DISTINCT schedule_id FROM games) AS g','s.id = g.schedule_id','left');
        $this->db->where('s.id',$gameid);

        $query = $this->db->get();
        return $query->row()->gamestatus;
      } else {
        return $gameid;
      }
    }

    public function logGame($gameid) {
      file_put_contents($this->filename, $gameid);
      log_message('info',"Game ID logged: $gameid");
    }

    public function getSchedule($teamabbr = null) {
      $this->db->select('s.id, s.gamedate, CONCAT(a.abbr," at ",h.abbr) AS gamedesc,gh.goals AS homegoals,ga.goals AS awaygoals,
        CASE WHEN g.schedule_id IS NOT NULL THEN 1 ELSE 0 END AS completed',false);
      $this->db->from('schedule as s');
      $this->db->join('teams as h','s.hometeam_id = h.id');
      $this->db->join('teams as a','s.awayteam_id = a.id');
      $this->db->join('(SELECT DISTINCT schedule_id FROM games) AS g','s.id = g.schedule_id','left');
      $this->db->join('games as gh','s.hometeam_id = gh.team_id AND s.id = gh.schedule_id','left');
      $this->db->join('games as ga','s.awayteam_id = ga.team_id AND s.id = ga.schedule_id','left');
      if ($teamabbr != null) {
        $this->db->or_where(array(
          'h.abbr' => $teamabbr,
          'a.abbr' => $teamabbr
        ));
      }
      $this->db->order_by('s.id ASC');

      $query = $this->db->get();
      return $query->result();
    }

    public function getTeams($nhlonly) {
      $this->db->select('t.*');
      $this->db->from('teams t');
      $this->db->join('divisions d','t.division_id = d.id');
      $this->db->join('conferences c','d.conference_id = c.id');
      $this->db->where('c.nhlflag',$nhlonly);
      $this->db->order_by('t.city ASC');
      $query = $this->db->get();
      return $query->result();
    }

    public function getScheduleByID($scheduleid) {
      $this->db->select('s.*, h.abbr as homeabbr, a.abbr as awayabbr');
      $this->db->from('schedule as s');
      $this->db->join('teams as h','s.hometeam_id = h.id');
      $this->db->join('teams as a','s.awayteam_id = a.id');
      $this->db->where('s.id',$scheduleid);

      $query = $this->db->get();
      return $query->row();
    }

    public function getGoaliesByScheduleID($scheduleid) {
      // query home team
      $this->db->select('p.team_id, p.firstname, p.lastname, gp.gp / tot.totalgp as pctgp',FALSE);
      $this->db->from('schedule as s');
      $this->db->join('players as p','s.hometeam_id = p.team_id');
      $this->db->join('games_played as gp','p.id = gp.playerid');
      $this->db->join('(select s.id,sum(gp.gp) as totalgp from schedule s join players p on s.hometeam_id = p.team_id join games_played gp on p.id = gp.playerid group by s.id) as tot','s.id = tot.id');
      $this->db->where(array(
        's.id' => $scheduleid,
        'p.pos' => 'G'
      ));
      $query = $this->db->get();

      // set goalie array
      $goalies = array();

      // randomize
      $frompct = 0;
      $topct = 0;
      $rand = rand(1,100);

      // choose a goalie
      foreach ($query->result() as $key=>$goalie) {
        $topct = $frompct + round($goalie->pctgp * 100);
        if ($rand > $frompct && $rand <= $topct) {
          array_push($goalies,$key);
          log_message('info','For schedule ID ' . $scheduleid . ', random number (' . $rand . ') home goalie selected: ' . $goalie->firstname . ' ' . $goalie->lastname . ' (' . $frompct . '-' . $topct . ') index = ' . $key);
        }
        $frompct = $topct;
      }

      // query away team
      $this->db->select('p.team_id, p.firstname, p.lastname, gp.gp / tot.totalgp as pctgp',FALSE);
      $this->db->from('schedule as s');
      $this->db->join('players as p','s.awayteam_id = p.team_id');
      $this->db->join('games_played as gp','p.id = gp.playerid');
      $this->db->join('(select s.id,sum(gp.gp) as totalgp from schedule s join players p on s.awayteam_id = p.team_id join games_played gp on p.id = gp.playerid group by s.id) as tot','s.id = tot.id');
      $this->db->where(array(
        's.id' => $scheduleid,
        'p.pos' => 'G'
      ));
      $query = $this->db->get();

      // randomize
      $frompct = 0;
      $topct = 0;
      $rand = rand(1,100);

      // choose a goalie
      foreach ($query->result() as $key=>$goalie) {
        $topct = $frompct + round($goalie->pctgp * 100);
        if ($rand > $frompct && $rand <= $topct) {
          array_push($goalies,$key);
          log_message('info','For schedule ID ' . $scheduleid . ', random number (' . $rand . ') away goalie selected: ' . $goalie->firstname . ' ' . $goalie->lastname . ' (' . $frompct . '-' . $topct . ') index = ' . $key);
        }
        $frompct = $topct;
      }

      return $goalies;
    }

  }
?>
