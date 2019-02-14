   <?php
	if(isset($_POST['xxx'])){
		$prices=$_POST['price'];
		session_start();

		$_SESSION['price']+=$_POST['shipping'];
			$_SESSION['booktype']="HardCopy";	

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

		<center>
		
 
        <table class="table table-bordered">
        
			<tr>
			<td>Shipping Type</td>
			<td><?php 

 
$sql="select * from shipping";
$rez=mysql_query($sql);
?>
<select name='shipping' id ='shipping' class="form-control">

<?php
while($row=mysql_fetch_array($rez,MYSQL_ASSOC))
{
 echo "<option value='$row[price]'>{$row['description']} ($ {$row['price']})</option>"; 
}

?>
</select></td>
          </tr><tr> <td></td> <td colspan="2" align="center"><input type="submit" value="Continue" name="xxx"  class="btn btn-block btn-primary" ></td><td></td>
			</tr>
		</table>
		<br></center></form>