<?php
session_start();
/*********************************************
1. Define Constants
*****session_start();****************************************/

include "../../functions.php";

define('ps_error', 'Error');
define('ps_ok','Ok');
define('ps_created_but_not_paid','created but not paid');
define('ps_cancelled','cancelled');
define('ps_failed','failed');
define('ps_paid','paid');
define('ps_awaiting_delivery','awaiting delivery');
define('ps_delivered','delivered');
define('ps_awaiting_redirect','awaiting redirect');
define('site_url', 'http://muroiwa.me/kuda/final/kuda/scripts/');

/*********************************************
2. sitewide variables, settings
*********************************************/
$order_id = ''; //current shopping session ID, we set it down there later (this is managed by your shopping cart)
$integration_id ='4975';
$integration_key = '3f00f77b-5463-4db2-a2a8-cdcef830e668'; //oops this MUST BE SECRET, take it from a Database, encrypted or something
$authemail = '';
$initiate_transaction_url = 'https://www.paynow.co.zw/Interface/InitiateTransaction';
$orders_data_file = 'ordersdata.ini';
$checkout_url = site_url.'http://muroiwa.me/kuda/final/kuda/scripts/paynow.php';
$transaction_type = "";

/*********************************************
3. site routing
*********************************************/
$action = isset($_GET['action']) ? $_GET['action'] : 'default';
switch ($action)
{
	case 'createtransaction': //create or initiate a transaction on paynow
		WereCreatingATransaction();
	break;
	
	case 'return': //entry point when returning/redirecting from paynow
		WereBackFromPaynow();
	break;
	
	case 'notify': //listen for transaction-status paynow
		PaynowJustUpdatingUs();
	break;
	
	default: //default	
		JustTheDefault();
	break;
	
}

//route entry functions
function JustTheDefault()
{
	global $order_id;
	//Unique Order Id for current shopping session

	$order_id = GetOrderNumber(); //lets just use simple numbers here, 1-60, but yours should be the order number of the current shopping session, one you can use to identify this particular transaction with for eternity, in your shopping cart.	
	getShoppingCartHTML();
}

function WereBackFromPaynow()
{
	global $integration_id;
	global $integration_key;
	global $checkout_url;
	global $orders_data_file;

	$local_order_id = $_GET['order_id'];


	//Lets get our locally saved settings for this order
	$orders_array = array();
	if (file_exists($orders_data_file))
	{
		$orders_array = parse_ini_file($orders_data_file, true);
	}
	
	$order_data = $orders_array['OrderNo_'.$local_order_id];
	
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $order_data['pollurl']);
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	//execute post
	$result = curl_exec($ch);

	if($result) {

		//close connection
		$msg = ParseMsg($result);
		
		$MerchantKey =  $integration_key;
		$validateHash = CreateHash($msg, $MerchantKey);

		if($validateHash != $msg["hash"]){
			header("Location: $checkout_url");
		}
		else
		{
			/***** IMPORTANT ****
			On Paynow, payment status has changed, say from Awaiting Delivery to Delivered
			
				Here is where you
				1. Update your local shopping cart of Payment Status etc and do appropriate actions here, Save data to DB
				2. Email, SMS Notifications to customer, merchant etc
				3. Any other thing
			
			*** END OF IMPORTANT ****/
			//1. Lets write the updated settings

						//ernest update sales 
						//ernest update sales		
						     	UpdateSales($local_order_id,'Paid');
								$type=GetTType($local_order_id);	
foreach ($orders_array as $key=>$elem) { 
  
            foreach ($elem as $key2=>$elem2) { 
                if(is_array($elem2)) 
                { 
                    for($i=0;$i<count($elem2);$i++) 
                    { 
					
                     
                        	
                       
                    } 
                } 
               
            } 
        } 
			
	UpdateSales($local_order_id,$orders_array['status']);
                        	 
			$orders_array['OrderNo_'.$local_order_id] = $msg;
			$orders_array['OrderNo_'.$local_order_id]['returned_from_paynow'] = 'yes';
			
			write_ini_file($orders_array, $orders_data_file, true);	
		}
	}
	
	//Thank	your customer
	getBackFromPaynowHTML($local_order_id,$type);
}

