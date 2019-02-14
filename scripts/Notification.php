
<?php
//include 'opendb.php';
if(isset($_POST['Submit'])){
	   
if($_POST['type']=="Company"){
  $type="Company";
}
  elseif($_POST['type']=="Facilitator"){
  $type="Facilitator";
}
 elseif($_POST['type']=="Student"){
    $type="Student";

}
$msg=$_POST['msg'];
    $query = mysql_query("SELECT * FROM `users` where department='$type'");
while($rw = mysql_fetch_array($query, MYSQL_ASSOC)){

 
      
  SendEmail("Choices Trust","Church Notification","Choices Trust Church Notification",$msg,$rw['email']);
  SendSMS($msg,$rw['cellnumber'],"Choices");
  }

 ?>
<script language="javascript">
 alert("Successfully Sent");
</script>
<?php
}

?>


<form action="" method="post" name="qualification_form" >



  <center><h3>Send Notification</h3></center>
  <table  class="table table-bordered" align="center">
<tr>
      <td>
      Details</td>
      <td>
            <select class="form-control" name="type" required>
            <option value="">Choose Type</option>
            <option>Company</option>
            <option>Facilitator</option>
            <option>Student</option>
            </select>
         </td></tr>
      <tr>
      <td>
      Details</td>
      <td><textarea class="form-control"  name="msg" required rows="5" required="required"></textarea>&nbsp;</td>
      </tr>
          
</td></tr>

<tr><td colspan="2"  align="center"><input type="submit" name="Submit" size="30"  value="Send"/></td>
</tr>
</table>
</form>

</body>

</html>
