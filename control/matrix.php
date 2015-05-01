<?php

$_SESSION['url'] = "matrix.php";
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');
$id = $_SESSION['id'];

class matrix {
	private $id;
	
	function __construct($id){
		$this->id = $id;
	}
	function getFromDB($row){
		//include('bd.php');
		$query = "SELECT $row FROM matrix WHERE id_spec=".$this->id;
		$returnQuery = $mysqli->query($query); 
		$result = $returnQuery->fetch_assoc();
		return $result;
	}
	
	function printQueryFromDB($row,$edit){
		$result =<<<IMPURG
		<form action=" {$_SERVER['PHP_SELF']}" method="post">
		<textarea name="answer" cols=100 rows=50 >$row</textarea><br/>	
		<input type='hidden' name='spec_id' value="$this->id">
		<input type='hidden' name='edit' value="$edit">
		<input type="submit" >
		</form>
IMPURG;
		return $result;
	}
	
	function insertIntoDB($edit,$answer){
		include('bd.php');
		$query = "UPDATE `matrix` SET `$edit`='$answer' WHERE id_spec=".$this->id;
		echo $query;
		$returnQuery = $mysqli->query($query); 
	}
}

if (isset($_GET) && !empty($_GET))
{
	$id = $_SESSION['id'];
	$edit = $_GET['edit']; 
	$matrix =  new matrix($id);
	$row = $matrix->getFromDB($edit);
	echo $matrix->printQueryFromDB($row[$edit],$edit);
}
else if (isset($_POST) && !empty($_POST)) 
{
	$id = $_POST['spec_id'];
	$edit = $_POST['edit']; 
	$answer = $_POST['answer']; 
	$matrix =  new matrix($id);
	$matrix->insertIntoDB($edit,$answer);
	header("Location:matrix.php");
}
else
{
	

	$query = "SELECT * FROM matrix WHERE id_spec=$id";
	$result = $mysqli->query($query); 
	$row = $result->fetch_assoc();
	foreach($row as $k=>$v){
		$row[$k] = nl2br($v);
	}
	echo <<<HERE
	<table border=1>
	<tr>
		<th></th>
		<th>Срочно</th>
		<th>Несрочно</th>
	</tr>
	<tr>
		<td><b>Важно</b></td>
		<td>{$row['important_urgent']}<br/> <a href="{$_SERVER['PHP_SELF']}?edit=important_urgent"> Редактировать</a></td>
		<td>{$row['important_unurgent']}<br/> <a href="{$_SERVER['PHP_SELF']}?edit=important_unurgent"> Редактировать</a></td>
	</tr>
	<tr>
		<td><b>Неважно</b></td>
		<td>{$row['unimportant_urgent']}<br/> <a href="{$_SERVER['PHP_SELF']}?edit=unimportant_urgent"> Редактировать</a></td>
		<td>{$row['unimportant_unurgent']}<br/><a href="{$_SERVER['PHP_SELF']}?edit=unimportant_unurgent"> Редактировать</a></td>
	</tr>

	</table>
HERE;
}
?>