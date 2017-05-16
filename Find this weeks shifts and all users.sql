DROP table IF EXISTS thisWeeksShifts;
DROP table IF EXISTS thisWeeksShifts2;

CREATE temporary TABLE IF NOT EXISTS thisWeeksShifts AS (
SELECT user_id, username, start_date, start_time, end_time, description FROM tbl_roster LEFT JOIN tbl_user USING (user_id)
WHERE start_date>='2017-05-15' AND start_date<='2017-05-21'
);

CREATE temporary TABLE IF NOT EXISTS thisWeeksShifts2 AS (
SELECT user_id, start_date, start_time, end_time, description FROM tbl_roster
WHERE start_date>='2017-05-15' AND start_date<='2017-05-21'
);

SELECT * FROM thisWeeksShifts
UNION
SELECT user_id, tbl_user.username, start_date, start_time, end_time, description FROM thisWeeksShifts2 RIGHT JOIN tbl_user USING (user_id)
WHERE thisWeeksShifts2.user_id IS NULL

ORDER BY user_id, start_date, start_time