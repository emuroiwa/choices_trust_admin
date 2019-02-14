<?php
error_reporting(0);
include 'opendb.php';
include 'functions.php';
$type=  encrypt_decrypt('decrypt', $_GET['type']);
session_start();

 if($type=='buy' and isset($_SESSION['username']) ){
 	header("location: scripts/index.php?page=order.php");
	exit;
	}
	 elseif($type=='donate' and isset($_SESSION['username'])){
 	header("location: scripts/index.php?page=buy.php");
	exit;
	}
if(isset($_POST['login'])){
$username = clean($_POST["username"]);
$password = clean(sha1($_POST["password"]));

  if($username == '' OR $password == ''){
	  	 echo ("<SCRIPT LANGUAGE='JavaScript'> window.alert('Enter All fields')
		  javascript:history.go(-1)
		 	</SCRIPT>");  
			exit;
		  }
		  
		  
		 
 else{

 $result ="";
$query = "SELECT * from users where username='$username' AND password = '$password' ";
$result = mysql_query($query);
$rows=mysql_fetch_array($result);
$fastatus=$rows['fastatus'];
$address=$rows['address'];
$access=$rows['access'];
$email=$rows['email'];
$id=$rows['id'];
$q1=$rows['name'];
$q2=$rows['surname'];
$full=$q1." ".$q2;

if(!$result)
{
	die( "\n\ncould'nt send the query because".mysql_error());
	exit;
}
	$row = mysql_num_rows($result);
	if($row==1 and $fastatus=="Y")
 {
  session_start();
$_SESSION['username'] = $username;
$_SESSION['name'] = $full;
$_SESSION['access'] = $access;
$_SESSION['email'] = $email;
$_SESSION['address'] = $address;
$_SESSION['id'] = $id;
	 
	 if($type=='buy'){
 	header("location: scripts/index.php?page=order.php");
	exit;
	}
	 elseif($type=='donate'){
 	header("location: scripts/index.php?page=buy.php");
	exit;
	}
   elseif($access!='1'){
  header("location: scripts/index.php?page=edit.php&id=$id");
  exit;
  }
	else{
		header("location: scripts/index.php");
	exit;
		}
 }

  else
 {
echo("<SCRIPT LANGUAGE='JavaScript'> window.alert('Wrong Username And Password Combination')
		 window.location='index.php'
		 	</SCRIPT>");   
	
}
}}
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Choices Trust Website| Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="../index.php"><b>Choices</b> Trust</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form action="" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Email Address" required name="username">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" required name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
            
            </div><!-- /.col -->
            <div class="col-xs-4">
              <input type="submit" class="btn btn-primary btn-block btn-flat" name="login" id="login" value="Sign In">
            </div><!-- /.col -->
   <a href="registerGeneral.php" class="text-center">Register</a> <br>
        <a href="forgot.php" class="text-center">Forgot Password</a>      
          </div>
        </form>

       
       <!-- <a href="#">I forgot my password</a><br>
        <a href="#" class="text-center">Registerw a new membership</a>-->

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
