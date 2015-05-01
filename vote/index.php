<?php
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
session_start();
if (isset($_POST) && !empty($_POST)){
	$id_poll     = $_POST['id_poll'];
	$id_variant  = $_POST['variant'];
	$ip			 = $_SERVER['REMOTE_ADDR'];
	
	$query = "SELECT 1 FROM result WHERE id_poll='$id_poll' AND IP='$ip'";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	
	if($row){
		echo "<a href='/'><img src='/img/previous.png' title='Вернуться обратно'></a><br/><br/>";
		?>
		<script type='text/javascript'>
		alert("Вы уже проголосовали!");
		window.location="result.php";
		</script>
		<?php

	}
	else{
		if ((isset($_SESSION['is_personal']))) {
			$id_name = $_SESSION['id'];
		}
		else {
			$id_name = 0;
		}
	

		$query_names = "INSERT INTO result
						(`id_name`, 
						`id_poll`,
						`id_variant`,
						`IP`) 
						VALUES 
						('$id_name',
						'$id_poll',
						'$id_variant',
						'$ip')";
		$result = $mysqli->query($query_names);
		header("Location:result.php");
	}
}

$query_names = "SELECT name,id FROM poll ORDER BY `id` DESC LIMIT 1";
$result      = $mysqli->query($query_names);
$row 		 = $result->fetch_assoc();
$id_poll     = $row['id'];
$name_poll   = $row['name'];

$query  = "SELECT name,id FROM variant WHERE id_poll='$id_poll'";
$result = $mysqli->query($query);


?>
<h2>Опрос</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
<?php echo $name_poll;?><br/>

<?php
while($row = $result->fetch_assoc()){
	echo "<input type='radio' name='variant' value='".$row['id']."'>".$row['name']."<br/>";
}
?>
<input type="hidden" name="id_poll" value="<?php echo $id_poll;?>">
<input type="submit" value="Выбрать">
</form>