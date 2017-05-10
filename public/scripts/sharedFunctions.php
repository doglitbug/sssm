<?php

/** Check to see if there is a logged in user
 * @return boolean True if a user is logged in
 */

function loggedIn() {
    return (isset($_SESSION['user_id']));
}

/** Check to see if the current user is a manager
 * @return boolean True if user is a manager
 */

function isManager() {
    return (isset($_SESSION['manager']) && $_SESSION['manager'] == '1');
}

/**
 * Find the monday(default start) of the week in which the given date falls
 * http://stackoverflow.com/questions/11771062/when-a-date-is-given-how-to-get-the-date-of-monday-of-that-week-in-php
 * @param $date Date of the given week
 * @returns strtotime Closest proceeding Monday, if not already a Monday
 * */
function getMondayOfWeek($date) {
    if (!is_numeric($date)) {
        $date = strtotime($date);
    }
    if (date('w', $date) == 1) {
        return $date;
    } else {
        return strtotime('last monday', $date);
    }
}

?>