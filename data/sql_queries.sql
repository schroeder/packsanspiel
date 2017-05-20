
--  Get all teams with number of members.
SELECT team.id, member.grade, count(*) FROM member LEFT JOIN team ON team.id=member.team_id WHERE member.grade IN ('w','j','p','r') GROUP BY team.id, member.grade ORDER by member.grade;

--  Get all members by town and group
SELECT member.village, member.town, member.grade, count(*) FROM member LEFT JOIN team ON team.id=member.team_id WHERE member.grade IN ('w','j','p','r') GROUP BY member.village, member.town, member.grade;

-- Get all active games with teams
SELECT game.game.identifier, game.name, team.id, team.grade, game.duration AS planned_duration, UNIX_TIMESTAMP()-team_level_game.start_time AS game_duration, UNIX_TIMESTAMP()-team_levgame.description game.duration    game.game_answer game.grade       game.id          game.identifier  game.level_id    game.location_id game.name        game.passcode    game.status     N temysql> SELECT game.level_id, game.identifier, game.name, team.id, team.grade, game.duration AS planned_duration, UNIX_TIMESTAMP()-team_level_game.start_time AS game_duration, UNIX_TIMESTAMP()-team_level.start_time AS level_duration  FROM game LEFT JOIN team_level_game ON game.id=team_level_game.assigned_game LEFT JOIN team_level ON team_level_game.team_level_id=team_level.id LEFT JOIN team ON team_level.team_id=team.id WHERE team.id IS NOT NULL;