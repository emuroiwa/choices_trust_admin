 
	  <?php $rs=mysql_query("select * from users where access!=1 and fastatus='N' order by name asc,surname") or die(mysql_error());	
	  
	  ?>
<center><h4>List of  Facilitators</h4><table width="65%" style="border:1px solid #000000" border="1" bordercolor="#000000" class="table table-bordered table-hover" >
					  <tr> 
                                   
                                   <td bgcolor="#0066FF" width="80"><font color="#000000">Name</font></td>
                                   <td bgcolor="#0066FF" width="103"><font color="#000000">Address</font></td>
                                   <td bgcolor="#0066FF" width="103"><font color="#000000">Cell Phone</font></td>
                                   <td bgcolor="#0066FF" width="103"><font color="#000000">Email</font></td>
                                 <td bgcolor="#0066FF" width="120"><font color="red">Approve</font></td>
								 
<?php  
while($row = mysql_fetch_array($rs)){
		$name = $row['name'];
		$surname = $row['surname'];
		$id = $row['id'];
		
		
		

	 echo "<tr><td>".$name."</td><td>{$row['address']}</td><td>{$row['cellnumber']}</td><td>{$row['email']}</td><td><a href='index.php?page=approve.php&id=$id' onclick='return confirm(\"Are you sure?\")'>[click to approve]</a></font></td></tr>";
  
    
   }
?>        </tr></table></center>
 