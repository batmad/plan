 <?php
        echo $_SERVER['SERVER_ADDR'];
        session_start();
        if (!isset($_SERVER['PHP_AUTH_USER']))  {
         header('WWW-Authenticate: Basic realm="Пожалуйста авторизуйтесь. Введите логин и пароль"');
         header('HTTP/1.0 401 Unauthorized');
         exit;  } 
        else {
		include($_SERVER['DOCUMENT_ROOT'].'bd.php');
        $user = $_SERVER['PHP_AUTH_USER'];
        $pwd = $_SERVER['PHP_AUTH_PW'];    
        $query = "SELECT password FROM admins WHERE username='$user'";
        $results = $mysqli->query($query);
        $result = $results->fetch_assoc();     
        if (password_verify($pwd,$result['password'])){
            $_SESSION['is_logged_in']=true;
			header("Location:index.php");
			}
        else{
            header('WWW-Authenticate: Basic realm="plan"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Доступ запрещен';
            exit;
            }  
		}
     ?> 
