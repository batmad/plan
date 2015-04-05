<?php
// Include the PHPWord.php, all other classes were loaded by an autoloader
include($_SERVER['DOCUMENT_ROOT'].'bd.php');
//include('checkauth.php');
require_once 'PHPWord.php';

if (isset($_GET['nextweek']) && !empty($_GET['nextweek']) && $_GET['nextweek']=="yes"){
	include('date_nw.php');
	$nextweek='yes';
}
else if (isset($_GET['old']) && !empty($_GET['old'])&& $_GET['old']=="yes"){
	$array_week = array();
	foreach($_GET['old_plan'] as $old_plan){
		array_push($array_week,$old_plan);
	}
}
else{
	include('date.php');
	$nextweek='no';
}



$query_names = "SELECT name,id FROM name WHERE `show_plan`='1' ORDER BY weight";
$result = $mysqli->query($query_names);
while ($row = $result->fetch_assoc()){
	$rows[] = $row;
}

$plan = array();
$ruks = array();
foreach ($rows as $row){
	array_push($ruks,$row['name']);
	foreach($array_week as $day){
		$query_todos = "SELECT descr,id FROM todo WHERE id_name=".$row['id']." AND `date`='$day'";
		$result2 = $mysqli->query($query_todos) or trigger_error($mysqli->error."[$query_todos]");
		$row2 = $result2->fetch_assoc();
		$plan[$row['name']][$day]['descr']=nl2br($row2['descr']);
		$plan[$row['name']][$day]['id'] = $row2['id']; 
		$plan[$row['name']][$day]['id_name'] = $row['id'];
		$plan[$row['name']][$day]['date'] = $day;
	}
}



// Create a new PHPWord Object
$PHPWord = new PHPWord();

// Every element you want to append to the word document is placed in a section. So you need a section:
$sectionStyle = array('orientation' => 'landscape');
$section = $PHPWord->createSection($sectionStyle);
$fontStyleTh = array('name'=>'Times New Roman', 'bold'=>true);
$fontStyleTd = array('name'=>'Times New Roman',);
$PHPWord->addFontStyle('myOwnStyle', $fontStyleTh);
$PHPWord->addFontStyle('myTdStyle', $fontStyleTd);

// You can directly style your text by giving the addText function an array:
$section->addText('План работы Министерства жилищно-коммунального хозяйства и энергетики Республики Саха (Якутия)', array('name'=>'Times New Roman', 'size'=>12, 'bold'=>true));

$cellStyle = array('valign'=>'center');
$styleTable = array('borderSize' => 6);
$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $cellStyle);

$table = $section->addTable('myOwnTableStyle');
$table->addRow();
$cell = $table->addCell(2000,$cellStyle);
$cell->addText('Ф.И.О.','myOwnStyle');
$cell = $table->addCell(2000, $cellStyle);
$cell->addText("Понедельник",'myOwnStyle');
$cell->addText("$array_week[0]",'myOwnStyle');
$cell = $table->addCell(2000, $cellStyle);
$cell->addText("Вторник",'myOwnStyle');
$cell->addText("$array_week[1]",'myOwnStyle');
$cell = $table->addCell(2000, $cellStyle);
$cell->addText("Среда",'myOwnStyle');
$cell->addText("$array_week[2]",'myOwnStyle');
$cell = $table->addCell(2000, $cellStyle);
$cell->addText("Четверг",'myOwnStyle');
$cell->addText("$array_week[3]",'myOwnStyle');
$cell = $table->addCell(2000, $cellStyle);
$cell->addText("Пятница",'myOwnStyle');
$cell->addText("$array_week[4]",'myOwnStyle');
$cell = $table->addCell(2000, $cellStyle);
$cell->addText("Суббота",'myOwnStyle');
$cell->addText("$array_week[5]",'myOwnStyle');

foreach ($ruks as $ruk){
	$table->addRow();
	$cell = $table->addCell(2000);
	$cell->addText("$ruk",'myTdStyle');
	foreach ($plan[$ruk] as $key => $todo){
			$cell = $table->addCell(2000);
			$temp = explode('<br />',$todo['descr']);
			foreach ($temp as $todo){
				$cell->addText($todo,'myTdStyle');
			}
	}
}




// At least write the document to webspace:
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
//$objWriter->save('/home/samba/tranzit/helloWorld.docx');
$objWriter->save($_SERVER['DOCUMENT_ROOT'].'/admin/download/plan.docx');

header("Location:".$_SERVER[DOCUMENT_ROOT]."/admin/download/plan.docx");
?>