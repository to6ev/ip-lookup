<!DOCTYPE html>
<html lang="en">
<head>
<title>IP Address Lookup</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="IP Lookup">
<meta name="keywords" content="ip lookup"/>
<meta name="author" content="ETI Free Stuff - www.eti.pw">
<meta name="robots" content="all"/>
<style type="text/css">
	body{color:#666;text-align:center;font-family:arial;font-size:.8em;}
	body,td{font:16px/20px "Lucida Grande","Lucida Sans Unicode",Verdana,Arial,sans-serif}
	a{border-bottom:1px solid #ddd;color:#21759b;text-decoration:none}
	a:hover,a:focus{color:green;border-color:#d54e21}
	h1{color:#000;font:46px/52px Georgia,"Bitstream Vera Serif","Times New Roman",serif;}
	p,form{margin: 10px 0 0 0}
	ul,li{margin:0;padding:0}
	li{list-style: disc inside;padding-left:10px}
	#gmap_canvas{width:100%;height:30em;}
</style>

</head>

<body>

<h1><a href="./">Lookup IP Address Location</a></h1>

<br>

<?php
$IP = $_SERVER['REMOTE_ADDR'];
$ip = htmlentities($_GET["ip"]);
$hostname = gethostbyaddr($_GET['ip']);
$location = json_decode(file_get_contents('http://freegeoip.net/json/'.$ip));   // with API :) yeah!
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));       // other API yep :)

if(isset($_GET['ip']))
{
echo '<form method="get" action="">
<input type="text" name="ip" id="ip" style="width:100px;height:20px;" maxlength="15" placeholder="IP" title="Enter IP Address here" />
<input type="submit" style="width:125px;height:25px;color:blue" value="Lookup IP Address" />
</form>';
echo "<br><b>General IP Information</b>";
echo "<br><b>IP: </b>" .$location->ip;
echo "<br><b>Country name: </b>" .$location->country_name;
echo "<br><b>Country code: </b>" .$location->country_code;
echo "<br><b>City: </b>" .$location->city;
echo "<br><b>State/Region: </b>" .$location->region_name;
echo "<br><b>Region code: </b>" .$location->region_code;
echo "<br><b>Zip code: </b>" .$location->zip_code;
echo "<br><b>Time zone: </b>" .$location->time_zone;
echo "<br><b>Latitude: </b>" .$location->latitude;
echo "<br><b>Longitude: </b>" .$location->longitude;
echo "<br><b>Metro code: </b>" .$location->metro_code;
echo "<br><b>Organization: </b>" .$details->org;
echo "<br><b>Host: </b>" .$hostname;
echo "<br><b>Browser User-Agent String: </b>" .$_SERVER['HTTP_USER_AGENT'];

echo <<<HTML
<br><b>Geolocation Map:</b><br>
<form action="" method="post">
<input type="text" name="address" value="$location->city" />
<input type="submit" class="button" value="Show City on the Map" />
</form>
HTML;
}
else {

print ('<form method="get" action="">
<input type="text" name="ip" id="ip" style="width:100px;height:20px;" maxlength="15" placeholder="IP" title="Enter IP Address here" value="'.$IP.'" />
<input type="submit" style="width:125px;height:25px;color:blue" value="Lookup IP Address" />
</form>');
echo "<br>Here's what you will find out:<br>
<li>Country name</li>
<li>Country code</li>
<li>City</li>
<li>State/Region</li>
<li>Region code</li>
<li>Zip code</li>
<li>Time zone</li>
<li>Latitude</li>
<li>Longitude</li>
<li>Metro code</li>
<li>Organization</li>
<li>Hostname</li>
<li>Browser User-Agent</li>
<li>Map</li>
";

}
?>

<?php
/* you can use this api too :)
$ip = htmlentities($_GET["ip"]);
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
echo "IP: " .$details->ip;
echo "<br>Country: " .$details->country;
echo "<br>City: " .$details->city;
echo "<br>Region: " .$details->region;
echo "<br>Hostname: " .$details->hostname;
echo "<br>Organization: " .$details->org;
echo "<br>Location: " .$details->loc;
*/
?>
	
<?php
function geocode($address){

	$address = urlencode($address);
	$url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
	$resp_json = file_get_contents($url);
	$resp = json_decode($resp_json, true); 	

	if($resp['status']=='OK'){

		$lati = $resp['results'][0]['geometry']['location']['lat'];
		$longi = $resp['results'][0]['geometry']['location']['lng'];
		$formatted_address = $resp['results'][0]['formatted_address'];
		
		if($lati && $longi && $formatted_address){
		
			$data_arr = array();		
			
			array_push(
				$data_arr, 
					$lati, 
					$longi, 
					$formatted_address
				);
			
			return $data_arr;
			
		}else{
			return false;
		}
		
	}else{
		return false;
	}
}
?>

<?php
if($_POST){

	$data_arr = geocode($_POST['address']);

	if($data_arr){
		
		$latitude = $data_arr[0];
		$longitude = $data_arr[1];
		$formatted_address = $data_arr[2];			
?>

	<div id="gmap_canvas">Loading map...</div>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>    
	<script type="text/javascript">
		function init_map() {
			var myOptions = {
				zoom: 14,
				center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
			marker = new google.maps.Marker({
				map: map,
				position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
			});
			infowindow = new google.maps.InfoWindow({
				content: "<?php echo $formatted_address; ?>"
			});
			google.maps.event.addListener(marker, "click", function () {
				infowindow.open(map, marker);
			});
			infowindow.open(map, marker);
		}
		google.maps.event.addDomListener(window, 'load', init_map);
	</script>

	<?php
	}else{
		echo "ERROR: No map found!";
	}
}
?>
</body>
</html>
