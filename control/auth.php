 <?php
        session_start();
        if (!isset($_SERVER['PHP_AUTH_USER']))  {
         header('WWW-Authenticate: Basic realm="plan"');
         header('HTTP/1.0 401 Unauthorized');
         exit;  } 
        else {
		include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
        $user = $_SERVER['PHP_AUTH_USER'];
        $pwd = $_SERVER['PHP_AUTH_PW'];    
        $query = "SELECT password,id,name FROM name WHERE login='$user'";
        $results = $mysqli->query($query);
        $result = $results->fetch_assoc();     
        if (password_verify($pwd,$result['password'])){
            $_SESSION['is_personal']=true;
			$_SESSION['name']=$result['name'];
			$_SESSION['id']=$result['id'];
			//$url = $_SESSION['url'];
			header("Location:index.php");
			exit;
		}
        else{
            header('WWW-Authenticate: Basic realm="plan"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Доступ запрещен';
            exit;
            }  
		}
     ?> 
