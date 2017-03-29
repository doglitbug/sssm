SELECT * FROM sssm.tbl_schedule
WHERE user_id='1'
AND start_date<='2017-04-02'
AND (occurrences=0 OR (DATE_ADD(start_date, INTERVAL ((occurrences-1)*7) DAY)>='2017-03-27'))
ORDER BY (case DAYOFWEEK(start_date) WHEN 1 THEN 8 else DAYOFWEEK(start_date) END)