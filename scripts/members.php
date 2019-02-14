
          
	  <?php 
	 
  
 
	  $rs=mysql_query("select * from users where access=3 and id not in(select member from members where project='$_GET[id]') order by surname desc") or die(mysql_error()); 
	     $rw = mysql_num_rows($rs);
	if($rw==0){ echo "No Project Members Avaliable For This Project";//exit;   
		  }else{
	  ?>
<center><hr><form action="index.php?page=add.php" method="post">
<h4>Project members avaliable</h4><table width="52%" style="border:1px solid #000000" border="1" bordercolor="#000000"  class="table table-bordered table-hover">
					  <tr> 
                                   
                                     
                       <input type="hidden" name="project" value="<?php echo $_GET['id'];?>" >
                       <input type="hidden" name="type" value="member" >
                      
                                                <td  width="233"><strong>Name</strong></td> 
                                                <td  width="233"><strong>Surname</strong></td> 
                                                
                        
                         <td  width="83"><strong>Tick To Add</strong></td></tr>
                                  <?php  
while($row = mysql_fetch_array($rs)){
	?>	
      <input type="hidden" name="member" value="<?php echo $row['id'];?>" >
<?php
		echo "<tr><td>{$row['name']}</td><td>{$row['surname']}</td><td>  <input type='checkbox' onclick='this.form.submit();'>
</td></tr>";

    
   }
	
?>        </tr></table></center></form>	  
<hr>

<?php 
		  }
  
 
	  $rs=mysql_query("select * from users where access=4 and id not in(select member from members where project='$_GET[id]') order by surname desc") or die(mysql_error()); 
	     $rw = mysql_num_rows($rs);
	if($rw==0){  echo "<br>
No Project Donors Avaliable For This Project";exit;   
		  }else{
	  ?>
<center><hr><form action="index.php?page=add.php" method="post">
<h4>Add Sponsor</h4><table width="52%" style="border:1px solid #000000" border="1" bordercolor="#000000"  class="table table-bordered table-hover" >
					  <tr > 
                                   
                                                            <input type="hidden" name="type" value="donor" >

                       <input type="hidden" name="project" value="<?php echo $_GET['id'];?>" >
                      
                                                <td  width="223"><strong>Name</strong></td> 
                                                <td  width="223"><strong>Surname</strong></td> 
                                                
                        
                         <td  width="83"><strong>Tick To Add</strong></td></tr>
                                  <?php  
while($row = mysql_fetch_array($rs)){
	?>	
      <input type="hidden" name="member" value="<?php echo $row['id'];?>" >
<?php
		echo "<tr><td>{$row['name']}</td><td>{$row['surname']}</td><td>  <input type='checkbox' onclick='this.form.submit();'>
</td></tr>";

    
   }
	
?>        </tr></table></center></form><?php } ?>