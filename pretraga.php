<?php

error_reporting(E_ALL);
include('funkcije.php');

$dom = new DOMDocument();
$dom->load('podaci.xml');

$xpath = new DOMXPath($dom);
$xpath->registerNamespace("php", "http://php.net/xpath");
$xpath->registerPHPFunctions();	

$obrazac = provjeraUnosa();

if (count($obrazac) != 0) {
	$upit = implode(" and ", $obrazac);
	$rezultat = $xpath->query("/pisci/pisac[$upit]");
} else {
	$rezultat = $xpath->query("/pisci/pisac");
}

$svi_podaci = $xpath->query("/pisci/pisac");

?>

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Svijet pisaca</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="dizajn.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="detalji.js"></script>
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"/>
		<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
	</head>

	<body>
		<header>
			<a href="index.html">
				<img id="logo" src="slike/logo.jpg" alt="Svijet pisaca logo" />
			</a>
			<h1 id="naslov">Svijet pisaca</h1>
		</header>
			
		<nav>
			<ul>
				<li><a href="index.html"> POČETNA </a></li>
				<li><a href="podaci.xml">PODACI</a></li>
				<li><a href="obrazac.html"> PRETRAŽIVANJE </a></li>
				<li><a href="https://www.fer.unizg.hr/predmet/or" target="_blank"> OTVORENO RAČUNARSTVO </a></li>
				<li><a href="http://www.fer.unizg.hr" target="_blank"> FER </a></li>
				<li><a href="mailto:nika.gibanica@fer.hr" target="_blank"> E-MAIL </a></li>
			</ul>
		</nav>
				
		<div id="glavna">
		
			<?php
			
				$pronadeno = count($rezultat);
				$ukupno = count($svi_podaci);
				print "Pronađeno $pronadeno od ukupno $ukupno rezultata.";
				
				foreach ($rezultat as $pisac) {
					$handle = $pisac->getAttribute("wiki_handle");
					$handles[] = $handle;
				}
				
				$odgovor = preuzmiMediaWikiAction($handles);
			?>
			
			<table id="tablica">
				<tr>
					<th>Slika</th>
					<th>Ime i prezime</th>
					<th>Datum rođenja</th>
					<th>Mjesto rođenja</th>
					<th>Alma mater</th>
					<th>Sažetak</th>
					<th>Goodreads</th>
					<th>Akcija</th>
					
				</tr>
	
			<?php
				
				foreach ($rezultat as $cvor) {
					$wiki = $cvor->getAttribute("wiki_handle");
					$id_podatka = $cvor->getAttribute("id");
					$wikimedia = preuzmiWikimedia($wiki);
								
					echo "<tr onmouseover='promijeniBojuRetka(this)'><td>";
					$url_slike = $wikimedia['originalimage']['source'];
					echo "<img id=\"wiki_slika\" src=\"$url_slike\" alt=\"Slika pisca\" height=\"100\" />";
					
					echo "</td><td>";
					$ime = $cvor->getElementsByTagName('ime')->item(0)->nodeValue;
					$prezime = $cvor->getElementsByTagName('prezime')->item(0)->nodeValue;
					$ime_prezime = $ime . " " . $prezime;
					print($ime_prezime);
					echo "</td><td>";
					print($cvor->getElementsByTagName('datum_rod')->item(0)->nodeValue);
					
					echo "</td><td>";
					$mjesto = dohvatiLokaciju($wiki, $odgovor);
					print ($mjesto);
					
					$koordinate = izvuciKoordinate(str_replace(" ", "_", $cvor->getElementsByTagName('mjesto')->item(0)->nodeValue));
					$koordinate0 = $koordinate[0];
					$koordinate1 = $koordinate[1];
					
					$nominatim = preuzmiNominatim(dohvatiLokaciju($wiki, $odgovor));
					$nominatim0 = $nominatim[0];
					$nominatim1 = $nominatim[1];
					
					echo "</td><td>";
					print (preuzmiMediaWikiActionDodatno($wiki));
					
					echo "</td><td width=\"200px\">";
					print (oblikujSazetak($wikimedia['extract']));
					
					echo "</td><td>";
					$gr_info = GoodreadsAPI($id_podatka);
					$fanovi = $gr_info[0];
					$gr_author = $gr_info[1];
					$gr_link = $gr_info[2];
					if ($gr_author == "true") {
						echo "<a href=\"$gr_link\"><img id=\"gr_logo\" src=\"slike/GoodreadsAuthor.png\" alt=\"Goodreads author logo\" height=\"30\" /></a>";
					}
					
					echo "<br>Broj fanova:<br>";
					print($fanovi);
					
					echo "</td><td>";
					$vise = "vise" . $id_podatka;
					$ucitavanje = "ucitavanje" . $id_podatka;
					echo "<button id='$vise' class='btn' onclick='pokaziDetalje(\"$id_podatka\", $koordinate0, $koordinate1, $nominatim0, $nominatim1, \"$ime_prezime\")'><i class='material-icons'>info</i></button>";
					echo "<img id='$ucitavanje' src='slike/loading.gif' alt='Loading' style='display:none;height:75px' />";
					echo "</td></tr>";

				}
			?>
	
			</table>
			
			<div id="div_karta">
			</div>
		</div>
		
		<div id="detalji">
		</div>
	</body>
			
	<footer>
		<p>&copy Nika Gibanica</p>
	</footer>
</html>