<?php
$date = date('N');
$array_week = array();
if ($date == 1){ 
	for ($i=0;$i<6;$i++){
		//echo date('N',strtotime("+".$i." day"));
		$nw = $i + 7;
		array_push($array_week,date('d-m-Y',strtotime("+".$nw." day")));
	}
}
elseif ($date == 7){
	for ($i=6;$i>0;$i--){
		$nw = $i - 7;
		array_push($array_week,date('d-m-Y',strtotime("-$nw day")));
	}
}
else {
	$i = 1;
	while(date('N',strtotime("-$i day"))!= 1){
		$i++;
	}
	$j = 6 - $i;
	

	
	while ($i!= 0){
		$nw = $i-7;
		array_push($array_week,date('d-m-Y',strtotime("-$nw day")));
		$i--;
	}
	for($i=0;$i<$j;$i++){
		$nw = $i + 7;
		array_push($array_week,date('d-m-Y',strtotime("+$nw day")));
	}
}



?>

