<?php
$number = "1111111111111";
$pattern = "/^00[0-9]{9}$/";
echo preg_match($pattern, $number);
?>