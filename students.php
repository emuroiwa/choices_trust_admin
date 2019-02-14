<!--A Design by W3layouts 
Author: W3layout
Author URL: http://Divine Developers.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
--><?php
error_reporting(0);
include 'admin/functions.php';
include 'admin/opendb.php';
list ($zero, $one, $two) = GetCalender();
/*foreach (GetCalender() as $key => $value) {
    // $arr[3] will be updated with each value from $arr...
    echo "{$key} => {$value} ";
    print_r($arr);
}*/
//echo $zero;
?>
<!DOCTYPE html>
<html>
<head>
<title>Students | Choices Trust</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.min.js"></script>
<!-- Custom Theme files -->
<!--theme-style-->
  <link rel="icon" href="images/logo.png">

<meta content="Students Partners  Page,Choices Trust Partners  Page, Choices Trust, Choice, Trust , Jesus, zimbabwe Religion, Chrisiatinty Zimbabwe, Avondale Church of the Nazarene. Corner Sam  Nujoma/Aberdeen Read.  Belgravia. Harare.,harare,Nico Ferreira, Nyasha Tundu,Webster Mutemaringa,The book is currently available in English and Shona. The book contains ten chapters with a short section at the end on relationships. Chapter headings are as follows: 
Jesus and me.,	Salvation ,	Foundations ,	The Holy Spirit,	Prayer,	Love ,	Truth ,	Purity,	Temptation,	Building your life-house,	Relationships  
The book is centred around the truth that: Your life is shaped by the choices you make. At the end of each chapter the student is asked to make a choice between what the Bible teaches or what the world teaches. 
" name="keywords">
<meta content="Choices Trust Students  Page,Choices Trust, The Choices Trust is a registered, non profit Trust in Zimbabwe. All trust members are Christian.The Choices Trust is a registered, non profit Trust in Zimbabwe. All trust members are Christian." name="description">
<meta content="Software team" name="author">
<meta content="Ernest Muroiwa" name="copyright">
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />	
<!--//theme-style-->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--fonts-->
<link href='http://fonts.googleapis.com/css?family=Raleway:400,200,600,800,900,500' rel='stylesheet' type='text/css'>
<!--//fonts-->
<script src="js/responsiveslides.min.js"></script>
	<script>
    $(function () {
      $("#slider").responsiveSlides({
      	auto: true,
      	speed: 500,
        namespace: "callbacks",
        pager: true,
      });
    });
  </script>
</head>
<body> 
<!--header-->
<!--banner-->
	<div class="banner">
	     
	</div>
	<!---->
	<div class="header">
		<div class="header-top">
			
			<div class="logo">
					<a href="index.php"><img src="images/logo.png" alt=" " /></a>
			</div>
			<div class="top-nav">
			
					<span class="menu"> </span>
								<ul>
						<li><a href="index.php" class="scroll" >HOME</a></li>
						<li><a href="about.php" class="scroll" data-hover="ABOUT">ABOUT</a></li>
						<li><a href="media.php" class="scroll" data-hover="MEDIA">MEDIA</a></li>

						<li><a href="resources.php" class="scroll" data-hover="RESOURCES">RESOURCES</a></li>
						<li><a href="../social" class="scroll" data-hover="TESTIMONIALS" target="_blank">TESTIMONIALS</a></li>
						<li class="active"><a href="students.php" class="scroll" data-hover="STUDENTS">STUDENTS</a></li>
						<li><a href="facilitators.php" class="scroll" data-hover="FACILITATORS">FACILITATORS</a></li>
                        						<li ><a href="contact.php" class="scroll" data-hover="CONTACT">CONTACT</a></li>

					</ul>
					<!--script-->
				<script>
					$("span.menu").click(function(){
						$(".top-nav ul").slideToggle(500, function(){
						});
					});
			</script>
				
				</div>
				<div class="clearfix"> </div>
		</div>
	</div>	
	<!--content-->
	<div class="content">
		
			<div class="content-news">

						<div class="content-team team-set">
			<div class="container">
				<h4> CHOICES STUDENTS</h4>
				<div class="team-left team-got">
				<p><?php echo GetDetails('student');?> </p>	
<br><br>
				<h4> REGISTER</h4>				
					<iframe width="100%" height="600" src="admin/registerStudent.php" frameborder="0" allowfullscreen></iframe>
					
					<div class="clearfix"> </div>
				</div>
			</div>
		</div>
			<!--
		<div class="content-team">
			<div class="container">
				<h3><span>OUT TEA</span>M</h3>
				<div class="team-left">
					<div class=" team-top">
					<a href="#"><img class="img-responsive mix-in" src="images/p1.jpg" alt="" /></a>
						<h6>Duis autem</h6>
						<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>
					</div>
					<div class=" team-top team-in">
						<a href="#"><img class="img-responsive  mix-in" src="images/p2.jpg" alt="" /></a>
						<h6>Duis autem</h6>
						<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>
					</div>
					<div class=" team-top">
						<a href="#"><img class="img-responsive  mix-in" src="images/p3.jpg" alt="" /></a>
						<h6>Duis autem</h6>
						<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>
					</div>
					<div class=" team-top top-team">
						<a href="#"><img class="img-responsive  mix-in" src="images/p4.jpg" alt="" /></a>
						<h6>Duis autem</h6>
						<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>
					</div>
					<div class="clearfix"> </div>
				</div>
			</div>
		</div>
		-->
		<div class="contact-me">
			<div class="container">
				<h3>DONATIONS</h3>
				<div class="contact-top">
					<div class="col-md-3 contact-fax">
						<a href="#"></span></a>
						<p></p>
					</div>
				
					<div class="col-md-3 contact-fax">
						<a href="#"><img src="https://fpdbs.paypal.com/dynamicimageweb?cmd=_dynamic-image&amp;buttontype=ecmark&amp;locale=en_US" alt="Acceptance Mark" class="v-middle"></span></a>
						<p>Paypal</p>
					</div>
							<div class="col-md-3 contact-fax">
						<a href="#"><a href='https://www.paynow.co.zw/Payment/Link/?q=c2VhcmNoPWVtdXJvaXdhJTQwZ21haWwuY29tJmFtb3VudD0xMC4wMCZyZWZlcmVuY2U9MTIzNDUmbD0w' target='_blank'><img src='https://www.paynow.co.zw/Content/Buttons/Medium_buttons/button_donate_medium.png' style='border:0' /></a></span></a>
						<p>PayNow</p>
					</div>
				
					<div class="col-md-3 contact-fax">
						<a href="#"> </span></a>
						<p></p>
					</div>
					<div class="clearfix"> </div>
				</div>
			</div>
		</div>
	</div>
		
	</div>
	<!--footer-->
	<div class="footer">
		<div class="container">
				 <?php include 'footer.php';?>
		 </div>
	</div>
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42561254-2', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>