<?php   
function relativeTime(DateTime $date): string {
    $currentDate = new DateTime();
    $interval = $currentDate->diff($date);
    if ($interval->y > 0) {
        $relativeTime = $interval->y . " years ago";
    } else if ($interval->m > 0) {
        $relativeTime = $interval->m . " months ago";
    } else if ($interval->d > 0) {
        $relativeTime = $interval->d . " days ago";
    } else if ($interval->h > 0) {
        $relativeTime = $interval->h . " hours ago";
    } else if ($interval->i > 0) {
        $relativeTime = $interval->i . " minutes ago";
    } else {
        $relativeTime = "Just now";
    }
    return $relativeTime;
}