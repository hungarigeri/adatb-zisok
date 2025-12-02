<?php
function formatdate($date){
    $date = str_replace('.', '', $date);
    $date = str_replace(' ', '', $date);
    list($year, $month, $day) = sscanf($date, "%d-%[^-]-%d");
    if ($year >= 0 && $year <= 25) {
        $year = 2000 + $year;
    } else {
        $year = 1900 + $year;
    }
    $formatted_date = sprintf("%d. %s. %d.", $year, $month, $day);
    return $formatted_date;
}
?>


