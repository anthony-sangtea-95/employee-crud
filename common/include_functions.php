<?php
function changeToYmdFormat($dateString)
{
   $dateObj       = DateTime::createFromFormat("m/d/Y", $dateString);
   $formattedDate = $dateObj->format("Y-m-d");
   return $formattedDate;
}

function changeTomdYFormat($dateString)
{
   $dateObj       = DateTime::createFromFormat("Y-m-d", $dateString);
   $formattedDate = $dateObj->format("m/d/Y");
   return $formattedDate;
}
