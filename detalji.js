var req;
var gl_id;

var sirina_w;
var duzina_w;
var sirina_n;
var duzina_n;
var pisac;

function promijeniBojuRetka(redak) {
	redak.style.backgroundColor = "thistle";
}

function pokaziDetalje(id, sirinaw, duzinaw, sirinan, duzinan, imeprez) {
	gl_id = id;
	sirina_w = sirinaw;
	duzina_w = duzinaw;
	sirina_n = sirinan;
	duzina_n = duzinan;
	pisac = imeprez;
	
	document.getElementById("vise" + id).style.display = 'none';
	document.getElementById("ucitavanje" + id).style.display = 'inline';
	
	if(window.XMLHttpRequest) {
		req = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	req.open("GET", "detalji.php?id=" + id, true);
	req.onreadystatechange = dohvati;
	req.send(null);
}

function dohvati() {
	if (req.readyState == 4) {
		if (req.status == 200) {
			document.getElementById("detalji").innerHTML = req.responseText;
			document.getElementById("ucitavanje" + gl_id).style.display = 'none';
			document.getElementById("vise" + gl_id).style.display = 'inline';
			
			document.getElementById('div_karta').innerHTML = "<div id='karta'></div>";
			document.getElementById("karta").style.display = 'inherit';
					
			var map = L.map('karta').setView([sirina_w, duzina_w], 8);
					
			var OpenStreetMap_Mapnik = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
				maxZoom: 15
			}).addTo(map);
			
			var violetIcon = new L.Icon({
				iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png',
				shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
				iconSize: [25, 41],
				iconAnchor: [12, 41],
				popupAnchor: [1, -34],
				shadowSize: [41, 41]
			});
			
			var marker1 = L.marker([sirina_w, duzina_w], {icon: violetIcon}).addTo(map);
			marker1.bindPopup("Rođen/a ovdje: " + pisac + "<br>Koordinate: " + sirina_w + " " + duzina_w).openPopup;
			
			var marker2 = L.marker([sirina_n, duzina_n]).addTo(map);
			marker2.bindPopup("Rođen/a ovdje: " + pisac + "<br>Koordinate: " + sirina_n + " " + duzina_n).openPopup;
		
			const koordinate = [[sirina_w, duzina_w], [sirina_n, duzina_n]];
			const configObject = {color: 'dimgray'};
			var polyline = L.polyline(koordinate, configObject).addTo(map);
			map.fitBounds(polyline.getBounds());
	
		} else {
			alert("Nije primljen 200 OK, nego:\n" + req.statusText);
		}
	}
}