function PaynowJustUpdatingUs()
{
	global $integration_id;
	global $integration_key;
	global $checkout_url;
	global $orders_data_file;

	$local_order_id = $_GET['order_id'];
	
	//write a file to show that paynow silently visisted us sometime
	file_put_contents('choices_log.txt', date('d m y h:i:s').'   Paynow visited us for order id '.$local_order_id.'\n', FILE_APPEND | LOCK_EX);
		
	//Lets get our locally saved settings for this order
	$orders_array = array();
	if (file_exists($orders_data_file))
	{
		$orders_array = parse_ini_file($orders_data_file, true);
	}
	
	$order_data = $orders_array['OrderNo_'.$local_order_id];
	
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $order_data['pollurl']);
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	//execute post
	$result = curl_exec($ch);

	if($result) {

		//close connection
		$msg = ParseMsg($result);
		
		$MerchantKey =  $integration_key;
		$validateHash = CreateHash($msg, $MerchantKey);

		if($validateHash != $msg["hash"]){
			header("Location: $checkout_url");
		}
		else
		{
			/***** IMPORTANT ****
			On Paynow, payment status has changed, say from Awaiting Delivery to Delivered
			
				Here is where you
				1. Update your local shopping cart of Payment Status etc and do appropriate actions here, Save data to DB
				2. Email, SMS Notifications to customer, merchant etc
				3. Any other thing
			
			*** END OF IMPORTANT ****/
			
			
	//ernest update sales			
foreach ($orders_array as $key=>$elem) { 
  
            foreach ($elem as $key2=>$elem2) { 
                if(is_array($elem2)) 
                { 
                    for($i=0;$i<count($elem2);$i++) 
                    { 
                        if($key2=='status'){
                        	UpdateSales($local_order_id,"Paid");
                        	 
                        }
                    } 
                } 
               
            } 
        } 
			
			//1. Lets write the updated settings
			$orders_array['OrderNo_'.$local_order_id] = $msg;
			$orders_array['OrderNo_'.$local_order_id]['visited_by_paynow'] = 'yes';
			
			write_ini_file($orders_array, $orders_data_file, true);	
		}
	}
	exit;	
}

function WereCreatingATransaction()
{
	global $_POST;
	global $integration_id;
	global $integration_key;
	global $authemail;
	global $initiate_transaction_url;
	global $orders_data_file;
	global $checkout_url;
	$local_order_id = $_POST['order_id'];
	

	//set POST variables
	$values = array('resulturl' => $_POST['resulturl'],
			'returnurl' =>  $_POST['returnurl'],
			'reference' =>  $_POST['reference'],
			'amount' =>  $_POST['amount'],
			'id' =>  $integration_id,
			'additionalinfo' =>  $_POST['additionalinfo'],
			'authemail' =>  $authemail,
			'status' =>  'Message'); //just a simple message
			
	$fields_string = CreateMsg($values, $integration_key);
	
	//open connection
	$ch = curl_init();
	$url = $initiate_transaction_url;
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//execute post
	$result = curl_exec($ch);

	if($result)
	{
		$msg = ParseMsg($result);
		
		//first check status, take appropriate action
		if ($msg["status"] == ps_error){
			//var_dump($msg);exit;
			//header("Location: $checkout_url");
			//exit;
			$error = $msg['error'];
		}
		else if ($msg["status"] == ps_ok){
			
			//second, check hash
			$validateHash = CreateHash($msg, $integration_key);
			if($validateHash != $msg["hash"]){
				$error =  "Paynow reply hashes do not match : " . $validateHash . " - " . $msg["hash"];
			}
			else
			{
				$theProcessUrl = $msg["browserurl"];

				/***** IMPORTANT ****
				On User has approved paying you, maybe they are awaiting delivery etc
				
					Here is where you
					1. Save the PollURL that we will ALWAYS use to VERIFY any further incoming Paynow Notifications FOR THIS PARTICULAR ORDER
					1. Update your local shopping cart of Payment Status etc and do appropriate actions here, Save any other relavant data to DB
					2. Email, SMS Notifications to customer, merchant etc
					3. Any other thing
				
				*** END OF IMPORTANT ****/
								$orders_array = array();

	//ernest update sales			
foreach ($orders_array as $key=>$elem) { 
  
            foreach ($elem as $key2=>$elem2) { 
                if(is_array($elem2)) 
                { 
                    for($i=0;$i<count($elem2);$i++) 
                    { 
                        if($key2==status){
                        	UpdateSales($local_order_id,$elem2[$i]);
                        	 
                        }
                    } 
                } 
               
            } 
        } 



				//1. Saving mine to a PHP.INI type of file, you should save it to a db etc
					if (file_exists($orders_data_file))
				{
					$orders_array = parse_ini_file($orders_data_file, true);
				}
				
				$orders_array['OrderNo_'.$local_order_id] = $msg;
				
				write_ini_file($orders_array, $orders_data_file, true);				
				
			}
		}
		else {						
			//unknown status or one you dont want to handle locally
			$error =  "Invalid status in from Paynow, cannot continue.";
		}

	}
	else
	{
	   $error = curl_error($ch);
	}
	
	//close connection
	curl_close($ch);

			
	//Choose where to go
	if(isset($error))
	{
		//back to checkout, show the user what they need to do
		//header("Location: $checkout_url");
		echo $error;exit;
	}
	else
	{
	
//ernest  saving to datatbase
		SetSales($_SESSION['username'],$_SESSION['price'],UserRegion(),$local_order_id,'Paynow',$_SESSION['transactiontype'],$_SESSION['booktype']);

		//redirect to paynow for user to complete payment
		header("Location: $theProcessUrl");
	}
	exit;	
	
}
?>

