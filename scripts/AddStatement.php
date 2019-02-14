
<?php
//include 'opendb.php';
if(isset($_POST['Submit'])){
	
            $fn = $_FILES['file']['name'];
			//echo $fn; exit;
			
				if($_FILES['file']['type']!="application/pdf")
	{

echo ("<SCRIPT LANGUAGE='JavaScript'> window.alert('File Not PDF')
		 window.location='index.php?page=AddStatement.php'
		 	</SCRIPT>");  
			
	}
    move_uploaded_file($_FILES['file']['tmp_name'],"statements/".$fn);
 
$result = mysql_query("INSERT INTO `statements` (`statement`, `description`, `statementmonth`, `date`) VALUES ('$fn', '$_POST[topic]', '$_POST[msgdate]', now())") or die (mysql_error());
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


<form action="" method="post" name="qualification_form" enctype="multipart/form-data">



  
  <table  class="table table-bordered" align="center">
<tr>
  <td width="27%"> <span class="style1 style9">Statement Description</span></td>
  <td width="73%">
    <input type="text" name="topic" class="form-control"  id="topic" size="30"  required="required"  /></td>
</tr><tr>
  <td width="27%"> <span class="style1 style9">Statement Month</span></td>
  <td width="73%">
    <input type="date" name="msgdate" class="form-control"  id="date" size="30"  required="required"  /></td>
</tr><tr>
  <td width="27%"> <span class="style1 style9">Type</span></td>
  <td width="73%">
                                     <input class="form-control"  name="file" type="file" class="input-xlarge" required/>

   </td>
</tr>
     

<tr><td colspan="2"  align="center"><input type="submit" name="Submit" size="30"  value="Save"/></td>
</tr>
</table>
</form>

</body>

</html>
