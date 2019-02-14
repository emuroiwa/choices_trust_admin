
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Choices trust  | Registration Page</title>
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
        <a href="../index.php"><b>Choices</b> Trust</a>
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
   }
   else{

 $rs = mysql_query("insert into users(name,surname,sex,email,address,username,password,idnumber,status,date,access,department,suspend,emailstatus) values ('$name','$_POST[surname]','$_POST[sex]','$email','$address','$email','$pwd','$_POST[type]','1',now(),'2','$_POST[department]','1','Y')") ;
  ?>
  <script language="javascript">
 alert("User successfully created");
  history.go(-1) 
  </script>
  <?php
  session_start();
$_SESSION['username'] = $_POST['email'];
$_SESSION['name'] = $_POST['name'];
$_SESSION['access'] = 2;
$_SESSION['email'] = $_POST['email'];
 	header("location: scripts/index.php");

  }}
?>

      
        <p class="login-box-msg">Register a new membership</p>
        <form action="" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Full name" name="name" required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email" name="email" required>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          
           <div class="form-group has-feedback">
            <input type="number" class="form-control" placeholder="Cellphone" name="cellnumber" required>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
           <div class="form-group has-feedback">
            <select class="form-control" name="type" required>
            <option value="">Choose Type</option>
            <option>Company</option>
            <option>Facilitator</option>
            <option>Student</option>
            </select>
         </div>
          <div class="form-group has-feedback">
           <textarea class="form-control" name="address" required placeholder="Address"></textarea>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Retype password" name="cpass" required>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> I agree to the <a href="#">terms</a>
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <input type="submit" class="btn btn-primary btn-block btn-flat" value="Register" name="Submit">
            </div><!-- /.col -->
          </div>
        </form>

       

        <a href="login.html" class="text-center">I already have a membership</a>
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
