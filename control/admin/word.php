<?php
// Include the PHPWord.php, all other classes were loaded by an autoloader
include($_SERVER['DOCUMENT_ROOT'].'bd.php');
include('function.php');
//include('checkauth.php');
if (isset($_POST) && !empty($_POST)){
	require_once $_SERVER['DOCUMENT_ROOT']."/admin/PHPWord.php";
	$date1 = correct_date($_POST['date1']);
	$date2 = correct_date($_POST['date2']);
	$month = date('m');
	$year = date('Y');
	$day = date('t');
	if ($date1 == "" && $date2 == ""){
		$query= "SELECT `c`.`id`,
					`c`.`descr`,
					`c`.`performed`,
					`c`.`answer`,
					`c`.`dep_id`,
					`c`.`spec_id`,
					`c`.`date`,
					`c`.`ctrl`,
					`c`.`comment`,
					`d`.`short` AS `dep_name`, 
					`n`.`name` AS `spec_name`,
					`i`.`descr` AS `item_descr` 
					FROM `control` AS `c` 
					LEFT JOIN `department` AS `d` 
					ON (`c`.`dep_id`=`d`.`id`) 
					LEFT JOIN `name` AS `n` 
					ON (`c`.`spec_id`=`n`.`id`) 
					LEFT JOIN `control_item` AS `i` 
					ON (`c`.`control_id`=`i`.`id`) 
					WHERE `c`.`ctrl`=0 AND `c`.`date` BETWEEN '0000-00-00' AND '$year-$month-$day'";
	}
	else if ($date1 == "" and $date2 != ""){
		$query= "SELECT `c`.`id`,
					`c`.`descr`,
					`c`.`performed`,
					`c`.`answer`,
					`c`.`dep_id`,
					`c`.`spec_id`,
					`c`.`date`,
					`c`.`ctrl`,
					`c`.`comment`,
					`d`.`short` AS `dep_name`, 
					`n`.`name` AS `spec_name`,
					`i`.`descr` AS `item_descr` 
					FROM `control` AS `c` 
					LEFT JOIN `department` AS `d` 
					ON (`c`.`dep_id`=`d`.`id`) 
					LEFT JOIN `name` AS `n` 
					ON (`c`.`spec_id`=`n`.`id`) 
					LEFT JOIN `control_item` AS `i` 
					ON (`c`.`control_id`=`i`.`id`) 
					WHERE `c`.`ctrl`=0 AND `c`.`date` BETWEEN '0000-00-00' AND '$date2'";
	}
	else{
	$query= "SELECT `c`.`id`,
					`c`.`descr`,
					`c`.`performed`,
					`c`.`answer`,
					`c`.`dep_id`,
					`c`.`spec_id`,
					`c`.`date`,
					`c`.`ctrl`,
					`c`.`comment`,
					`d`.`short` AS `dep_name`, 
					`n`.`name` AS `spec_name`,
					`i`.`descr` AS `item_descr` 
					FROM `control` AS `c` 
					LEFT JOIN `department` AS `d` 
					ON (`c`.`dep_id`=`d`.`id`) 
					LEFT JOIN `name` AS `n` 
					ON (`c`.`spec_id`=`n`.`id`) 
					LEFT JOIN `control_item` AS `i` 
					ON (`c`.`control_id`=`i`.`id`) 
					WHERE `c`.`ctrl`=0 AND `c`.`date` BETWEEN '$date1' AND '$date2' ORDER BY `c`.`date`";
	}

	

	$result = $mysqli->query($query);
	while ($row = $result->fetch_assoc()){
		$rows[] = $row;
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
	$cell->addText('Поручение','myOwnStyle');
	$cell = $table->addCell(2000, $cellStyle);
	$cell->addText("Департамент",'myOwnStyle');
	$cell = $table->addCell(2000, $cellStyle);
	$cell->addText("Специалист",'myOwnStyle');
	$cell = $table->addCell(2000, $cellStyle);
	$cell->addText("Срок",'myOwnStyle');
	$cell = $table->addCell(2000, $cellStyle);
	$cell->addText("Ответ",'myOwnStyle');
	$cell = $table->addCell(2000, $cellStyle);
	$cell->addText("Комментарий",'myOwnStyle');

	foreach ($rows as $row){
		$table->addRow();
		$cell = $table->addCell(4000);
		if (!empty($row['item_descr'])){
			$cell->addText($row['item_descr'],'myTdStyle');
		}
		$cell->addText($row['descr'],'myTdStyle');
		$cell = $table->addCell(1500);
		$cell->addText($row['dep_name'],'myTdStyle');
		$cell = $table->addCell(1500);
		$cell->addText($row['spec_name'],'myTdStyle');
		$cell = $table->addCell(1500);
		$cell->addText(correct_date($row['date']),'myTdStyle');
		$cell = $table->addCell(4000);
		$cell->addText($row['answer'],'myTdStyle');
		$cell = $table->addCell(2000);
		$cell->addText($row['comment'],'myTdStyle');
	}

	// At least write the document to webspace:
	$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
	//$objWriter->save('/home/samba/tranzit/helloWorld.docx');
	$objWriter->save('/var/www/plan/admin/download/control.docx');

	header('Location:http://10.50.10.100/admin/download/control.docx');
}


?>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<input type="text" name="date1">
	<input type="text" name="date2">
	<input type="submit">
</form>
