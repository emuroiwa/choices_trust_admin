<?php
include 'opendb.php';


$date = date('m/d/Y');
$time = date('m/d/Y - H:m:s');
$Today = date('y:m:d');
$user=$_SESSION['username'];
  $transactiontypevariable=$_SESSION['transactiontype'];
                                        $new = date('l, F d, Y', strtotime($Today));
  function getConnection() {
 mysql_connect('localhost','choicesroot','choicestrust');
mysql_select_db('choicestrust');
  }
   	function GetHardCopySales(){
		  $query = mysql_query("select * from booksales where booktype='HardCopy' and status<>'Done'");
		  return mysql_num_rows($query);
      
  }
//  getConnection();
	 	function GetPrice($type){
		  $query = mysql_query("SELECT * FROM `prices` where version='$type' ");

while($rw = mysql_fetch_array($query, MYSQL_ASSOC)){
  $topic=$rw['price'];
 
      
  }
  return $topic;
	}
	 	function GetEmail($ordernum){
		  $query = mysql_query("SELECT * FROM `booksales` where ordernumber='$ordernum' ");

while($rw = mysql_fetch_array($query, MYSQL_ASSOC)){
  $topic=$rw['user'];
 
      
  }
  return $topic;
	}
 function CheckDatexx($input){
	 
$date_format = 'Y-m-d';


$input = trim($input);
$time = strtotime($input);

$is_valid = date($date_format, $time) == $input;
if($is_valid)
return true;
else{
	return false;
	}
	 }
  	function SetSales($user,$ordertype,$region,$ordernumber,$platform,$transactiontype,$booktype){
		  $query = mysql_query("INSERT INTO `booksales` (`user`, `ordertype`, `region`, `transactiondate`,`ordernumber`,`status`,`platform`,`transactiontype`,`booktype`) VALUES ('$user', '$ordertype', '$region', NOW(),'$ordernumber','pending','$platform','$transactiontype','$booktype')");
		//  echo "INSERT INTO `booksales` (`user`, `ordertype`, `region`, `transactiondate`,`ordernumber`,`status`,`platform`) VALUES ('$user', '$ordertype', '$region', NOW(),'$ordernumber','pending','$platform')";
		  return true;
      
  }
  
