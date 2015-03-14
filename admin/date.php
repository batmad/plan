<?php
$date = date('N');
$array_week = array();
if ($date == 1){ 
	for ($i=0;$i<6;$i++){
		//echo date('N',strtotime("+".$i." day"));
		array_push($array_week,date('d-m-Y',strtotime("+".$i." day")));
	}
}
elseif ($date == 7){
	for ($i=6;$i>0;$i--){
		array_push($array_week,date('d-m-Y',strtotime("-$i day")));
	}
}
else {
	$i = 1;
	while(date('N',strtotime("-$i day"))!= 1){
		$i++;
	}
	$j = 6 - $i;
	

	
	while ($i!= 0){
		array_push($array_week,date('d-m-Y',strtotime("-$i day")));
		$i--;
	}
	for($i=0;$i<$j;$i++){
		array_push($array_week,date('d-m-Y',strtotime("+$i day")));
	}
}



?>

