<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php
 mysql_query("DELETE FROM `users` WHERE id= '$_GET[id]'");

 
  ?>
  <script language="javascript">
  alert("Deleted............")
		  location = 'index.php'
  </script>
  <?php
  

  
?>
</body>
</html>