function encrypt_decrypt($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

  	function UpdateSales($ordernumber,$status){
		  $query = mysql_query("update `booksales` set status='$status' where ordernumber='$ordernumber'");
		  return true;
      
  }
 	function GetCalender(){
		  $query = mysql_query("SELECT * FROM `calender` ");
while($rw = mysql_fetch_array($query, MYSQL_ASSOC)){
  $topic=$rw['topic'];
  $msg=$rw['msg'];
  $msgdate=$rw['msgdate'];
 
      
  }
  return array($topic,$msg,$msgdate);
	}
function GetMoney($id){
		  $query = mysql_query("SELECT * FROM `booksales`,users where booksales.`user`= users.username and ordernumber='$id' ");
while($rw = mysql_fetch_array($query, MYSQL_ASSOC)){

   $out['a'] = $rw['ordertype'];
    $out['b'] = $rw['address'];
    $out['c'] = $rw['platform'];
    $out['d'] = $rw['transactiontype'];

    return $out;
 
      
  }

	}
	
function GetOrderNumber(){
	$today = date("Ymd");
$rand = strtoupper(substr(uniqid(sha1(time())),0,8));
return $rand;

}
	function UserData() {
	 $location = file_get_contents('http://freegeoip.net/json/'.$_SERVER['REMOTE_ADDR']);
  $obj = json_decode($location);

    $out['ip'] = $obj->{'ip'};
    $out['country_name'] = $obj->{'country_name'};
    $out['time_zone'] =$obj->{'time_zone'};
    return $out;
}
function UserRegion() {
	 $location = file_get_contents('http://freegeoip.net/json/'.$_SERVER['REMOTE_ADDR']);
  $obj = json_decode($location);
    return $obj->{'country_name'};
}

	function SendEmail($company,$type,$subject,$body,$email){

 $body1='<style type="text/css">
			
			html { background-color:#E1E1E1; margin:0; padding:0; }
			body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
			table{border-collapse:collapse;}
			table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
			img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
			a {text-decoration:none !important;border-bottom: 1px solid;}
			h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}
			.ReadMsgBody{width:100%;} .ExternalClass{width:100%;} 
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;} 
			table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
			#outlook a{padding:0;} 
			img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;} 
			body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;}
			.ExternalClass td[class="ecxflexibleContainerBox"] h3 {padding-top: 10px !important;} 
			h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
			h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
			h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
			h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
			.flexibleImage{height:auto;}
			.linkRemoveBorder{border-bottom:0 !important;}
			table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}
			body, #bodyTable{background-color:#E1E1E1;}
			#emailHeader{background-color:#E1E1E1;}
			#emailBody{background-color:#FFFFFF;}
			#emailFooter{background-color:#E1E1E1;}
			.nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
			.emailButton{background-color:#205478; border-collapse:separate;}
			.buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
			.buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
			.emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
			.emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}			.emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
			.imageContentText {margin-top: 10px;line-height:0;}
			.imageContentText a {line-height:0;}
			#invisibleIntroduction {display:none !important;} 
			span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;} 
			span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
			span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
		
			.a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
			.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}


			
			@media only screen and (max-width: 480px){
			
				body{width:100% !important; min-width:100% !important;} 
				table[id="emailHeader"],
				table[id="emailBody"],
				table[id="emailFooter"],
				table[class="flexibleContainer"],
				td[class="flexibleContainerCell"] {width:100% !important;}
				td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
				
				td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
				img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
				img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}

	
				table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}

				
				table[class="emailButton"]{width:100% !important;}
				td[class="buttonContent"]{padding:0 !important;}
				td[class="buttonContent"] a{padding:15px !important;}

			}

		
			@media only screen and (-webkit-device-pixel-ratio:.75){
				
			}

			@media only screen and (-webkit-device-pixel-ratio:1){
				
			}

			@media only screen and (-webkit-device-pixel-ratio:1.5){
				
			}
			
			@media only screen and (min-device-width : 320px) and (max-device-width:568px) {

			}
			
		</style>
		
	</head>
	<body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

		
		<center style="background-color:#E1E1E1;">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
				<tr>
					<td align="center" valign="top" id="bodyCell">

						
						<table bgcolor="#E1E1E1" border="0" cellpadding="0" cellspacing="0" width="500" id="emailHeader">

							
							<tr>
								<td align="center" valign="top">
									
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												
											</td>
										</tr>
									</table>
									
								</td>
							</tr>
						

						</table>
						
						<table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">

						
							<tr>
								<td align="center" valign="top">
								
									<table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#3498db">
										<tr>
											<td align="center" valign="top">
												
											
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td align="center" valign="top" width="500" class="flexibleContainerCell">

														
															<table border="0" cellpadding="30" cellspacing="0" width="100%">
																<tr>
																	<td align="center" valign="top" class="textContent">
																		<h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">'.$company.'</h1>
																		<h2 style="text-align:center;font-weight:normal;font-family:Helvetica,Arial,sans-serif;font-size:23px;margin-bottom:10px;color:#205478;line-height:135%;">'.$type.'</h2>
	
																	</td>
																</tr>
															</table>
															

														</td>
													</tr>
												</table>
												
											</td>
										</tr>
									</table>
									
								</td>
							</tr>
							
							<tr mc:hideable>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // --><!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->


							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr style="padding-top:0;">
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // --><!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->


							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
										<tr>
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // -->
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td align="center" valign="top" width="500" class="flexibleContainerCell">
															<table border="0" cellpadding="30" cellspacing="0" width="100%">
																<tr>
																	<td align="center" valign="top">

																		<!-- CONTENT TABLE // -->
																		<table border="0" cellpadding="0" cellspacing="0" width="100%">
																			<tr>
																				<td valign="top" class="textContent">
																				
																	Good Day,<br>
