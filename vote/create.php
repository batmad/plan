<?php
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');

if (isset($_POST) && !empty($_POST)){
	$name= $_POST['name'];
	$query_names = "INSERT INTO poll(`name`,`open`) VALUES ('$name','1')";
	$result = $mysqli->query($query_names);
	$id_poll = $mysqli->insert_id;
	$count = count($_POST['inp']);
	for($i=1;$i<=$count;$i++) {
		$input = $_POST['inp'][$i];
		$query_names = "INSERT INTO variant(`name`,`id_poll`) VALUES ('$input','$id_poll')";
		$result = $mysqli->query($query_names);
	}
	
	header("Location: http://$_SERVER[SERVER_ADDR]/vote/index.php");
	
}

?>


 
<script language='JavaScript' type="text/javascript">
var i = 3;
function ff(){
	document.getElementById('form_inner').innerHTML = document.getElementById('form_inner').innerHTML +
	"<input type='text' name='inp[" + i + "]' /><br/>";
	i++;
}
</script>

 <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
 Добавить вопрос: <br/>
 <input type='text' name='name'/> <br/>
 
 Добавить варианты ответа: <br/>

<span name='form_inner' id='form_inner'>
<input type='text' name='inp[1]' /> <br/>
<input type='text' name='inp[2]' /> <br/>
</span>
<input type='button' value='Добавить вариант' onclick="ff()"><br/><br/>


<input name='frm_sbm' type='submit' value='Отправить запрос' />
</form>
 

 
 