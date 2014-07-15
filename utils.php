<?php

function CleanInput($str, $conn)
{
    $str = $conn->real_escape_string($str);

    return $str;
}

?>