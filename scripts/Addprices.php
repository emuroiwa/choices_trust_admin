
<?php
//include 'opendb.php';
if(isset($_POST['xxx'])){
	   
   
$result = mysql_query("update `prices` set  `price`='$_POST[price]' where  `version`= '$_POST[version]'") or die (mysql_error());
if ($result )
{
 ?>
<script language="javascript">
 alert("Successfully Saved");
 history.go(-1);
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



  <center><h3>Prices</h3></center>
  <table class="table table-bordered">
  <tr><td>HardCopy</td><td>$<?php echo GetPrice('HardCopy'); ?></td></tr>
  <tr><td>Word</td><td>$<?php echo GetPrice('Word'); ?></td></tr>
  <tr><td>PDF</td><td>$<?php echo GetPrice('PDF'); ?></td></tr>
  </table>
  <center><h3>Update Prices</h3></center>
  <table class="table table-bordered">
        
      <tr>
      <td>Version</td>
      <td><select name="version" required class="form-control"><option value="">Please Select Order Type</option><option value="HardCopy">HardCopy</option><option value="Word">Word</option><option value="PDF">PDF</option></select></td>

          </tr>
<tr> <td>Price</td><td> $<input type="number" name="price" required="" ></td></tr>
          <tr> <td></td> <td colspan="2" align="center"><input type="submit" value="Update" name="xxx"  class="btn btn-block btn-primary" ></td><td></td>
      </tr>
    </table>
</form>

</body>

</html>
