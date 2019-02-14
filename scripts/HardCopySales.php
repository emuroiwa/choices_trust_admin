
<?php $rs=mysql_query("SELECT * FROM `booksales`,users where users.username=booksales.`user` and booktype='HardCopy' and booksales.status<>'Done' ORDER BY transactiondate desc ") or die(mysql_error());	
	  
	  ?>
<center><h4>List of  Book Sales</h4><table width="65%" style="border:1px solid #000000" border="1" bordercolor="#000000" class="table table-bordered table-hover">
					  <tr> 
                                                                   <td width="103"><font color="#000000">BookType</font></td>
   
                                   <td width="80"><font color="#000000">Name</font></td>
                                   <td width="103" bgcolor="blue"><font color="red">Address</font></td>
                                   <td width="103"><font color="#000000">Cell Phone</font></td>
                                   <td width="103"><font color="#000000">Email</font></td>
                                   <td width="103"><font color="#000000">Country</font></td>
                                   <td width="103"><font color="#000000">Date</font></td>
                                 <td width="120"><font color="blue">Invoice</font></td><td width="120"><font color="blue">Start Shipment</font></td>
								 
<?php  
if(mysql_num_rows($rs)==0){
	echo "<h4><font color='red'> No Sales Recorded </font></h4>";
}
while($row = mysql_fetch_array($rs)){
		$name = $row['name'];
		$surname = $row['surname'];
		$id = $row['ordernumber'];
		
		
		

	 echo "<tr><td>{$row['booktype']}</td><td>".$name."</td><td>{$row['address']}</td><td>{$row['cellnumber']}</td><td>{$row['email']}</td><td>{$row['region']}</td><td>{$row['transactiondate']}</td><td><a href='checkout/invoiceprint.php?id=$id' onclick='return confirm(\"Are you sure?\")'>[View]</a></font></td><td><a href='index.php?page=StartShipping.php&email=$row[email]&id=$row[ordernumber]' onclick='return confirm(\"Are you sure?\")'>[Start]</a></font></td></tr>";
  
    
   }
?>        </tr></table></center>