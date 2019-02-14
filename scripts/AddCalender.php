
<?php
//include 'opendb.php';
if(isset($_POST['Submit'])){
  $topic=clean($_POST['topic']);
  $msg=clean($_POST['msg']);
  
 
$result = mysql_query("INSERT INTO `calender` (`topic`, `msg`, `msgdate`, `user`, `type`) VALUES ('$topic', '$msg', '$_POST[msgdate]', '$_SESSION[username]', '$_POST[type]')") or die (mysql_error());
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



  
  <table  class="table table-bordered" align="center">
<tr>
  <td width="27%"> <span class="style1 style9">Topic</span></td>
  <td width="73%">
    <input type="text" name="topic" class="form-control"  id="topic" size="30"  required="required"  /></td>
</tr><tr>
  <td width="27%"> <span class="style1 style9">Date</span></td>
  <td width="73%">
    <input type="date" name="msgdate" class="form-control"  id="date" size="30"  required="required"  /></td>
</tr><tr>
  <td width="27%"> <span class="style1 style9">Type</span></td>
  <td width="73%">
  <select name="type" class="form-control" required > <option value="">Select Calender Type </option>
  <option>General</option><option>Faciltators</option><option>Students</option></select>
   </td>
</tr>
      <tr>
      <td>
      Message</td>
      <td><textarea class="form-control"  name="msg" required rows="5"></textarea>&nbsp;</td>
      </tr>
          
</td></tr>

<tr><td colspan="2"  align="center"><input type="submit" name="Submit" size="30"  value="Save"/></td>
</tr>
</table>
</form>

</body>

</html>
