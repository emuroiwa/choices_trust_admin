processing..................................................
<?php
error_reporting(0);
include '../../functions.php';
include '../../opendb.php';
if($_SESSION['transactiontype']!="Donate"){
$dd=GetEmail($_GET['id']);
if($_SESSION['booktype'])=="HardCopy"{

	 SendEmail("Choices Trust","HardCopy Purchase","HardCopy Purchase","Please Logi to the website to procees the Hardcopy purchase","admin@choicestrust.com");

}
		SendEmailFile(GetEmail($_GET['id']),"dsfsdfdfsf");
}else
{
	     SendEmail("Choices Trust","Donation","Choices Trust Donation","Thank you so much for the donation. May God Bless you",GetEmail($_GET['id']));

	}
		header("location: invoiceprint.php?id=$_GET[id]");


?>