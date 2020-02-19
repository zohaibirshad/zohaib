<?php

/**
* returns a 13 digit code based on timestamp
*
* @return int
*/
function unique_code()
{
    $milliseconds = (String) round(microtime(true) * 568);
    $shuffled = str_shuffle($milliseconds);
    $id = substr($shuffled, 0, 13);
    return $id;
}