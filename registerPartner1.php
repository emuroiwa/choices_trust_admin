
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Choices Trust | Registration Page</title>
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
  <body class="hold-transition register-page">
    <div class="register-box">
      <div class="register-logo">
        <a href="#"><b>Choices</b> Trust</a>
      </div>

      <div class="register-box-body">
      <?php
	  error_reporting(0);
  if(isset($_POST['Submit'])){
       include "opendb.php";
   include "functions.php";
   
   $name = clean($_POST['name']);
   $email = clean($_POST['email']);
   $address = clean($_POST['address']);
   $password = clean($_POST['password']);
   $cpass = clean($_POST['cpass']);
   $cellnumber=clean($_POST['cellnumber']);
   $username = $_SESSION['username'];

    $pwd=sha1($password);
   $rs1 = mysql_query("select * from users where username = '$email'");
   $rw = mysql_num_rows($rs1);
   if($rw == 1){
   ?>
  <script language="javascript">
 alert("Email already in use");
 history.go(-1)
  </script>
  <?php
  exit;
   }
  
   if($password!=$cpass){
   ?>
  <script language="javascript">
 alert("Password did not match with confrim password");
 history.go(-1)
  </script>
  <?php
  exit;
   }
   if(strlen($password) < 8 ){
   ?>
  <script language="javascript">
 alert("Password should be above 8 charactors");
 history.go(-1)  </script>
  <?php
  exit;
   } else{
if(substr($_POST['contact'],0, 1)==0){
         $contact=$_POST['country'].substr($_POST['contact'],1);

}else{

           $contact=$_POST['country'].$_POST['contact'];

}
 $rs = mysql_query("insert into users(name,surname,cellnumber,email,address,username,password,idnumber,status,date,access,department,suspend,emailstatus,fastatus) values ('$name','$_POST[surname]','$contact,'$email','$address','$email','$pwd','$_POST[type]','1',now(),'2','Facilitator','1','Y','Y')") ;
 $msg="Thank you <strong>".$name."</strong>. You Can now login and Purchase Choices/Sarudzo <br>
 You Applications is being verified by Choices Trust. "; 
    SendEmail("Choices Trust","New Facilitator","Choices Trust New Facilitator",$msg,$email);

  //SendSMS($msg,$_POST['cellnumber'],"Choices")
  ?>
  <script language="javascript">
 alert("User successfully created");
   javascript:history.go(-1)
  </script>
 <?php
  /* session_start();
$_SESSION['username'] = $_POST['email'];
$_SESSION['name'] = $_POST['name'];
$_SESSION['access'] = 2;
$_SESSION['email'] = $_POST['email'];
 	header("location: scripts/index.php");
*/
  }}
?>

      
        <p class="login-box-msg">Register a new membership</p>
        <form action="" method="post">
          <div class="form-group has-feedback">
           <a href="registerPartner.php">  <button type="button" class="btn btn-primary btn-block btn-flat">Co-Operate Partners</button></a>
          </div>
          <div class="form-group has-feedback">
           <a href="registerPartnerIndi.php">  <button type="button" class="btn btn-primary btn-block btn-flat" href="registerPartnerIndi.php">Individual Partners</button></a>
          </div>
          
           
         
       

        <a href="index.php" class="text-center">I already have a membership</a>
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="/plugins/iCheck/icheck.min.js"></script>
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
