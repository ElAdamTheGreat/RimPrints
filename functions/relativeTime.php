<?php   
function relativeTime(string $dateString): string {
    $currentDate = new DateTime();
    $date = new DateTime($dateString);
    $interval = $currentDate->diff($date);
    if ($interval->y > 0) {
        if ($interval->y == 1) {
            return "a year ago";
        } else {
            return $interval->y . " years ago";
        }
    } else if ($interval->m > 0) {
        if ($interval->m == 1) {
            return "a month ago";
        } else {
            return $interval->m . " months ago";
        }
    } else if ($interval->d > 0) {
        if ($interval->d == 1) {
            return "a day ago";
        } else {
            return $interval->d . " days ago";
        }
    } else if ($interval->h > 0) {
        if ($interval->h == 1) {
            return "an hour ago";
        } else {
            return $interval->h . " hours ago";
        }
    } else if ($interval->i > 0) {
        if ($interval->i == 1) {
            return "a minute ago";
        } else {
            return $interval->i . " minutes ago";
        }
    } else {
        $relativeTime = "Just now";
    }
    return $relativeTime;
}