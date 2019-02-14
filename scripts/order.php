   <?php
	if(isset($_POST['xxx'])){
		$prices=GetPrice($_POST['price']);
		session_start();
		$_SESSION['price']=$prices;
				$_SESSION['transactiontype']="Buy";

if($_POST['price']!="HardCopy"){
				$_SESSION['booktype']="SoftCopy";	

		?>
        <script>
        window.location='Checkout/index.php'
        
        </script><?php
    }else{
	?>
        <script>
        window.location='index.php?page=ProcessShipping.php'
        
        </script><?php

    }
		}
    
	?>
	<!--content-->
	<div class="content">
		<br>
<br>
<br>
<form action="" method="post">

		<center>
		
        <table width="100%"><tr><td width="50%">
                <table align="right"><tr><td align="right"><img src="../../images/cover.png" width="150" height="210" ></td></tr></table>
</td><td><strong>Price List In USD$</strong>
            <table class="table table-bordered">
  <tr><td>HardCopy</td><td><?php echo GetPrice('HardCopy'); ?></td></tr>
  <tr><td>Word</td><td><?php echo GetPrice('Word'); ?></td></tr>
  <tr><td>PDF</td><td><?php echo GetPrice('PDF'); ?></td></tr>
  </table></td></tr></table>
        <table class="table table-bordered">
        
			<tr>
			<td>Order Type</td>
			<td><select name="price" required class="form-control"><option value="">Please Select Order Type</option><option value="HardCopy">HardCopy</option><option value="Word">Word</option><option value="PDF">PDF</option></select></td>
          </tr><tr> <td></td> <td colspan="2" align="center"><input type="submit" value="Buy Now" name="xxx"  class="btn btn-block btn-primary" ></td><td></td>
			</tr>
		</table>
		<br></center></form>