with API ... this source code is new ... version 0.3 ... you can get more tools from www.tools.eti.pw

<!DOCTYPE html>
<html lang="en">
<head>
<title>IP Address Lookup</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="IP Lookup">
<meta name="keywords" content="ip lookup, what is my ip, my ip address, my ip, ip address lookup, ip geolocation, latitude longitude finder, ip lookup php script, ip2location, geolocation, ip-location, my ip lookup, ip-lookup, geoip, geo ip, ip finder, ip tools, ip tools, ip location finder, location finder, what is my ip location, ip address geolocation, моето айпи, моят айпи адрес, моето ip, покажи айпи">
<meta name="author" content="ETI's Free Stuff - www.eti.pw">
</head>
<body>

<h2>Lookup IP Address Location</h2>

<br>

<?php

$IP = $_SERVER['REMOTE_ADDR'];
$ip = htmlentities($_GET["ip"]);
$hostname = gethostbyaddr($_GET['ip']);

$latitude = htmlentities($_POST['latitude'], ENT_QUOTES, 'UTF-8');
$longitude = htmlentities($_POST['longitude'], ENT_QUOTES, 'UTF-8');
$city = htmlentities($_POST['city'], ENT_QUOTES, 'UTF-8');

// ipapi.com provide api too

// old API without login from: http://freegeoip.net
// https://github.com/apilayer/freegeoip
// $location = json_decode(file_get_contents('http://freegeoip.net/json/'.$ip));

// with new API with login from: http://ipstack.com (www.freegeoip.net) | LogIn there to get your own access key... It's free
// the limit is: 10000 requests on month...
// read documentation: https://ipstack.com/documentation
$location = json_decode(file_get_contents('http://api.ipstack.com/'.$ip.'?access_key=Your-API-Key-here&format=1'));
// with Api from www.ipinfo.io without apikey
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
// API from ip-api.com without access key
$more = json_decode(file_get_contents("http://ip-api.com/json/{$ip}?fields=status,message,continent,continentCode,country,countryCode,region,regionName,city,district,zip,lat,lon,timezone,currency,isp,org,as,asname,reverse,mobile,proxy,query"));

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
// echo "<br><b>Calling code: </b>" .$location->calling_code; // it was by this way before time
echo "<br><b>Calling code: </b>" .$location->calling_code; // api's changed ... you can remove this: $location->calling_code;   in this line... or leave it
$calling_code = $location->location->calling_code; // that's way i use this silly code now :)
echo $calling_code;

echo "<br><b>Latitude: </b>" .$location->latitude;
echo "<br><b>Longitude: </b>" .$location->longitude;

echo "<br><b>Timezone: </b>" .$more->timezone;
echo "<br><b>Currency: </b>" .$more->currency;
echo "<br><b>Mobile: </b>" .$more->mobile;
echo "<br><b>Proxy: </b>" .$more->proxy;

// no more of these 2 :(
// echo "<br><b>Time zone: </b>" .$location->time_zone;
// echo "<br><b>Metro code: </b>" .$location->metro_code;

echo "<br><b>Organization: </b>" .$details->org;
echo "<br><b>Host: </b>" .$hostname;
echo "<br><b>Your Browser User-Agent String: </b>" .$_SERVER['HTTP_USER_AGENT']; //or remove this Browser User-Agent :)
//echo  $_SERVER['HTTP_USER_AGENT'];

echo "<br><br><b>Short View</b><br>";
echo "<b>IP: </b>" .$details->ip;
echo "<br><b>Country code: </b>" .$details->country;
echo "<br><b>City: </b>" .$details->city;
echo "<br><b>Region: </b>" .$details->region;
echo "<br><b>Postal: </b>" .$details->postal;
echo "<br><b>Hostname: </b>" .$details->hostname;
echo "<br><b>Organization: </b>" .$details->org;
echo "<br><b>Location: </b>" .$details->loc;

echo <<<HTML
<br><br><b>Geolocation Map</b>
<form action="" method="post">
<input type="text" name="city" value="$location->city" />
<input type="submit" class="button" value="Show City on the Map" />
</form>
HTML;

