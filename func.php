<?php
function cleanTrash()
{
 echo "
 <script>
	window.history.replaceState(null, null, 'https://tunnal.ink/');
 </script>
 "; 
}

function getOS()
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    $osPlatform    =   "Unknown OS Platform";

    $osArray       =   array(
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    );

    foreach ($osArray as $regex => $value) {

        if (preg_match($regex, $userAgent)) {
            $osPlatform    =   $value;
        }
    }

    return $osPlatform;
}

$operating_system = getOS();

if (!isset($_SERVER['HTTP_REFERER'])) {
    $campaign = "natural_visit";
    $ref_url = "new_tab";
} else {
    $campaign = "natural_click";
    $ref_url = $_SERVER["HTTP_REFERER"];
}
$is_page_refreshed = (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] == 'max-age=0');
if ($is_page_refreshed) {
    $campaign = "page_refresh";
    $ref_url = "";
}

if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
    $browser = 'Internet Explorer';
elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
    $browser = 'Internet Explorer';
elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
    $browser = 'Mozilla Firefox';
elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
    $browser = 'Google Chrome';
elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
    $browser = "Opera Mini";
elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
    $browser = "Opera";
elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
    $browser = "Safari";
else
    $browser = 'Khác';

function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}
$user_ip = getUserIP();
$details = json_decode(file_get_contents("http://ip-api.com/json/{$user_ip}"));
$location = "{$details->city}, {$details->country}";
$sql = "INSERT INTO referral (campaign, ref_url, ref_test_id, ip_address, location, OS, browser) VALUES ('$campaign', '$ref_url', '$ref_test_id', '$user_ip', '$location', '$operating_system', '$browser')";
$conn->query($sql);
$sql = "SELECT LAST_INSERT_ID() AS id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $GLOBALS['referral_id'] = $row["id"];
    }
}
$conn->close();