'.$body.'
<br>

																	
																		

																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->


							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // --><!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->


							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // -->
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td align="center" valign="top" width="500" class="flexibleContainerCell">

															<!-- CONTENT TABLE // --><!-- // CONTENT TABLE -->

														</td>
													</tr>
												</table>
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->

							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // -->
												
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->


							<!-- MODULE DIVIDER // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												
														</td>
													</tr>
												</table>
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // END -->


							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // -->
												<table border="0" cellpadding="30" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td valign="top" width="500" class="flexibleContainerCell">

															<!-- CONTENT TABLE // --><!-- // CONTENT TABLE -->

														</td>
													</tr>
												</table>
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->


							<!-- MODULE DIVIDER // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
											
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // END -->


							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // -->
												<table border="0" cellpadding="30" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td valign="top" width="500" class="flexibleContainerCell">

															<!-- CONTENT TABLE // --><!-- // CONTENT TABLE -->

														</td>
													</tr>
												</table>
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->


							<!-- MODULE DIVIDER // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // -->
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td align="center" valign="top" width="500" class="flexibleContainerCell">
															<table class="flexibleContainerCellDivider" border="0" cellpadding="30" cellspacing="0" width="100%">
																<tr>
																	<td align="center" valign="top" style="padding-top:0px;padding-bottom:0px;">

																		

																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												
											</td>
										</tr>
									</table>
								
								</td>
							</tr>
							
							<tr>
								<td align="center" valign="top">
								
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
									
											</td>
										</tr>
									</table>
								
								</td>
							</tr>
						
							<tr>
								<td align="center" valign="top">
								
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												

																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											
											</td>
										</tr>
									</table>
								
								</td>
							</tr>
						
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // --><!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->


							<!-- MODULE DIVIDER // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												
														</td>
													</tr>
												</table>
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // END -->


							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // --><!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->


							<!-- MODULE DIVIDER // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // -->
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td align="center" valign="top" width="500" class="flexibleContainerCell">
															
														</td>
													</tr>
												</table>
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // END -->


							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // --><!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->


							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									
								</td>
							</tr>
							<!-- // MODULE ROW -->


							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // -->
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td valign="top" width="500" class="flexibleContainerCell">

															<!-- CONTENT TABLE // -->
														<!--	<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
																<tr>
																	<td align="left" valign="top" class="flexibleContainerBox" style="background-color:#5F5F5F;">
																		<table border="0" cellpadding="30" cellspacing="0" width="100%" style="max-width:100%;">
																			<tr>
																				<td align="left" class="textContent">
																					<h3 style="color:#FFFFFF;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">Left Column</h3>
																					<div style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis.</div>
																				</td>
																			</tr>
																		</table>
																	</td>
																	<td align="right" valign="top" class="flexibleContainerBox" style="background-color:#27ae60;">
																		<table class="flexibleContainerBoxNext" border="0" cellpadding="30" cellspacing="0" width="100%" style="max-width:100%;">
																			<tr>
																				<td align="left" class="textContent">
																					<h3 style="color:#FFFFFF;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">Right Column</h3>
																					<div style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis.</div>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>-->
															<!-- // CONTENT TABLE -->

														</td>
													</tr>
												</table>
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->


							<!-- MODULE ROW // -->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>
							<!-- // MODULE ROW -->

						</table>
						<!-- // END -->

				
						<table bgcolor="#E1E1E1" border="0" cellpadding="0" cellspacing="0" width="500" id="emailFooter">

							<!-- FOOTER ROW // -->
							<!--
								To move or duplicate any of the design patterns
								in this email, simply move or copy the entire
								MODULE ROW section for each content block.
							-->
							<tr>
								<td align="center" valign="top">
									<!-- CENTERING TABLE // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<!-- FLEXIBLE CONTAINER // -->
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td align="center" valign="top" width="500" class="flexibleContainerCell">
															<table border="0" cellpadding="30" cellspacing="0" width="100%">
																<tr>
																	<td valign="top" bgcolor="#E1E1E1">

																		<div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
																			<div>Copyright &#169; <a href="https://www.facebook.com/muroiwa?fref=ts" target="_blank" style="text-decoration:none;color:#828282;"><span style="color:#828282;">Divine Developers</span></a>. All&nbsp;rights&nbsp;reserved.</div>
																			

																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												<!-- // FLEXIBLE CONTAINER -->
											</td>
										</tr>
									</table>
									<!-- // CENTERING TABLE -->
								</td>
							</tr>

						</table>
						<!-- // END -->

					</td>
				</tr>
			</table>
		</center>
