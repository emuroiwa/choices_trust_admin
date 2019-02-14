
<?php
//include 'opendb.php';
if(isset($_POST['Submit'])){
	   
   $msg=clean($_POST['msg']);
 
$result = mysql_query("update `details` set  `details`='$msg' where  `type`= 'fa'") or die (mysql_error());
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



  <center><h3>Update Faciltators</h3></center>
  <table  class="table table-bordered" align="center">

      <tr>
      <td>
      Details</td>
      <td><textarea class="form-control"  name="msg" required rows="5"><?php echo GetDetails('fa');?></textarea>&nbsp;</td>
      </tr>
          
</td></tr>

<tr><td colspan="2"  align="center"><input type="submit" name="Submit" size="30"  value="Save"/></td>
</tr>
</table>
</form>

</body>

</html>
