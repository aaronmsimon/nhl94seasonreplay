CREATE VIEW starpoints AS (
    SELECT *, goalpts + assistpts + shotpts + shotbonus + savepts + svpctbonus + shutout AS starpoints,
    	RANK() OVER(PARTITION BY schedule_id ORDER BY starpoints DESC, toi ASC) AS starrank
    FROM
    (
        SELECT g.schedule_id, g.id AS game_id, t.id AS team_id, t.abbr, p.id AS player_id, p.num, p.firstname, p.lastname, p.pos,
        	CASE WHEN p.pos <> 'G' THEN ps.goals ELSE NULL END AS goals,
        	CASE WHEN p.pos <> 'G' THEN ps.assists ELSE NULL END AS assists,
        	CASE WHEN p.pos <> 'G' THEN ps.sog ELSE NULL END AS shots,
        	CASE WHEN p.pos = 'G' THEN ps.sog - ps.goals ELSE NULL END AS saves,
        	CASE WHEN p.pos = 'G' THEN (ps.sog - ps.goals) / ps.sog ELSE NULL END AS svpct,
        	ps.toi,
        	CASE WHEN p.pos <> 'G' THEN ps.goals ELSE 0 END * 250 AS goalpts,
        	CASE WHEN p.pos <> 'G' THEN ps.assists ELSE 0 END * 100 AS assistpts,
        	CASE WHEN p.pos <> 'G' THEN ps.sog ELSE 0 END * 10 AS shotpts,
			CASE WHEN p.pos <> 'G' AND ps.sog >= 10 THEN 100 ELSE 0 END AS shotbonus,
        	CASE WHEN p.pos = 'G' THEN ps.sog - ps.goals ELSE 0 END * 10 AS savepts,
        	CASE WHEN p.pos = 'G' AND (ps.sog - ps.goals) / ps.sog >= .95 THEN 200 ELSE 0 END AS svpctbonus,
        	CASE WHEN p.pos = 'G' AND ps.goals = 0 THEN 600 ELSE 0 END AS shutout
        FROM playerstats ps
        JOIN players p ON ps.player_id = p.id
        JOIN teams t ON p.team_id = t.id
        JOIN games g ON ps.game_id = g.id
        WHERE ps.toi > 0
    ) sp
)