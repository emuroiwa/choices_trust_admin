
<?php
//include 'opendb.php';
if(isset($_POST['Submit'])){
  $topic=clean($_POST['topic']);
  $msg=clean($_POST['msg']);
  $region=UserRegion();
 $ordernumber=GetOrderNumber();
 $price=GetPrice('HardCopy');
 for($i=1;$i<=$_POST['qty'];$i++){
  //echo "INSERT INTO `booksales` (`user`, `ordertype`, `region`, `transactiondate`, `ordernumber`, `status`, `platform`, `transactiontype`, `booktype`) VALUES ('OTC', '$_POST[price]', '$region', now(), '$ordernumber', 'paid', 'OTC', 'buy', 'HardCopy')";
$result = mysql_query("INSERT INTO `booksales` (`user`, `ordertype`, `region`, `transactiondate`, `ordernumber`, `status`, `platform`, `transactiontype`, `booktype`) VALUES ('OTC', '$price', '$region', now(), '$ordernumber', 'paid', 'OTC', 'buy', 'OTC')") or die (mysql_error());
}

 ?>
<script language="javascript">
 alert("Successfully Saved");
 window.open("Checkout/invoiceprint1.php?id=<?php echo $ordernumber;?>");
</script>
<?php
	header("location: Checkout/invoiceprint1.php?id=$ordernumber");		
}

?>

<center>
<h3>Book Sales</h3>
<form action="" method="post" name="qualification_form" >



  
  <table  class="table table-bordered" align="center">
<tr>
  <td width="27%"> <span class="style1 style9">Hard Copy Price</span></td>
  <td width="73%">
    <input type="text" name="price" class="form-control" disabled  id="price" size="30"  required="required"  value="<?php echo GetPrice('HardCopy'); ?>" /></td>

</tr><tr>
  <td width="27%"> <span class="style1 style9">Quantity</span></td>
  <td width="73%">
    <input type="number" name="qty" class="form-control"  id="date" size="30" min="1"  required="required"  /></td>
</tr>
          


<tr><td colspan="2"  align="center"><input type="submit" name="Submit" size="30"  value="Save"/></td>
</tr>
</table>
</form>
</center>
</body>

</html>
