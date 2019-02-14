
<?php
	$rs=mysql_query("select * from users where access!=1 order by name asc,surname") or die(mysql_error());	
 if(mysql_num_rows($rs)==0){echo "No results";}
?>

<center><p><strong><h4>List Of Users</h4></strong></p></center>
<div class="table-responsive">
  <table class="table table-striped table-bordered table-hover" id="dataTables-example" width="100%" border="1">
    <thead>
                                        <tr bgcolor="">
    <th width="146">Fullname</th>
    <th width="149"><span class="style2">Email</span></th>
    <th width="149"><span class="style2">CellNumber</span></th>

    <th width="181"><span class="style2">Type</span></th>
    <th width="181"><span class="style2">Address</span></th>
    <th width="181"><span class="style2">Date</span></th>

    <th width="181"><span class="style2">Delete</span></th>
<!--    <th width="181"><span class="style2">Make Payment</span></th>
-->    <th width="181"><span class="style2">Edit</span></th>
  
    </tr>
    </thead>
    <tbody>
                                        <?php
										while($row=mysql_fetch_array($rs))
										{
										
										?>
                                        <tr class="odd gradeX" >
    <td bgcolor="#FFFFFF"><span class="style2"><?php echo $row['name']; ?></span></td>
    <td bgcolor="#FFFFFF"><span class="style2"><?php echo $row["email"]; ?></span></td>
    <td bgcolor="#FFFFFF"><span class="style2"><?php echo $row["cellnumber"]; ?></span></td>
  
    <td bgcolor="#FFFFFF"><span class="style2"><?php echo $row["department"]; ?></span></td>
    <td bgcolor="#FFFFFF"><span class="style2"><?php echo $row["address"]; ?></span></td>
     <td bgcolor="#FFFFFF"><span class="style2"><?php echo $row["date"]; ?></span></td>
    <td><?php echo "<a href='index.php?page=delete.php&id=$row[id]' onclick='return confirm(\"Are you sure?\")'>[click to delete]</a></font>";?></td>
    <td><?php echo "<a href='index.php?page=edit.php&id=$row[id]' onclick='return confirm(\"Are you sure?\")'>[click to edit]</a></font>";?></td>
    

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