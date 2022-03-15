<?php

  class Stats_model extends CI_Model {

    public function __construct() {
      parent::__construct();
      $this->load->database();
    }

  public function getLeagueLeaders($orderby, $orderdir, $limit, $limitoffset) {
      $this->db->select("t.abbr, p.id, p.firstname, p.lastname,
        s.gp,s.g,s.a,s.pts,s.sog,s.plusminus,s.chkf,s.chka,s.toi,
        COALESCE(ss.ppg,0) AS ppg,COALESCE(ss.shg,0) AS shg,
        COALESCE(pim.pim,0) AS pim");
      $this->db->from('players AS p');
      $this->db->join('teams AS t','p.team_id = t.id');
      $this->db->join("
        (SELECT player_id,
          SUM(CASE WHEN toi > 0 THEN 1 ELSE 0 END) AS gp,
          sum(goals) as g,
          sum(assists) as a,
          SUM(goals + assists) AS pts,
          sum(sog) as sog,
          sum(plusminus) AS plusminus,
          sum(checksfor) as chkf,
          sum(checksagainst) as chka,
          TIME_FORMAT(SEC_TO_TIME(sum(toi) / SUM(CASE WHEN toi > 0 THEN 1 ELSE 0 END)),'%i:%s') as toi
        FROM playerstats
        GROUP BY player_id
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
      $this->db->where("p.pos <> 'G'");
      $this->db->group_by(array('t.name', 'p.firstname', 'p.lastname'));
      $this->db->order_by("$orderby $orderdir, gp ASC, toi ASC, abbr ASC");
      $this->db->limit($limit,$limitoffset);

      $query = $this->db->get();
      return $query->result();
    }

    public function getPlayerStatsByPlayerID($playerid) {
        $this->db->select("t.city, t.name, t.abbr, p.firstname, p.lastname,
          s.gp,s.g,s.a,s.pts,s.sog,s.plusminus,s.chkf,s.chka,s.toi,
          COALESCE(ss.ppg,0) AS ppg,COALESCE(ss.shg,0) AS shg,
          COALESCE(pim.pim,0) AS pim");
        $this->db->from('players AS p');
        $this->db->join('teams AS t','p.team_id = t.id');
        $this->db->join("
          (SELECT player_id,
            SUM(CASE WHEN toi > 0 THEN 1 ELSE 0 END) AS gp,
            sum(goals) as g,
            sum(assists) as a,
            SUM(goals + assists) AS pts,
            sum(sog) as sog,
            sum(plusminus) AS plusminus,
            sum(checksfor) as chkf,
            sum(checksagainst) as chka,
            TIME_FORMAT(SEC_TO_TIME(sum(toi) / SUM(CASE WHEN toi > 0 THEN 1 ELSE 0 END)),'%i:%s') as toi
          FROM playerstats
          GROUP BY player_id
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
        $this->db->where('p.id',$playerid);

        $query = $this->db->get();
        return $query->row();
      }

      public function getPlayerStatsByGameByPlayerID($playerid) {
          $this->db->select("CASE WHEN s.hometeam_id = p.team_id THEN CONCAT('vs ',a.abbr) ELSE CONCAT('@ ',h.abbr) END AS 'gamedesc',s.id AS scheduleid,
              SUM(CASE WHEN ps.toi > 0 THEN 1 ELSE 0 END) AS gp,
              SUM(ps.goals) as g,
              SUM(ps.assists) as a,
              SUM(ps.goals + ps.assists) AS pts,
              SUM(ps.sog) as sog,
              SUM(ps.plusminus) AS plusminus,
              SUM(ps.checksfor) as chkf,
              SUM(ps.checksagainst) as chka,
              TIME_FORMAT(SEC_TO_TIME(sum(ps.toi) / SUM(CASE WHEN ps.toi > 0 THEN 1 ELSE 0 END)),'%i:%s') as toi,
              COALESCE(SUM(st.ppg),0) AS ppg,
              COALESCE(SUM(st.shg),0) AS shg,
              COALESCE(SUM(pim.pim),0) AS pim");
          $this->db->from('playerstats as ps');
          $this->db->join('games as g','ps.game_id = g.id');
          $this->db->join('schedule as s','g.schedule_id = s.id');
          $this->db->join('players as p','ps.player_id = p.id');
          $this->db->join('teams as h','s.hometeam_id = h.id');
          $this->db->join('teams as a','s.awayteam_id = a.id');
          $this->db->join("(
            select ss.goal_player_id AS player_id,ss.schedule_id,SUM(CASE WHEN gt.category = 'pp' THEN 1 ELSE 0 END) AS PPG,SUM(CASE WHEN gt.category = 'sh' THEN 1 ELSE 0 END) AS SHG
            from scoringsummary ss
            join goaltypes gt on CONV(ss.goaltype,16,10) = gt.id
            group by ss.goal_player_id,ss.schedule_id
          ) AS st",'p.id = st.player_id AND s.id = st.schedule_id','left');
          $this->db->join("(
            select ps.player_id,ps.schedule_id,sum(pim.minutes) as pim
            from penaltysummary ps
            join penalties pim
            on ps.penalty_id = pim.id
            group by ps.player_id,ps.schedule_id
          ) as pim",'p.id = pim.player_id AND s.id = pim.schedule_id','left');
          $this->db->where('p.id',$playerid);
          $this->db->group_by('s.id');

          $query = $this->db->get();
          return $query->result();
        }

        public function getGoalieStatsByPlayerID($playerid) {
          $this->db->select("t.city, t.name, t.abbr, p.firstname, p.lastname,
            s.gp,s.ga,s.sa,s.toi,s.soi,
            COALESCE(d.w,0) AS w,COALESCE(d.l,0) AS l,COALESCE(d.t,0) AS t,
            COALESCE(so.so,0) AS so");
          $this->db->from('players AS p');
          $this->db->join('teams AS t','p.team_id = t.id');
          $this->db->join("
            (SELECT player_id,
              SUM(CASE WHEN toi > 0 THEN 1 ELSE 0 END) AS gp,
              sum(goals) as ga,
              sum(sog) as sa,
              TIME_FORMAT(SEC_TO_TIME(sum(toi) / SUM(CASE WHEN toi > 0 THEN 1 ELSE 0 END)),'%i:%s') as toi,
              sum(toi) AS soi
            FROM playerstats
            GROUP BY player_id
            HAVING SUM(toi) > 0) AS s",'p.id = s.player_id');
          $this->db->join(
            "(select player_id,sum(wins) as w,sum(losses) as l,sum(ties) as t from (
              select p.id as player_id,
              case when r.GameResult = 'W' and max(ps.toi) over(partition by g.id) = ps.toi THEN 1 else 0 end as wins,
              case when r.GameResult = 'L' and max(ps.toi) over(partition by g.id) = ps.toi  THEN 1 else 0 end as losses,
              case when r.GameResult = 'T' and max(ps.toi) over(partition by g.id) = ps.toi  THEN 1 else 0 end as ties
              from players p
              join playerstats ps on p.id = ps.player_id
              join games g on ps.game_id = g.id
              join results r on g.schedule_id = r.schedule_id and p.team_id = r.team_id
              where ps.toi > 0
              ) d
              group by player_id) AS d",'p.id = d.player_id','left'
          );
          $this->db->join(
            "(select player_id, count(*) as so from playerstats where goals = 0 and toi > 0 group by player_id) as so",
            'p.id = so.player_id','left'
          );
          $this->db->where('p.id',$playerid);
  
          $query = $this->db->get();
          return $query->row();
        }
  
        public function getGoalieStatsByGameByPlayerID($playerid) {
          $this->db->select("CASE WHEN s.hometeam_id = p.team_id THEN CONCAT('vs ',a.abbr) ELSE CONCAT('@ ',h.abbr) END AS 'gamedesc',s.id AS scheduleid,
            SUM(CASE WHEN ps.toi > 0 THEN 1 ELSE 0 END) AS gp,
            sum(ps.goals) as ga,
            sum(ps.sog) as sa,
            TIME_FORMAT(SEC_TO_TIME(sum(ps.toi) / SUM(CASE WHEN ps.toi > 0 THEN 1 ELSE 0 END)),'%i:%s') as toi,
            sum(ps.toi) AS soi,
            COALESCE(d.w,0) AS w,COALESCE(d.l,0) AS l,COALESCE(d.t,0) AS t,
            COALESCE(so.so,0) AS so");
          $this->db->from('playerstats as ps');
          $this->db->join('games as g','ps.game_id = g.id');
          $this->db->join('schedule as s','g.schedule_id = s.id');
          $this->db->join('players as p','ps.player_id = p.id');
          $this->db->join('teams as h','s.hometeam_id = h.id');
          $this->db->join('teams as a','s.awayteam_id = a.id');
          $this->db->join("(
            select game_id,player_id,sum(wins) as w,sum(losses) as l,sum(ties) as t 
            from (
              select ps.game_id,p.id AS player_id,
                case when r.GameResult = 'W' and max(ps.toi) over(partition by g.id) = ps.toi THEN 1 else 0 end as wins,
                case when r.GameResult = 'L' and max(ps.toi) over(partition by g.id) = ps.toi  THEN 1 else 0 end as losses,
                case when r.GameResult = 'T' and max(ps.toi) over(partition by g.id) = ps.toi  THEN 1 else 0 end as ties
              from players p
              join playerstats ps on p.id = ps.player_id
              join games g on ps.game_id = g.id
              join results r on g.schedule_id = r.schedule_id and p.team_id = r.team_id
              where ps.toi > 0
            ) d
            group by game_id,player_id
          ) AS d",'g.id = d.game_id AND p.id = d.player_id','left');
          $this->db->join(
            "(select game_id,player_id, count(*) as so from playerstats where goals = 0 and toi > 0 group by game_id,player_id) as so",
            'g.id = so.game_id AND p.id = so.player_id','left'
          );
          $this->db->where('p.id',$playerid);
          $this->db->group_by('s.id');

          $query = $this->db->get();
          return $query->result();
        }

      public function getTeamStats($teamid) {
        $this->db->select('t.name,pp.pct AS PPpct,
          CONCAT(pp.rank,CASE
            WHEN pp.rank%100 BETWEEN 11 AND 13 THEN "th"
            WHEN pp.rank%10 = 1 THEN "st"
            WHEN pp.rank%10 = 2 THEN "nd"
            WHEN pp.rank%10 = 3 THEN "rd"
            ELSE "th"
            END) AS PPrank,pk.pct AS PKpct,
          CONCAT(pk.rank,CASE
            WHEN pk.rank%100 BETWEEN 11 AND 13 THEN "th"
            WHEN pk.rank%10 = 1 THEN "st"
            WHEN pk.rank%10 = 2 THEN "nd"
            WHEN pk.rank%10 = 3 THEN "rd"
            ELSE "th"
            END) AS PKrank
        ');
        $this->db->from('teams as t');
        $this->db->join("(
          SELECT team_id, ROUND(SUM(ppgoals) / SUM(ppattempts),3) AS pct, RANK() OVER (ORDER BY pct DESC) AS 'rank'
        	FROM games
        	GROUP BY team_id
        ) AS pp",'t.id = pp.team_id');
        $this->db->join("(
        	SELECT t.id, ROUND((SUM(g.ppattempts) - SUM(g.ppgoals)) / SUM(g.ppattempts),3) AS pct, RANK() OVER (ORDER BY pct DESC) AS 'rank'
        	FROM games g
        	JOIN (SELECT g.schedule_id, g.team_id FROM games g JOIN teams t ON g.team_id = t.id) f ON g.schedule_id = f.schedule_id
        	JOIN teams t ON f.team_id = t.id
        	WHERE g.team_id <> f.team_id
        	GROUP BY t.id
        ) AS pk",'t.id = pk.id');
        $this->db->where('t.id',$teamid);
        $query = $this->db->get();
        return $query->row();
      }

  }
?>
