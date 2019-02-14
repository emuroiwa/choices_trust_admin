<?php
error_reporting(0);
include 'opendb.php';
include 'functions.php';
$type=  encrypt_decrypt('decrypt', $_GET['type']);
if(isset($_POST['login'])){
 if($_POST['email'] == '' ){
       echo ("<SCRIPT LANGUAGE='JavaScript'> window.alert('Enter Email Address')
      javascript:history.go(-1)
      </SCRIPT>");  
      exit;
     }
     $qry=mysql_query("select * from users where email='$_POST[email]'");
      if(mysql_num_rows($qry) == 0){
       echo ("<SCRIPT LANGUAGE='JavaScript'> window.alert(' Email Address doesnt exist')
      javascript:history.go(-1)
      </SCRIPT>");  
      exit;
     }
function &generatePassword($length=9, $strength=0) { 
        $vowels = 'aeiuy'; 
        $consonants = 'bcdfghjkmnpqrstwz'; 
        if ($strength & 1) { 
                $consonants .= 'BCDFGJLMNPQRSTVXZ'; 
        } 
        if ($strength & 2) { 
                $vowels .= "AEIUY"; 
        } 
        if ($strength & 4) { 
                $consonants .= '23456789'; 
        } 
        if ($strength & 8) { 
                $consonants .= '@#$%'; 
        } 
  
        $password = ''; 
        $alt = time() % 2; 
        for ($i = 0; $i < $length; $i++) { 
                if ($alt == 1) { 
                        $password .= $consonants[(rand() % strlen($consonants))]; 
                        $alt = 0; 
                } else { 
                        $password .= $vowels[(rand() % strlen($vowels))]; 
                        $alt = 1; 
                } 
        } 
        return $password; 
} 
  function qoutess($str){
$remove[] = "'";
$remove[] = '"';
$remove[] = "-"; // just as another example
$new = str_replace($remove, "", $str);
return $new;
}
$new_password =& generatePassword(); 
 $new_password1=sha1($new_password);
//$username=clean(qoutess($_POST['username'])); 
$email=clean(qoutess($_POST['email'])); 
$sql="update users set password='$new_password1' WHERE  email='$email'"; 
$result=mysql_query($sql); 
 
if($result){ 
$msg="Your Password has been successfully reset To $new_password. <a href='http://www.choicestrust.com/admin/index.php'>Login</a> to change your password";
   SendEmail("Choices Trust","Password Reset","Password Reset",$msg,$_POST['email']);
  // WriteToLog("Reset Pasword $email ",$email);
   echo ("<SCRIPT LANGUAGE='JavaScript'> window.alert('Password Succesfully Reset Please Check your email')
      javascript:history.go(-1)
      </SCRIPT>");  
} 
 
else{ 
$content.='<font color="#FF0000">Wrong details provided!!!!! <br />
New password could not be generated.  
If you continue to have issues, please email <a href="mailto:emuroiwa@gmail.com">emuroiwa@gmail.com</a> for assistance.</font>'; 
} 
 
 echo $content;}
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
        <p class="login-box-msg">Recover password</p>
        <form action="" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Email Address" required name="email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
         
          <div class="row">
            <div class="col-xs-8">
            
            </div><!-- /.col -->
            <div class="col-xs-4">
              <input type="submit" class="btn btn-primary btn-block btn-flat" name="login" id="login" value="Sign In">
            </div><!-- /.col -->

        <a href="index.php" class="text-center">Login</a>
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