';

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'To: <'.$email.'>' . "\r\n";
$headers .= 'From: '.$company.' <noreply@example.com>' . "\r\n";

// Mail it
 if(!mail($to, $subject,$body1, $headers))
{
    echo "Mailer Error:";
	return false;
 } else {
    return true;
 }}
  ///*******************************************************************************************************************************************************************
		
		function SendEmailFile($email,$message){
$file = "choices.pdf";
$content = file_get_contents( $file);
$content = chunk_split(base64_encode($content));
$uid = md5(uniqid(time()));
$name = basename($file);

// header
$header = "From: Choices Trust <admin@choicestrust.com>\r\n";
$header .= "Reply-To: admin@choicestrust.com\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
$message="Congratulations for purchasing our choices trust book. Please find attached";
// message & attachment
$nmessage = "--".$uid."\r\n";
$nmessage .= "Content-type:text/plain; charset=iso-8859-1\r\n";
$nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$nmessage .= $message."\r\n\r\n";
$nmessage .= "--".$uid."\r\n";
$nmessage .= "Content-Type: application/octet-stream; name=\"choices.pdf\"\r\n";
$nmessage .= "Content-Transfer-Encoding: base64\r\n";
$nmessage .= "Content-Disposition: attachment; filename=\"choices.pdf\"\r\n\r\n";
$nmessage .= $content."\r\n\r\n";
$nmessage .= "--".$uid."--";

if (mail($email, "Choices Book Purchase", $nmessage, $header)) {
    return true; // Or do something here
} else {
  return false;
}
}

		//*********************************************************************************************************dsfd*******************************************************
		
		  function SendSMS($msgsms,$contact,$callerid){
			  //echo $contact;
$postUrl = "http://193.105.74.59/api/sendsms/xml";
// XML-formatted data
$xmlString =
"<SMS>
<authentification>
<username>ChoicesTrst</username>
<password>D1dfpkMK</password>
</authentification>
";

$xmlString.="
<message>
<sender>".$callerid."</sender>
<text>".$msgsms."</text>
<recipients>
<gsm>".$contact."</gsm>
</recipients>
</message>
";

$xmlString.="

</SMS>";
//echo $xmlString; exit;

// previously formatted XML data becomes value of "XML" POST variable
$fields = "XML=" . urlencode($xmlString);

// in this example, POST request was made using PHP's CURL
$ch = curl_init($postUrl);
curl_setopt($ch, CURLOPT_URL, $postUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
// response of the POST request
curl_exec($ch);
curl_close($ch);
return true;
		  }
	 	function GetDetails($type){
		  $query = mysql_query("SELECT * FROM `details` where type='$type' ");

while($rw = mysql_fetch_array($query, MYSQL_ASSOC)){
  $topic=$rw['details'];
 
      
  }
  return $topic;
	}
 
 	 	function GetTType($type){
		  $query = mysql_query("SELECT transactiontype FROM `booksales` WHERE ordernumber='$type' ");

while($rw = mysql_fetch_array($query, MYSQL_ASSOC)){
  $topic=$rw['transactiontype'];
 
      
  }
  return $topic;
	}
		
		
		function msg($msg){
			?>
				<script language="javascript">
					alert('<?php echo $msg;?>');
				</script>
			<?php
		}
		//msg('jfkjaj');
		function linkk($link){
			?>
				<script language="javascript">
					location = '<?php echo $link;?>';
				</script>
			<?php
		}
				//link('index.php') 

		function clean($str) {
                            $str = @trim($str);
                            if (get_magic_quotes_gpc()) {
                                $str = stripslashes($str);
                            }
							$db = getConnection();
                            $new=mysql_real_escape_string($str);
                  			$remove[] = "'";
							$remove[] = '"';
							$remove[] = "-"; // just as another example
							$word = str_replace($remove, "", $new);
							return $new;
							}
							//echo clean("hjd'''''kfd");
								
		
		
		?>
