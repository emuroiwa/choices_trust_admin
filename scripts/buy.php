   <?php
	if(isset($_POST['xxx'])){
		$prices=$_POST['price'];
		session_start();
		$_SESSION['price']=$prices;
		$_SESSION['transactiontype']="Donate";
		?>
        <script>
        window.location='Checkout/index.php'
        
        </script><?php
		}
	?>
	<!--content-->
	<div class="content">
		<br>
<br>
<br>
<form action="" method="post">

		<center>Thank you so such for your donation. Please enter the amount you want to donate below. <table class="table table-bordered">
			<tr>
			<td>Amount To Donate $</td>
			<td><input type="number" required name="price" class="form-control" step=any min="1" ></td>
          </tr><tr> <td></td> <td colspan="2" align="center"><input type="submit" value="Donate"  name="xxx"  class="btn btn-block btn-primary" ></td><td></td>
			</tr>
		</table><br></center></form>