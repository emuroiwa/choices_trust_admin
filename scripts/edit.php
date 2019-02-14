
<?php


		$rs=mysql_query("select * from users where id = '$_GET[id]'") or die(mysql_error());
while($row = mysql_fetch_array($rs)){
		$id = $row['id'];
		$name = $row['name'];
		$surname = $row['surname'];
		$phone= $row['cellnumber'];
		$address = $row['address'];
		$idnum = $row['idnum'];
				$email = $row['email'];
				}
				
	?>
<?php
  if(isset($_POST['Submit'])){
   include "opendb.php";
  $rs = mysql_query("UPDATE users set name = '$_POST[name]',address='$_POST[address]',email='$_POST[email]',cellnumber='$_POST[phone]' where id = '$_GET[id]'")or die(mysql_error());
 
  if($rs){
  ?>
  <script language="javascript">
 alert("successfully updated")
 location = 'index.php'
  </script>
  <?php
  
  }
  else
  {
  echo "problem occured";
  }
  }

?>

	
<div class="style4">
  <div align="center">Edit  Details</div>
</div>
<form action="" method="post" >

   
<center>  <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" border="1">
  
        <tr>
      <td>Fullname</td>
      <td>
        <input name="name" type="text" id="name" value="<?php echo $name; ?>"  width="100%" />       </td>
        </tr>
        
            <td>Email</td>
            <td>
              <input name="email" type="text" id="email"  value="<?php echo $email; ?>"  width="100%"/>            </td>
          </tr>  <tr>
            <td>Cellnumber</td>
            <td>
              <input name="phone" type="text" id="phone"  value="<?php echo $phone; ?>" />            </td>
          </tr>  <tr>
            <td>Address</td>
            <td>
             <textarea name="address" name="address"><?php echo $address; ?></textarea>          </td>
          </tr>  <tr>
            <td></td>
            <td>
              <input name="Submit" type="Submit" id="Submit" value="Update"  />            </td>
          </tr>
    </table>
   </form>

