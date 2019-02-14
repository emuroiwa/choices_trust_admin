<?php
 mysql_query("update users set fastatus='Y' WHERE id= '$_GET[id]'");
$msg="Your Facilitators Application was successfully  To $new_password. <a href='http://www.choicestrust.com/admin/index.php'>Login</a> ";
   SendEmail("Choices Trust","Password Reset","Password Reset",$msg,$_POST['email']);
  ?>
  <script language="javascript">
  alert("Updated..............")
history.go(-1)   </script>
