SELECT * FROM sssm.tbl_schedule
WHERE start_date<='2017-03-26' AND
(occurrences=0 OR (DATE_ADD(start_date, INTERVAL ((occurrences-1)*7) DAY)>='2017-03-20'));