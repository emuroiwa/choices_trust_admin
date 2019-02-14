<?php
	$today = date("Ymd");
$rand = strtoupper(substr(uniqid(sha1(time())),0,8));
echo $rand;
 

function UserData() {
	 $location = file_get_contents('http://freegeoip.net/json/'.$_SERVER['REMOTE_ADDR']);
  $obj = json_decode($location);

    $out['ip'] = $obj->{'ip'};
    $out['country_name'] = $obj->{'country_name'};
    $out['time_zone'] =$obj->{'time_zone'};
    return $out;
}

$data = UserData();
echo $data['ip'];
echo $data['country_name'];
echo $data['time_zone'];
?>