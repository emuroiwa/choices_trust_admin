<?php include('header.php'); ?>
	<span class="span4">
	</span>
	<span class="span5">
		<div class="hero-unit">
		<?php 		SetSales($_SESSION['username'],$amt,UserRegion(),$transactionId,'Paypal');
?>
		   You cancelled the order.
		</div>
	</span>
	<span class="span3">
	</span>
<?php include('footer.php'); ?>