<?php
/*********************************************
Helper Functions
*********************************************/
function ParseMsg($msg) {
	$parts = explode("&",$msg);
	$result = array();
	foreach($parts as $i => $value) {
		$bits = explode("=", $value, 2);
		$result[$bits[0]] = urldecode($bits[1]);
	}

	return $result;
}

function UrlIfy($fields) {
	$delim = "";
	$fields_string = "";
	foreach($fields as $key=>$value) {
		$fields_string .= $delim . $key . '=' . $value;
		$delim = "&";
	}

	return $fields_string;
}

function CreateHash($values, $MerchantKey){
	$string = "";
	foreach($values as $key=>$value) {
		if( strtoupper($key) != "HASH" ){
			$string .= $value;
		}
	}
	$string .= $MerchantKey;
	
	$hash = hash("sha512", $string);
	return strtoupper($hash);
}

function CreateMsg($values, $MerchantKey){
	$fields = array();
	foreach($values as $key=>$value) {
	   $fields[$key] = urlencode($value);
	}

	$fields["hash"] = urlencode(CreateHash($values, $MerchantKey));

	$fields_string = UrlIfy($fields);
	return $fields_string;
}	

//cutom function to write php config type of file from array
function write_ini_file($assoc_arr, $path, $has_sections=FALSE) { 
    $content = ""; 
    if ($has_sections) { 
        foreach ($assoc_arr as $key=>$elem) { 
            $content .= "[".$key."]\n"; 
            foreach ($elem as $key2=>$elem2) { 
                if(is_array($elem2)) 
                { 
                    for($i=0;$i<count($elem2);$i++) 
                    { 
                        $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
                    } 
                } 
                else if($elem2=="") $content .= $key2." = \n"; 
                else $content .= $key2." = \"".$elem2."\"\n"; 
            } 
        } 
    } 
    else { 
        foreach ($assoc_arr as $key=>$elem) { 
            if(is_array($elem)) 
            { 
                for($i=0;$i<count($elem);$i++) 
                { 
                    $content .= $key2."[] = \"".$elem[$i]."\"\n"; 
                } 
            } 
            else if($elem=="") $content .= $key2." = \n"; 
            else $content .= $key2." = \"".$elem."\"\n"; 
        } 
    } 

    if (!$handle = fopen($path, 'w')) { 
        return false; 
    } 
    if (!fwrite($handle, $content)) { 
        return false; 
    } 
    fclose($handle); 
    return true; 
}

?>

<?php
/*********************************************
HTML Functions
*********************************************/
?>

<?php
function getShoppingCartHTML()
{
	global $order_id;
?>
<div align="center"><br><br><br>
<form name="frmSellingMilk" action="?action=createtransaction" method="post">
			<span>Choices Transaction  (Just $<?php echo $_SESSION['price'] ;?>)</span>
			<input type='hidden' name='resulturl' value='<?php echo site_url; ?>/paynow.php?action=notify&order_id=<?php echo $order_id; ?>' />
			<input type='hidden' name='returnurl' value='<?php echo site_url; ?>/paynow.php?action=return&order_id=<?php echo $order_id; ?>' />
			<input type='hidden' name='reference' value='Order Number: <?php echo $order_id; ?>'/>
			<input type='hidden' name='amount' value='<?php echo $_SESSION['price'] ;?>'/>
			<input type='hidden' name='additionalinfo' value='<?php echo $_SESSION['address'] ;?>'/>		
			<input type='hidden' name='order_id' value='<?php echo $order_id; ?>'/>
			<button type='submit'>Paynow </button>
</form>
</div>
<?php }
?>

<?php
function getBackFromPaynowHTML($order_id,$type,$status)
{
global $transaction_type;
if($status!="Cancelled"){
	?>
		<?php if($type!="Donate"){
}
header("location: invoice.php?id=$order_id");
echo $type;
    				?>
<div>Thank you for your business, it's much appreciated! <a href="invoice.php?id=<?php echo $order_id; ?>">back to shopping cart</a></div>
	<?php }else{
header("location: invoiceprint.php?id=$order_id");

    			    				?>
    			    	<div>Thank you for your business, it's much appreciated! <a href="invoiceprint.php?id=<?php echo $order_id; ?>">back to shopping cart</a></div>
			

<?php }
}else{

	echo "<h3><font color='red'>Transaction Cancelled</font></h3>";
}
}
?>