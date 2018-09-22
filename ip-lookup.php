<!DOCTYPE html>
<html lang="en">
<head>
<title>IP Address Lookup</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="IP Lookup">
<meta name="keywords" content="ip lookup, what is my ip, my ip address, my ip, ip address lookup, ip geolocation, latitude longitude finder, ip lookup php script, моето айпи, моят айпи адрес, моето ip, покажи айпи"/>
<meta name="author" content="ETI's Free Stuff - www.eti.pw">
<meta name="robots" content="all"/>
</head>
<body>

<h2>Lookup IP Address Location</h2>

<br>

<?php

$IP = $_SERVER['REMOTE_ADDR'];
$ip = htmlentities($_GET["ip"]);
$hostname = gethostbyaddr($_GET['ip']);

// old API without login from: http://freegeoip.net
// https://github.com/apilayer/freegeoip
// $location = json_decode(file_get_contents('http://freegeoip.net/json/'.$ip));

// with new API with login from: http://ipstack.com (www.freegeoip.net) | LogIn there to get your own access key... It's free
// the limit is: 10000 requests on month...
$location = json_decode(file_get_contents('http://api.ipstack.com/'.$ip.'?access_key=yourkeyhere&format=1')); // access_key!!!
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json")); //we use free api from: ipinfo.io too... no need key

if(isset($_GET['ip']))
{
echo '<form method="get" action="">
<input type="text" name="ip" id="ip" maxlength="15" placeholder="IP" title="Enter IP Address here" />
<input type="submit" class="button" value="Lookup IP Address" />
</form>';
echo "<br><b>General IP Information</b>";
echo "<br><b>IP: </b>" .$location->ip;
echo "<br><b>IP type: </b>" .$location->type;
echo "<br><b>Continent code: </b>" .$location->continent_code;
echo "<br><b>Continent name: </b>" .$location->continent_name;
echo "<br><b>Country code: </b>" .$location->country_code;
echo "<br><b>Country name: </b>" .$location->country_name;
echo "<br><b>City: </b>" .$location->city;
echo "<br><b>State/Region: </b>" .$location->region_name;
echo "<br><b>Region code: </b>" .$location->region_code;
echo "<br><b>Zip code: </b>" .$location->zip; // it was zip_code before :)
echo "<br><b>Calling code: </b>" .$location->calling_code;
echo "<br><b>Latitude: </b>" .$location->latitude;
echo "<br><b>Longitude: </b>" .$location->longitude;

// no more free of these 2 ... the extensions are paid
// echo "<br><b>Time zone: </b>" .$location->time_zone;
// echo "<br><b>Metro code: </b>" .$location->metro_code;

echo "<br><b>Organization: </b>" .$details->org;
echo "<br><b>Host: </b>" .$hostname;
echo "<br><b>Your Browser User-Agent String: </b>" .$_SERVER['HTTP_USER_AGENT']; //or remove this:)
//echo  $_SERVER['HTTP_USER_AGENT'];

echo "<br><br>Short View:<br>";
echo "<b>IP: </b>" .$details->ip;
echo "<br><b>Country code: </b>" .$details->country;
echo "<br><b>City: </b>" .$details->city;
echo "<br><b>Region: </b>" .$details->region;
echo "<br><b>Postal: </b>" .$details->postal;
echo "<br><b>Hostname: </b>" .$details->hostname;
echo "<br><b>Organization: </b>" .$details->org;
echo "<br><b>Location: </b>" .$details->loc;

echo <<<HTML
<br><br><b>Geolocation Map:</b><br>
<form action="" method="post">
<input type="text" name="address" value="$location->city" />
<input type="submit" class="button" value="Show City on the Map" />
</form>
HTML;

echo <<<HTML
<br><b>Map Latitude Longitude finder:</b>
<form action="" method="post">
Enter a latitude/longitude:
<input type="text" name="address" value="$location->latitude $location->longitude" />
<input type="submit" class="button" value="Go to this Location" /><br> 
<small>(You can put any latitude/longitude to see the location on the map)</small><br>
<small>e.g. 27.3717 -81.4306</small>
</form>
HTML;
}

else {

print ('<form method="get" action="">
<input type="text" name="ip" id="ip" maxlength="15" placeholder="IP" title="Enter IP Address here" value="'.$IP.'" />
<input type="submit" class="button" value="Lookup IP Address" />
</form>');
echo "<br>Here's what you will find out:<br><br>
<li>Your IP (but you can check other IP)</li>
<li>IP type</li>
<li>Continent code</li> 
<li>Continent name</li> 
<li>Country code</li>
<li>Country name</li>
<li>City</li>
<li>State/Region</li>
<li>Region code</li>
<li>Zip code</li>
<li>Calling code</li>
<li>Latitude</li>
<li>Longitude</li>
<li>Organization</li>
<li>Hostname</li>
<li>Your Browser User-Agent</li>
<li>Map</li>
<li>Map Latitude Longitude finder</li>
";

}
?>
	
	<style>
	#gmap_canvas{
		width:100%;
		height:30em;
	}
	
	#map-label,
	#address-examples{
		margin:1em 0;
	}
	</style>

<?php
function geocode($address){

	// url encode the address
	$address = urlencode($address);
	
	// google map geocode free api url ... keyless old api
	// $url = "http://maps.google.com/maps/api/geocode/json?address={$address}"; // no need &key parameter :)

// Keyless access to Google Maps Platform is deprecated since June 11th,2018 ... Get KEY: cloud.google.com/maps-platform/maps
$url = "http://maps.google.com/maps/api/geocode/json?address={$address}&key=YOUR_API_KEY"; //key parameter contains your application's API key

	// get the json response
	$resp_json = file_get_contents($url);
	
	// decode the json
	$resp = json_decode($resp_json, true);

	// response status will be 'OK', if able to geocode given address 
	if($resp['status']=='OK'){

		// get the important data
		$lati = $resp['results'][0]['geometry']['location']['lat'];
		$longi = $resp['results'][0]['geometry']['location']['lng'];
		$formatted_address = $resp['results'][0]['formatted_address'];
		
		// verify if data is complete
		if($lati && $longi && $formatted_address){
		
			// put the data in the array
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

	// get latitude, longitude and formatted address
	$data_arr = geocode($_POST['address']);

	// if able to geocode the address
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
		echo "<br>ERROR: No map found!";
	}
}
?>
