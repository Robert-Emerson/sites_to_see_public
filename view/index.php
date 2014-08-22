<?php
	require 'flight/Flight.php';


	function view_trip($val) {

	$connecting_string = sprintf('mongodb://%s:%d/%s', 'ds047197.mongolab.com', 47197, 'mongolab_dbname');
        $connection=  new Mongo($connecting_string,array('username'=>'mongolab_username','password'=>'mongolab_password'));
        $dbname = $connection->selectDB('city_explorer');

        $itineraries = $dbname->selectCollection('mongolab_collection');
	$query = array('uid' => $val);
	$result = $itineraries->find($query);
	$centerLat = 0.0;
	$centerLon = 0.0;
	foreach ($result as $doc) {
		$centerLat = $doc['img']['lat'];
		$centerLon = $doc['img']['lon'];
	}
		echo '<!DOCTYPE html>
<html>
<head>
 <script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
	    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
	
	    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

	    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>	
	<title>Sights to See</title>

<script src="http://maps.googleapis.com/maps/api/js?key=YOUR_UNIQUE_GOOGLE_API_KEY&sensor=false">
</script>

<script type="text/javascript">
	function Submit() 
	{
		var uid = document.getElementById("uid").value;
		var number = document.getElementById("number").value;
		var name = document.getElementById("name").value;
		$.ajax({
			type: "POST",
			url: "../text.php",
			data: {\'uid\': uid, \'name\': name, \'number\': number},
			method: "POST",
			success: function(data) {

				$("#textSent").show().fadeOut(6000);

			}
		});
	}
</script>

<script>
function initialize()
{
var mapProp = {
  center:new google.maps.LatLng('.$centerLat.','.$centerLon.'),
 		zoom:10,
 		mapTypeId:google.maps.MapTypeId.ROADMAP
 		};
		var map=new google.maps.Map(document.getElementById("googleMap") ,mapProp);

';
	$index = 0;
	foreach ($result as $doc) {
		$lat = $doc['img']['lat'];
		$lon = $doc['img']['lon'];
		
		echo 'var marker_'.$index.'= new google.maps.Marker({ position: new google.maps.LatLng('.$lat.','.$lon.')});
marker_'.$index.'.setMap(map);
';
		$index = $index+1;
	}
echo '
		
		}
		google.maps.event.addDomListener(window, "load", initialize);
		</script>
		</head>

		<body>
		<input type="hidden" id="uid" value="'.$val.'">
		<div class="container">
		
			<div class="jumbotron">
				<h1> Here\'s your map!</h1>
				<p> Feel free to zoom in and pan around! </p>
			</div>
			<div class="row marketing" style="text-align:center">
			<div id="googleMap" style="width:800px;height:534px;display:block;margin-left:auto;margin-right:auto;max-width:100%;"></div>
			<h3> Now why not <a data-toggle="modal" data-target="#myModal">text it to a friend</a>? </h3>
			<p> Powered by Twilio</p>
			</div>
		</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Phone a friend (through text)</h4>
      </div>
      <div class="modal-body">
        Your name: <input type="text" id="name" placeholder="Awesome Adventurer"> <br>
	Their number: <input type="tel" id="number" placeholder="(202) 456-1111">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick=Submit()>Send a text</button>
      </div>
    </div>
  </div>
</div>
		</body>
		</html>';
	}

Flight::route('/', function(){
    	$value = $_GET['val'];
	view_trip($value);
});

Flight::start();
?>
