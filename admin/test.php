<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
//include('checkauth.php');
echo password_hash('cnfhr11',PASSWORD_DEFAULT);
	
$year = date('Y');
$week_number = 0;
$week = array();
if ($year % 4 == 0 && $year % 100 != 0 || $year % 400 == 0){
	$months = array(1=>31,2=>29,3=>31,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30,12=>31);
}
else {
	$months = array(1=>31,2=>28,3=>31,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30,12=>31);
}


foreach($months as $month=>$day){
	$i = 1;
	while($i <=$day){
		if (date('N',mktime(1,1,1,$month,$i,$year))==7){
			$week_number = date('W',mktime(1,1,1,$month,$i,$year));
			if($week_number<=date('W')){
				$week[$week_number]['start']=date('d-m-Y',mktime(1,1,1,$month,$i-6,$year));
				$week[$week_number]['end']=date('d-m-Y',mktime(1,1,1,$month,$i,$year));
				$week[$week_number]['month']=$month;
			}
			else{
				break;
			}
		}
		$i++;
	}
}
ksort($week);


echo "<br/>";
foreach($week as $w){
	echo "План работы от ".$w['start']." по ".$w['end']."<br/>";
}

$mysqli->close();
?>

