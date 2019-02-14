<!DOCTYPE HTML>
<?php include('header.php');
      include('paypal_config.php'); 
	  error_reporting(0);
	  session_start();
	  ?>

            <!--Form containing item parameters and seller credentials needed for SetExpressCheckout Call-->
            <form class="form" action="paypal_ec_redirect.php" method="POST">
             
                  
                    
                        <table align="center">
                        <tr><td>Welcome to the Choices Trust Checkout. For International (Mastercard and Visa) and Ecocash and Telecash</td>  <td><img src="img/verified_merchant.png"></td></tr></table>
                        <table align="center">
                                      <tr><td><input type="hidden" name="currencyCodeType" value="USD" readonly></input><input type="hidden" name="PAYMENTREQUEST_0_AMT" value="<?php echo $_SESSION['price'] ?>" readonly></input><input type="hidden" name="LOGOIMG" value=<?php echo('http://'.$_SERVER['HTTP_HOST'].preg_replace('/index.php/','img/logo.jpg',$_SERVER['SCRIPT_NAME'])); ?>></input></td></tr>                

                        <tr><td colspan="2"><!--<br/><br/><div id="myContainer"></div>--></td><td></td><td colspan="2"><br/><br/><a href='paynow.php' target='_blank'><img src='https://www.paynow.co.zw/Content/Buttons/Medium_buttons/button_pay-now_medium.png' style='border:0' /></a></td></tr>
						
                        </table>
               
            </form>
   

   <!--Script to dynamically choose a seller and buyer account to render on index page-->
   <script type="text/javascript">
      function getRandomNumberInRange(min, max) {
          return Math.floor(Math.random() * (max - min) + min);
      }


      var buyerCredentials = [{"email":"ron@hogwarts.com", "password":"qwer1234"},
                        {"email":"sallyjones1234@gmail.com", "password":"p@ssword1234"},
                        {"email":"joe@boe.com", "password":"123456789"},
                        {"email":"hermione@hogwarts.com", "password":"123456789"},
                        {"email":"lunalovegood@hogwarts.com", "password":"123456789"},
                        {"email":"ginnyweasley@hogwarts.com", "password":"123456789"},
                        {"email":"bellaswan@awesome.com", "password":"qwer1234"},
                        {"email":"edwardcullen@gmail.com", "password":"qwer1234"}];
      var randomBuyer = getRandomNumberInRange(0,buyerCredentials.length);

      document.getElementById("buyer_email").value =buyerCredentials[randomBuyer].email;
      document.getElementById("buyer_password").value =buyerCredentials[randomBuyer].password;


   </script>

   <script type="text/javascript">
   window.paypalCheckoutReady = function () {
       paypal.checkout.setup('<?php echo($merchantID); ?>', {
           container: 'myContainer',
           environment: '<?php echo($env); ?>'
       });
   };
   </script>
   <script src="//www.paypalobjects.com/api/checkout.js" async></script>

<?php include('footer.php') ?>
