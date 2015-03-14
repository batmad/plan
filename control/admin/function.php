<?php
header('Content-type: text/html; charset=utf-8');

function correct_date($d){
	$incorrect = array("/","\\",".","_","|",",");
	$date = str_replace($incorrect,"-",$d);
	$dates = explode('-',$date);
	$rdate = $dates[2]."-".$dates[1]."-".$dates[0];
	return $rdate;
}
