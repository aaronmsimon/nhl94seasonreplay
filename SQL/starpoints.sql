SET @goal := 250;
SET @assist := 100;
SET @shot := 10;
SET @shotthresh := 10;
SET @shotbonus := 100;

SET @save := 10;
SET @savepctthresh = .95;
SET @savepctbonus := 200;

SET @shutout := 600; -- Sv% Bonus = 200 + 20 saves = 200, total >= 1000

[TODO]
SET @gwg := 50;
SET @checkvar := 75;

    SELECT *, goalpts + assistpts + shotpts + shotbonus + savepts + svpctbonus + shutout AS starpoints,
    	RANK() OVER(ORDER BY starpoints DESC, toi ASC) AS ThreeStars
    FROM
    (
        SELECT t.abbr, p.num, p.lastname, p.pos,
        	CASE WHEN p.pos <> 'G' THEN ps.goals ELSE NULL END AS goals,
        	CASE WHEN p.pos <> 'G' THEN ps.assists ELSE NULL END AS assists,
        	CASE WHEN p.pos <> 'G' THEN ps.sog ELSE NULL END AS shots,
        	CASE WHEN p.pos = 'G' THEN ps.sog - ps.goals ELSE NULL END AS saves,
        	CASE WHEN p.pos = 'G' THEN (ps.sog - ps.goals) / ps.sog ELSE NULL END AS svpct,
        	ps.toi,
        	CASE WHEN p.pos <> 'G' THEN ps.goals ELSE 0 END * @goal AS goalpts,
        	CASE WHEN p.pos <> 'G' THEN ps.assists ELSE 0 END * @assist AS assistpts,
        	CASE WHEN p.pos <> 'G' THEN ps.sog ELSE 0 END * @shot AS shotpts,
			CASE WHEN p.pos <> 'G' AND ps.sog > @shotthresh THEN @shotbonus ELSE 0 END AS shotbonus,
        	CASE WHEN p.pos = 'G' THEN ps.sog - ps.goals ELSE 0 END * @save AS savepts,
        	CASE WHEN p.pos = 'G' AND (ps.sog - ps.goals) / ps.sog >= @savepctthresh THEN @savepctbonus ELSE 0 END AS svpctbonus,
        	CASE WHEN p.pos = 'G' AND ps.goals = 0 THEN @shutout ELSE 0 END AS shutout
        FROM playerstats ps
        JOIN players p ON ps.player_id = p.id
        JOIN teams t ON p.team_id = t.id
        JOIN games g ON ps.game_id = g.id
        WHERE g.schedule_id = 161
        	AND ps.toi > 0
    ) sp