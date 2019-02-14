<?php
 mysql_query("update booksales set status='Done' WHERE ordernumber= '$_GET[id]'");
$msg="Your Choices Book has been packed and sent for shipping.Thank you, God bless ";
   SendEmail("Choices Trust","Choices Shipment","Choices Shipment",$msg,$_GET['email']);
  ?>
  <script language="javascript">
  alert("Updated..............")
history.go(-1)   </script>
