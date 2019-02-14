
<?php
//include 'opendb.php';
if(isset($_POST['Submit'])){
	
  
 
$result = mysql_query("INSERT INTO `shipping` (`description`, `price`) VALUES ('$_POST[description]', '$_POST[price]')") or die (mysql_error());
if ($result )
{
 ?>
<script language="javascript">
 alert("Successfully Saved");
</script>
<?php
				 }
			 else
			  {
			      $msg= "Error Occured";
		}	   
}

?>


<form action="" method="post" name="qualification_form" >


<h3>Shipping</h3>
  
  <table  class="table table-bordered" align="center">
<tr>
  <td width="27%"> <span class="style1 style9">Shipping Description</span></td>
  <td width="73%">
    <input type="text" name="description" class="form-control"  id="description" size="30"  required="required"  /></td>
</tr><tr>
  <td width="27%"> <span class="style1 style9">Price</span></td>
  <td width="73%">
    <input type="text" name="price" class="form-control"  id="price" size="30"  required="required"  /></td>
</tr>

<tr><td colspan="2"  align="center"><input type="submit" name="Submit" size="30"  value="Save"/></td>
</tr>
</table>
</form>

</body>

</html>
