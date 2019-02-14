
<?php
	$rs=mysql_query("select * from  shipping ") or die(mysql_error());	
 if(mysql_num_rows($rs)==0){echo "No results";}
?>

<center><p><strong><h4>List Of Shipping Details</h4></strong></p></center>
<div class="table-responsive">
  <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" border="1">
    <thead>
                                        <tr bgcolor="">
    <th width="146">Description</th>
    <th width="149"><span class="style2">Price</span></th>
    

    <th width="181"><span class="style2">Delete</span></th>

  
    </tr>
    </thead>
    <tbody>
                                        <?php
										while($row=mysql_fetch_array($rs))
										{
										
										?>
                                        <tr class="odd gradeX" >
    <td bgcolor="#FFFFFF"><span class="style2"><?php echo $row['description']; ?></span></td>
    <td bgcolor="#FFFFFF"><span class="style2"><?php echo $row["price"]; ?></span></td>
   
    <td><?php echo "<a href='index.php?page=deleteshipping.php&id=$row[sid]' onclick='return confirm(\"Are you sure?\")'>[click to delete]</a></font>";?></td>
    

 </tr>
                                        <?php
										}
										?>
    </tbody>
  </table>

    <script>
    $(document).ready(function() {
        $('#dataTables-example').dataTable();
    });
    </script><br>
<br>
<br>
<br>
<br>
</div>