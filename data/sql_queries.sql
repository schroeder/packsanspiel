
--  Get all teams with number of members.
SELECT team.id, member.grade, count(*) FROM member LEFT JOIN team ON team.id=member.team_id WHERE member.grade IN ('w','j','p','r') GROUP BY team.id, member.grade ORDER by member.grade;

--  Get all members by town and group
SELECT member.village, member.town, member.grade, count(*) FROM member LEFT JOIN team ON team.id=member.team_id WHERE member.grade IN ('w','j','p','r') GROUP BY member.village, member.town, member.grade;