if(isset($_POST['city'])){
// echo "<iframe src='https://google-developers.appspot.com/maps/documentation/utils/geocoder/#q%3D{$city}' width='100%' height='900' FRAMEBORDER=NO FRAMESPACING=0 BORDER=0 ></iframe>";
echo "<iframe src='https://developers-dot-devsite-v2-prod.appspot.com/maps/documentation/utils/geocoder?hl=pt-br#q%3D{$city}' width='100%' height='900' FRAMEBORDER=NO FRAMESPACING=0 BORDER=0 ></iframe>";
}

echo <<<HTML
<br><b>Map Latitude Longitude</b>
<form action="" method="post">
<input type="text" name="address" value="$location->latitude" placeholder="latitude" title="Latitude" /><input type="text" name="address" value="$location->longitude" placeholder="longitude" title="Longitude" />
</form>
HTML;

// echo "<iframe src='https://google-developers.appspot.com/maps/documentation/utils/geocoder/#q%3D{$location->latitude}%2520{$location->longitude}' width='100%' height='900' FRAMEBORDER=NO FRAMESPACING=0 BORDER=0 ></iframe><br>";
echo "<iframe src='https://developers-dot-devsite-v2-prod.appspot.com/maps/documentation/utils/geocoder?hl=pt-br#q%3D{$location->latitude}%2520{$location->longitude}' width='100%' height='900' FRAMEBORDER=NO FRAMESPACING=0 BORDER=0 ></iframe><br>";
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
<li>Timezone</li>
<li>Currency</li>
<li>Mobile</li>
<li>Proxy</li>
<li>Organization</li>
<li>Hostname</li>
<li>Your Browser User-Agent</li>
<li>Geolocation Map</li>
<li>Map Latitude Longitude finder</li>
";
}

?>

<?php

if(isset($_GET['ip'])){

if(isset($_POST['address'])){
// echo "<iframe src='https://google-developers.appspot.com/maps/documentation/utils/geocoder/#q%3D{$location->latitude}%2520{$location->longitude}' width='100%' height='900' FRAMEBORDER=NO FRAMESPACING=0 BORDER=0 ></iframe>";
echo "<iframe src='https://developers-dot-devsite-v2-prod.appspot.com/maps/documentation/utils/geocoder?hl=pt-br#q%3D{$location->latitude}%2520{$location->longitude}' width='100%' height='900' FRAMEBORDER=NO FRAMESPACING=0 BORDER=0 ></iframe>";
}			

if(isset($_POST['latitude'])){

print ('<br><b>Map Latitude Longitude finder</b>
<form action="" method="post">
Enter a latitude/longitude:
<input type="text" name="latitude" id="latitude" placeholder="latitude" /><input type="text" name="longitude" id="longitude" placeholder="longitude" />
<input type="submit" class="button" value="Go to this Location" /><br />
<small>(You can put any latitude/longitude to see the location on the map)</small><br>
<small>e.g. 27.3717 -81.4306</small>
</form>');

// echo "<iframe src='https://google-developers.appspot.com/maps/documentation/utils/geocoder/#q%3D{$latitude}%2520{$longitude}' width='100%' height='900' FRAMEBORDER=NO FRAMESPACING=0 BORDER=0 ></iframe>";
echo "<iframe src='https://developers-dot-devsite-v2-prod.appspot.com/maps/documentation/utils/geocoder?hl=pt-br#q%3D{$latitude}%2520{$longitude}' width='100%' height='900' FRAMEBORDER=NO FRAMESPACING=0 BORDER=0 ></iframe>";
}

else {

print ('<br><b>Map Latitude Longitude finder</b>
<form action="" method="post">
Enter a latitude/longitude:
<input type="text" name="latitude" id="latitude" placeholder="latitude" /><input type="text" name="longitude" id="longitude" placeholder="longitude" />
<input type="submit" class="button" value="Go to this Location" /><br />
<small>(You can put any latitude/longitude to see the location on the map)</small><br>
<small>e.g. 27.3717 -81.4306</small>
</form>');

}

}				
?>
