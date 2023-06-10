<head>
    <title>Tunna™ Link Shortener PROVIP®</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/tunnaduong.png" type="image/png">
    <meta charset="UTF-8">
</head>
<style>
h1 {
    margin-top: 0;
}

@media only screen and (max-width: 600px) {
    .ads {
        zoom: 0.55;
    }
}
</style>
<center>
    <img src="./logo.png" style="height: 60px">
    <?php
	// connect to database
	$conn = mysqli_connect("localhost", "tunnaduong_link", "Tunganh2003", "tunnaduong_link");
	mysqli_set_charset($conn, "utf8");
	// check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	// if there is no "size" get param in url, then redirect to that same url with "size" param equals to user's screen size in javascript code
	if (!isset($_GET['size'])) {
		// Print out Verifying your shortened URL code, please hang on... | Đang xác minh và kiểm tra an toàn mã code đường link đã rút gọn... ====>> STATUS: 200 OK! Verified<br>
		echo "<h1>Đang lấy link...<br>( ^_^)／</h1>Đang xác minh và kiểm tra an toàn mã code đường link đã rút gọn... // Verifying your shortened URL code, please hang on...<br><br><br>";
		echo "<hr>----WEBSITE INFO:----<br><br><h3 style='margin: 0;margin-top: 5px'>Tunna™ Link Shortener PROVIP®</h3><p style='margin: 0;'>Hệ thống rút gọn link chia sẻ bởi Dương Tùng Anh</p><br><img src='tunnaduong.png' style='width: 40px;margin: 10px 0 0 0;opacity: 0.5'><p style='opacity: 0.5'>(c) Tunna Duong 2022</p>";
	?>
    <script>
    // @TODO:
    // Check if user came from in-app browsers like Facebook app...
    // Check if url contains fbclid then remove it immediately
    // ...
      
    var is_fb_flag = false;
    if (/^\?fbclid=/.test(location.search)) {
      	is_fb_flag = true;
        location.replace(location.href.replace(/\?fbclid.+/, ""));
    } else {
	is_fb_flag = false;    
    }
    // ---
    if (typeof(window.screen.width) != 'undefined') {
        var width = window.screen.width;
        var height = window.screen.height;
        var size = width + 'x' + height;
        var url = window.location.href;
        url += '&size=' + size;
        // get ref url
        var ref_url = document.referrer ? document.referrer : is_fb_flag === true ? "Facebook Click ID" : "New tab";
        url += '&ref=' + encodeURIComponent(ref_url);
        // if url contain ? then remove it
        if (url.indexOf('?') != -1) {
            url = url.replace('?', '&');
        }
        // replace https://tunnal.ink with base url plus index.php?code= and the other params
        url = url.replace('https://tunnal.ink/', 'https://tunnal.ink/?code=');
        window.location.href = url;
      window.history.replaceState(null, null, 'https://tunnal.ink/');
    }
    </script>
    <?php
	} else {

		// import functions file named "func.php"
		// require_once("func.php");

		function getOS()
		{
			$userAgent = $_SERVER['HTTP_USER_AGENT'];

			$osPlatform    =   "Unknown";

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

		// if url having "platform" parameter, then set the "platform" variable to the value of the parameter else set it to "Unknown"
		if (isset($_GET['p'])) {
			$platform = $_GET['p'];
		} else {
			$platform = "Unknown";
		}

		// if there is a http referer header, then set the "ref_url" variable to the value of the header else set it to "Unknown"
		if (isset($_GET['ref'])) {
			$ref_url = $_GET['ref'];
		} else {
			$ref_url = "Unknown";
		}

		// if the page is refreshed, set the ref_url to "Page refreshed"
		if (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] == 'max-age=0') {
			$ref_url = "Page refreshed";
		}

		// check for user's browser name
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) {
			$browser = 'Internet Explorer';
		} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) { //For Supporting IE 11
			$browser = 'Internet Explorer';
		} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) {
			$browser = 'Mozilla Firefox';
		} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) {
			$browser = 'Google Chrome';
		} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE) {
			$browser = "Opera Mini";
		} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE) {
			$browser = "Opera";
		} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE) {
			$browser = "Safari";
		} else {
			$browser = 'Other';
		}

		// get user's IP address
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

		// insert all info above to database
		if (isset($_GET['code'])) {
			$code = $_GET['code'];
		} else {
			$code = "Unknown";
		}

		// get user screen size get param
		if (isset($_GET['size'])) {
			$size = $_GET['size'];
		} else {
			$size = "Unknown";
		}
      
      		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		$sql = "INSERT INTO tracker (ref_code, platform, ref_url, ip_address, location, screen_size, browser, OS, browser_user_agent) VALUES ('$code', '$platform', '$ref_url', '$user_ip', '$location', '$size', '$browser', '$operating_system', '$user_agent')";
		// insert db
		$conn->query($sql);

		// if user is visiting the page without entering get parameters, show a message
		if (!isset($_GET['code']) || empty($_GET['code'])) {
			echo "<h1>Tút tút tít tít 110011100010011111...<br>／༼ ༏༏ີཻ༾ﾍ ༏༏ີཻ༾༾༽༽ </h1>Ngài Cô_đơn_lẻ_loi_9x ơi, đang nghịch gì đó? Cái web rau rau này có gì đâu :'( Bấm vào link trước đấy mà xem đi còn chờ chiii<br><br><br>
        <script>
	window.history.replaceState(null, null, 'https://tunnal.ink/');
 </script>
        
        ";
		} else {
			// check the database for matching get "code" parameters
			$code = $_GET['code'];
			$query = "SELECT * FROM links WHERE code='$code'";
			$result = mysqli_query($conn, $query);
			// handle mysql errors
			if (!$result) {
				die("Query failed");
			}
			if (mysqli_num_rows($result) == 1) {
				// if there is a match, count total views of the link and update the database
				// select * from tracker where ref_code = $code
				$query = "SELECT * FROM tracker WHERE ref_code='$code'";
				$result2 = mysqli_query($conn, $query);
				$total_views = mysqli_num_rows($result2);
				$query = "UPDATE links SET total_views='$total_views' WHERE code='$code'";
				mysqli_query($conn, $query);
				// get url from db, redirect to that url
				$row = mysqli_fetch_assoc($result);
				$url = $row['next_url'];
		?>
    <script>
    document.title = "<?php 
              if ($row['link_title'] !== "") {
              echo "\"". $row['link_title'] . " | Tunna™ Link Shortener PROVIP®";
                }
      		else {
             echo "Tunna™ Link Shortener PROVIP®";
              }
      ?>";
    </script>
    <h1 id='wait'>Đợi chút xíu tầm <span id='sec'>69</span> giây thôi bạn nhé!!!<br>┗(^o^　)┓三</h1>
    Đang chuyển hướng đến đường dẫn được chia sẻ... // Redirecting to content, please wait a second...<br><br>

    <a href='<?php
							if (isset($row['ads_click_url']) && !empty($row['ads_click_url'])) {
								echo $row['ads_click_url'];
							} else {
								echo "/hamhoc";
							}
							?>' style='zoom: 0.7;display: block'>
        <img class='ads' src='<?php
											if (isset($row['ads_img_url']) && !empty($row['ads_img_url'])) {
												echo $row['ads_img_url'];
											} else {
												echo "./img/ads/hamhoc_ads_campaign.jpg";
											}
											?>' style="height: 600px;border: 1px solid black;border-radius: 10px;">
    </a>
    <p>--------- Được tài trợ bởi: <?php
												if (isset($row['ads_promoted_by']) && !empty($row['ads_promoted_by'])) {
													echo $row['ads_promoted_by'];
												} else {
													echo "Ham Học Channel";
												}
												?> ----------</p>

    <br><br>
    <script>
    // @WEBSITE CONFIG PARAMS:
    const WAIT_SECONDS = <?php echo $row['wait_seconds'] ?>; // 6 secs
    const COUNTDOWN_DELAY = <?php echo $row['countdown_delay'] ?>; // 900 mil
    const REDIRECT_DELAY = <?php echo $row['redirect_delay'] ?>; // 7500 mil

    // Countdown timer
    var temp = WAIT_SECONDS;
    var timer = setInterval(function() {
        temp--;
        document.getElementById("sec").innerHTML = temp;
        if (temp <= 0) {
            temp = 0;
            document.getElementById("sec").innerHTML = 0;
            clearInterval(timer);
            // Change some texts if you want.. :)
            document.getElementById("wait").innerHTML =
                "Xong ròiii, đợi lâu lắm hong :v? Gét gô... <br> ᕕ(︶‿︶)ᕗ";
        }
    }, COUNTDOWN_DELAY);

    // Enough shits, let's redirect our customers with the desired link!
    setTimeout(function() {
       // window.location.replace("
      window.location.href = "<?php echo $url ?>";
    }, REDIRECT_DELAY);
    </script>
    <?php
			} else {
				echo "<h1>Oh no... Lỗi 404!!!!!<br>(∿°○°)∿ ︵ ǝʌol</h1>Mã link rút gọn sai mất tiêu rùi TvT! Vui lòng bật mode cẻnh xát chính tả rồi thử lại sau một lát vì có thể link đã bị sai, hết hạn hoặc thằng Tunna vẫn đang cập nhật link :V ...<br><br><br>
            <script>
	window.history.replaceState(null, null, 'https://tunnal.ink/');
 </script>
            
            ";
			}
		}
	}
	// @SITE FOOTER
	echo "<hr>---- WEBSITE INFO ----<br><br><h3 style='margin: 0;margin-top: 5px'>Tunna™ Link Shortener PROVIP®</h3><p style='margin: 0;'>Hệ thống rút gọn link chia sẻ bởi Dương Tùng Anh</p><br><img src='tunnaduong.png' style='width: 40px;margin: 10px 0 0 0;opacity: 0.5'><p style='opacity: 0.5'>(c) Tunna Duong 2022 - ". date("Y") ."</p><br><br><br>Website hiện vẫn đang trong quá trình nâng cấp giao diện! Mọi ý kiến góp ý vui lòng điền vào biểu mẫu tại: <a href='https://tunnal.ink/DongGopYKien'>tunnal.ink/DongGopYKien</a><br><br><sup>ver: 23.06.01.183h73N72HKP4444(a)</sup>";
	?>
</center>
