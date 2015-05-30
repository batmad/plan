<?php
class mDate{
	private $date;

	function __construct(){
		$this->date = date('N');
	}

	function createArrayWeek($day = 0){
		$array_week = array();
		
		$j = 0;
		while(date('N',strtotime("-$j day")) > 1){
			$j++;
		}

		
		for ($i=0;$i<6;$i++){
			$nw = $i + $day - $j;
			array_push($array_week,date('d-m-Y',strtotime("+$nw day")));
		}
		
		return $array_week;
	} 

	function reverseDate($date){

		$dates = explode('-',$date);
		$result = $dates[2]."-".$dates[1]."-".$dates[0];

		return $result;
	}

	function datetime($date,$time){

		$date = $this->reverseDate($date);
		return $date." ".$time.":00";
	}

	function dateBegin($date){
		$date = $this->reverseDate($date);
		return $date." 00:00:00";
	}

	function dateEnd($date){
		$date = $this->reverseDate($date);
		return $date." 23:59:59";
	}

	function monthRus($month){
		$arr = array(1=>"Январь",2=>"Февраль",3=>"Март",4=>"Апрель",5=>"Май",6=>"Июнь",7=>"Июль",8=>"Август",9=>"Сентябрь",10=>"Октябрь",11=>"Ноябрь",12=>"Декабрь");
		return $arr[$month];
	}

}

?>

