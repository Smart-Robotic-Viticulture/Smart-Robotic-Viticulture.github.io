<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <title>VWS Map Explorer</title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"-->
<link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-h21C2fcDk/eFsW9sC9h0dhokq5pDinLNklTKoxIZRUn3+hvmgQSffLLQ4G4l2eEr" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.26.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.26.0/mapbox-gl.css' rel='stylesheet' />

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>

    <style>
        body { margin:0; padding:0; background-color: #000000}
        #map { position:absolute; top:0; left: 350px; right: 0; bottom:0;  }
        #navbar { position: absolute; top: 0; left: 0; z-index: 99; height: 35px; vertical-align: middle }
	#gallerybar { position: absolute; left: 0; top: 0; width: 350px; color: #ccc; padding: 5px; font-size: 0.9em; height: 100%; overflow-y: auto; padding-top: 15px }

	.img270 {  
		-webkit-transform: rotate(270deg);
		-moz-transform: rotate(270deg);
		-ms-transform: rotate(270deg);
		-o-transform: rotate(270deg);
		transform: rotate(270deg);
                max-height: 300px;
		width: auto;
	     }
        .img0 {
                max-width: 300px;
        }
        .img90 {
		-webkit-transform: rotate(90deg);
		-moz-transform: rotate(90deg);
		-ms-transform: rotate(90deg);
		-o-transform: rotate(90deg);
		transform: rotate(90deg);
                max-height: 300px;
		width: auto;
        }
        .img180 {
		-webkit-transform: rotate(180deg);
		-moz-transform: rotate(180deg);
		-ms-transform: rotate(180deg);
		-o-transform: rotate(180deg);
		transform: rotate(180deg);
                max-width: 300px;
        }
	.grow { border-bottom: 1px solid #999; overflow-x: hidden; }
        td { padding: 0px 8px}
	tr.crit { border: 1px solid #ccc; } 
	tr.crit th { border: 1px solid #ccc; padding: 2px 8px; } 


.modal-dialog {
  width: auto;
  height: 100%;
  margin: 0px;
  margin-left: 350px;
  padding: 0px;
  overflow-y: auto;
}
.modal-content {
  padding: 10px;
}
.modal-body {
  margin: 20px;
  padding: 0px;
  position: relative;
}

    </style>
   <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
</head>
<body>

<div id='navbar'>
  <select id="ids" width="400px">
    <option>all</option>
  </select>
  <select id="timeframe" width="100px">
    <option>all</option>
    <!--option>last 7 days</option>
    <option>last 30 days</option>
    <option>last 90 days</option-->
  </select>
</div>
<div style="position: absolute; top: 0; right: 0; background-color: #ffffff; z-index: 9; font-size: 11px">
  <button type="button" class="btn btn-default" aria-label="Devices" style="padding: 12px 20px" data-toggle="tooltip" data-placement="auto" title="Show Devices" onclick="showDevices()">
    <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>
  </button>
  <div style="float: right; padding: 3px">
    <label>Saturation</label>
    <input id='slider' type='range' min='0' max='100' step='0' value='20' />
  </div>
  <div style="float: right; padding: 3px">
    <label>Min Brightness</label>
    <input id='slider2' type='range' min='0' max='100' step='0' value='0' />
  </div>
  <div style="float: right; padding: 3px">
    <label>Max Brightness</label>
    <input id='slider3' type='range' min='0' max='100' step='0' value='70' />
  </div>
  <div style="float: right; padding: 3px">
    <label>Contrast</label>
    <input id='slider4' type='range' min='-100' max='100' step='0' value='0' />
  </div>
</div>

<div id='gallerybar'>
  
</div>

<div id='map'></div>
<script>

var slider = document.getElementById('slider');
var slider2 = document.getElementById('slider2');

mapboxgl.accessToken = 'pk.eyJ1Ijoic21hcnRyb2JvdGljdml0aWN1bHR1cmUiLCJhIjoiY2l2ZDFzZHJiMDBsNDJvbGJ1ZXF2N3R1eiJ9.ZAi5UdKJWUy49o_e-_9-9Q';
var map = new mapboxgl.Map({
    container: 'map', // container id
    //style: 'mapbox://styles/mapbox/satellite-v9', //stylesheet location
    //style: 'mapbox://styles/mapbox/dark-v9', //stylesheet location
    //style: 'mapbox://styles/slamunsw/civa67ief002n2inpmenl1als',
    //style: 'mapbox://styles/slamunsw/civa6o9dp002q2inpm63v9pao',
    style: 'mapbox://styles/smartroboticviticulture/civd23jlu00582iqk7vw3cder',
//    center: [151.234485, -33.9163955], // starting position
//    zoom: 15 // starting zoom
    center: [145.234485, -33.9163955], // starting position
    zoom: 6 // starting zoom
});

map.on('load', function() {
    slider.addEventListener('input', function(e) {
        map.setPaintProperty('mapbox-mapbox-satellite', 'raster-saturation', -parseInt(100 - e.target.value, 10) / 100);
    });
    slider2.addEventListener('input', function(e) {
        map.setPaintProperty('mapbox-mapbox-satellite', 'raster-brightness-min', parseInt(e.target.value, 10) / 100);
    });
    slider3.addEventListener('input', function(e) {
        map.setPaintProperty('mapbox-mapbox-satellite', 'raster-brightness-max', parseInt(e.target.value, 10) / 100);
    });
    slider4.addEventListener('input', function(e) {
        map.setPaintProperty('mapbox-mapbox-satellite', 'raster-contrast', parseInt(e.target.value, 10) / 100);
    });
});

String.prototype.hashCode = function() {
  var hash = 0, i, chr, len;
  if (this.length === 0) return hash;
  for (i = 0, len = this.length; i < len; i++) {
    chr   = this.charCodeAt(i);
    hash  = ((hash << 5) - hash) + chr;
    hash |= 0; // Convert to 32bit integer
  }
  return hash;
};

var dbdata = {};
$(function()
{

	$.getJSON("vws-explore?id=all", function(data) {
		// console.log(data);
		if('Hidden' in data)
			delete(data['Hidden']);

		dbdata = data;
		$('#ids').select2({
			data: Object.keys(dbdata)
		}).on('change', function(e) {
			console.log(e);

			var text = $("#ids").val();
			console.log(text);

			showImagesFrom(dbdata, text);
			showMetaFrom(dbdata, text);
		});

		showImagesFrom(dbdata, 'all');
		showMetaFrom(dbdata, 'all');
	});

	$('[data-toggle="tooltip"]').tooltip(); 
});

var markers = [];

function showDevices()
{
	$.getJSON("vws-devices?id=all", function(data) {
		var html = "<table><tr class='crit'><th>Author ID</th><th>App Version</th><th>Photos</th><th>Status</th><th>Last Report</th><th>Android</th><th>Screen</th></tr><tbody>";
		for (var key in data) {
			// skip loop if the property is from prototype
			if (!data.hasOwnProperty(key)) continue;
	
			
			var device = data[key];
			console.log(device);
			var author = device['Author'];
//			str = JSON.stringify(device, null, 2);
			html += "<tr style='padding: 5px'>";
			html += "<td><a href='#' onclick='javascript: selectArtist(\"" + author + "\")'>" + author.replace("@gmail.com",'') + "</a></td>";
			html += "<td>" + device['AppVersion'].replace("UNSW SRV VWS ","") + "</td>";
			html += "<td>" + device['Photos'] + "</td>";

			var flags = '';
			if(device['WifiLinkSpeed'] > 0) flags += "<span class='glyphicon glyphicon-signal' title='" + device['WifiLinkSpeed'] + " Mbps'></span> ";
			if(device['BatteryCharging'] == 'true') flags += "<span class='glyphicon glyphicon-flash' title='" + device['BatteryPct'] + " %'></span> ";
			if(device['LastWorkSize'] > 0) flags += "<span class='glyphicon glyphicon-cloud-upload' title='" + device['LastWorkSize'] +" files'></span> ";

			html += "<td>" + flags + "</td>";

			html += "<td>" + moment(device['LastUpdate']).fromNow() + "</td>";
			html += "<td>" + ('AndroidVersion' in device ? device['AndroidVersion'] : '') + "</td>";
			html += "<td>" + ('ScreenWidth' in device ? device['ScreenWidth'] + 'x' + device['ScreenHeight'] : '') + "</td>";
			html += "</tr>";
	//		if(('AppVersion' in device)) html += makerow('App', device['AppVersion'], 'crit');
		}
		html += "</tbody></table>";
		$("#modalBody").html(html);
		$('#myModal').modal("show");
	});
}

function showMetaFrom(data, idkey)
{
	var gallerybar = $("#gallerybar");
	gallerybar.html('');

	var lastMonthYear = "";

	for (var key in data) {
		// skip loop if the property is from prototype
		if (!data.hasOwnProperty(key)) continue;

		// skip unselected ids
		if (idkey != key) continue;

		var files = data[key];
		for (var prop in files) {
			// skip loop if the property is from prototype
			if(!files.hasOwnProperty(prop)) continue;

			var obj = files[prop];
			if(!('CWSI' in obj)) continue;
			if(!('Date/Time Original' in obj)) continue;
			if(!('Artist' in obj)) continue;
			if(!('Rotation' in obj)) obj['Rotation'] = 270;

			var datetime = moment( obj['Date/Time Original'] , "YYYY:MM:DD HH:mm:ss.SSSZZ");
			datetime.utcOffset(obj['Date/Time Original'].substr(-6));
			//datetime.utcOffset(utcoffset);
			var artist = obj['Artist'];

			var author = artist.split('; ');
			if(author[1] != 'null') {
				author = author[1];
				email = author.split('@');
				author = email[0];
			}
			else {
				device = author[0];
				device = device.split(' (');
				author = device[0];
				
			}
			var block = ('User_Block' in obj) ? ("<em style='font-size: .8em'>" + obj['User_Block'] + "</em>"): "";
			

			var monthyear = datetime.format("MMMM 'YY");
			if(monthyear != lastMonthYear) {
				gallerybar.append("<div><h2>" + monthyear + "</h2></div>");
				lastMonthYear = monthyear;
			}

			if(idkey == "all") 
				author = "<a href='#' onclick=\"javascript: selectArtist('" + artist + "')\">" + author + "</a>";


			var filename = obj['File Name (Server)'];
			var html = "<div class='grow image_" + filename.hashCode() + "'><a href='#' onclick=\"javascript: showModalOf('" + key + "', '" + prop + "')\">" + datetime.format("DD HH:mm ZZ") + "</a> &nbsp; <strong>" + author + "</strong><span style='float: right'>" + parseFloat(obj['CWSI']).toFixed(2) + "</span> " + block + "</div>";
			gallerybar.append(html);
		}
	}
}

function makerow(key, val, c)
{
	if(c === undefined) c='';
	return "<tr class="+c+"><td><b>" + key + ":</b> </td> <td>" + val + "</td></tr>\n";
}

function showModalOf(key, filename)
{
	var obj = dbdata[key][filename];

	//console.log(obj);
	str = JSON.stringify(obj, null, 2);

	var filename = obj['File Name (Server)'];
	var img0 = '<a target="_blank" href="vws-image?type=jpg&url=' + encodeURI(filename) + '"><img src="vws-image?type=jpg&url=' + encodeURI(filename) + '" class="img' + obj['Rotation'] +'" title="Composite FLIR"/></a><br/>';
	var img1 = '<a target="_blank" href="vws-image?type=thumb&url=' + encodeURI(filename) + '"><img src="vws-image?type=thumb&url=' + encodeURI(filename) + '" class="img0" title="App screenshot"/></a><br/>';
	var img2 = '<a target="_blank" href="vws-image?type=rgb&url=' + encodeURI(filename) + '"><img src="vws-image?type=rgb&url=' + encodeURI(filename) + '" class="img' + obj['Rotation'] + '" title="Color Only"></a>';
	var errors = "";

	if(!('GPS Latitude Numeric' in obj)) 
		errors += "GPS error: No position recorded<br/>";
	if(('Error' in obj)) 
		errors += "Analysis error: " + obj['Error'] + "<br/>";

	if(errors != "") 
		errors = "<div class='alert alert-danger'>" + errors + "</div>";

	var header = "";
	if(('Software' in obj)) 	header += "Generated using <b>" + obj['Software'] + "</b> ";
	if(('Artist' in obj)) 		header += "by <u>" + obj['Artist'] + "</u> ";
	if(('Date/Time Original' in obj)) header += "at <i>" + obj['Date/Time Original'] + "</i><br/>";
	if(('CWSI' in obj)) 		header += "<h3>CWSI: " + obj['CWSI'] + "</h3>";
	header += "<table>";
	if(('User_Block' in obj)) 	header += makerow('Block', obj['User_Block']);
	if(('AverageT' in obj)) 	header += makerow('Avg Canopy Temp', obj['AverageT'] + " &deg;c",'crit');
	if(('WetMeasuredT' in obj)) 	header += makerow('Wet (Measured)',  obj['WetMeasuredT'] + " &deg;c");
	if(('WetCorrectedT' in obj)) 	header += makerow('Wet (Corrected)',  obj['WetCorrectedT'] + " &deg;c");
	if(('MinT' in obj)) 		header += makerow('Min Canopy Temp', obj['MinT'] + " &deg;c", 'crit');
	if(('DryMeasuredT' in obj)) 	header += makerow('Dry (Measured)', obj['DryMeasuredT'] + " &deg;c");
	if(('DryCorrectedT' in obj)) 	header += makerow('Dry (Corrected)', obj['DryCorrectedT'] + " &deg;c");
	if(('MaxT' in obj)) 		header += makerow('Max Canopy Temp', obj['MaxT'] + " &deg;c", 'crit');
	header += "</table><br/>";

	header += "<p><strong>Actions:</strong> <button type='button' class='btn btn-warning btn-xs' onclick='hide(this)' title='" + filename + "'>Hide</button><br/>";

	$("#modalBody").html("");
	$("#modalBody").html("<div style='float: right; position: absolute; top: 0; right: 20px; text-align: right'>" + img0 + img1 + img2 + "</div>" + header + errors + "<pre><small>" + str + "</small></pre>");
	$('#myModal').modal("show");
}

function hide(e)
{
	var el = $(e);
	console.log(el);

	var filename = el.prop('title');
	console.log(filename);
	if(confirm('Hide this file?\n' + filename)) {
		$.post( "vws-hide", { filename: filename } );

		$(".image_" + filename.hashCode()).hide();
		$('#myModal').modal("hide");
	}
}

function showImagesFrom(data, idkey)
{
	markers.forEach(function(marker) {
		marker.remove();
	});
	markers = [];

	for (var key in data) {
		// skip loop if the property is from prototype
		if (!data.hasOwnProperty(key)) continue;

		// skip unselected ids
		if (idkey != key) continue;

		var files = data[key];
		for (var prop in files) {
			// skip loop if the property is from prototype
			if(!files.hasOwnProperty(prop)) continue;

			var obj = files[prop];

			if(!('GPS Latitude Numeric' in obj)) continue;
			if(!('File Name (Server)' in obj)) continue;
			if(!('GPS Longitude Numeric' in obj)) continue;
			if(!('Rotation' in obj)) obj['Rotation'] = 270;

			var filename = obj['File Name (Server)'];

			// your code
			//console.log(key + ":" + prop);
			//console.log(obj);

			var iconSize = [48, 64];

			var el = document.createElement('div');
			var el2 = document.createElement('div');
			el.className = 'marker image_' + filename.hashCode();
			el2.className = 'img' + obj['Rotation'];
			//el.style.backgroundImage = 'url(https://placekitten.com/g/' + iconSize.join('/') + '/)';
			el2.style.backgroundSize = 'cover';
			el2.style.backgroundPosition = 'center center';
			el2.style.backgroundRepeat = 'no-repeat';
			el2.style.backgroundImage = 'url(vws-image?type=rgb&url=' + encodeURI(filename) + ')';
			el.style.width = iconSize[0] + 'px';
			el.style.height = iconSize[1] + 'px';
			el2.style.width = iconSize[0] + 'px';
			el2.style.height = iconSize[1] + 'px';
			el.appendChild(el2);
                        el.setAttribute('fsrc', prop);
                        el.setAttribute('fkey', key);

			el.addEventListener('click', function() {
//					window.alert();
				//$('#myModal').modal("show");
				showModalOf(this.getAttribute('fkey'), this.getAttribute('fsrc'));
			});

			// add marker to map
			var marker = new mapboxgl.Marker(el, {offset: [-iconSize[0] / 2, -iconSize[1] / 2]})
				.setLngLat([obj['GPS Longitude Numeric'], obj['GPS Latitude Numeric']]);

			marker.addTo(map);
			markers.push(marker);

		}
	}
	//console.log(data);

	if(markers.length > 0) {

		var minLat = 999;
		var maxLat = -999;
		var minLng = 999;
		var maxLng = -999;

		markers.forEach(function(m) {
			ll = m.getLngLat();
			if(ll.lng < minLng) 
				minLng = ll.lng;
			if(ll.lng > maxLng)
				maxLng = ll.lng;
			if(ll.lat < minLat)
				minLat = ll.lat;
			if(ll.lat > maxLat)
				maxLat = ll.lat;
		});
		var rngLat = maxLat - minLat + .02;
		var rngLng = maxLng - minLng + .02;
		var bounds = [[minLng - rngLng*.1, minLat - rngLat*.1], [maxLng+rngLng*.1, maxLat+rngLat*.1]];
		//console.log(bounds);
		map.fitBounds(bounds);
	}
}

function selectArtist(artist)
{
	$("#ids").val(artist).trigger("change");	
}

</script>




<!-- Trigger the modal with a button -->
<!--button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button-->

<!-- Modal -->
<div id="myModal" class="modal" role="dialog" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <!--div class="modal-header">
        <h4 class="modal-title" id="modalTitle">Modal Header</h4>
      </div-->
      <button type="button" class="close" data-dismiss="modal" style="z-index: 9999"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
      <div class="modal-body" id="modalBody">
        <p>Some text in the modal.</p>
      </div>
      <!--div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div-->
    </div>

  </div>
</div>


</body>
</html>
