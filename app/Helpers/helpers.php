<?php

    function convert_date($value) {
        return date('d/m/Y | H:i:s', strtotime($value));
    }

    function convert_late($value) {
        $datetime1 = new DateTime();
        $datetime2 = new DateTime($value); //convert data ke dalam objek
        $interval = $datetime1->diff($datetime2);
        return $value = $interval->format('%a');
    }

?>
