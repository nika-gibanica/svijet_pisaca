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
				
		<div>
		
			<?php
			
				$pronadeno = count($rezultat);
				$ukupno = count($svi_podaci);
				print "Pronađeno $pronadeno od ukupno $ukupno rezultata.";
			?>
			
			<table id="tablica">
				<tr>
					<th>Slika</th>
					<th>Ime i prezime</th>
					<!-- <th>Web</th> -->
					<th>Datum rođenja</th>
					<th>Mjesto rođenja</th>
					<th>Koordinate (Wikimedia)</th>
					<th>Koordinate (Nominatim)</th>
					<!-- <th>Pseudonim</th> -->
					<th>Knjige</th>
					<th>Sažetak</th>
					
				</tr>
	
			<?php
				
				foreach ($rezultat as $cvor) {
					$wiki = $cvor->getAttribute("wiki_handle");
					$wikimedia = preuzmiWikimedia($wiki);
								
					echo "<tr><td>";
					$url_slike = $wikimedia['originalimage']['source'];
					echo "<img id=\"wiki_slika\" src=\"$url_slike\" alt=\"Slika pisca\" width=\"128\" />";
					
					echo "</td><td>";
					print($cvor->getElementsByTagName('ime')->item(0)->nodeValue);
					echo " ";
					print($cvor->getElementsByTagName('prezime')->item(0)->nodeValue);
					//echo "</td><td>";
					//print($cvor->getElementsByTagName('web')->item(0)->nodeValue);
					echo "</td><td>";
					print($cvor->getElementsByTagName('datum_rod')->item(0)->nodeValue);
					
					echo "</td><td>";
					print (preuzmiMediaWikiAction($wiki));
					
					echo "</td><td>";
					$koordinate = izvuciKoordinate(str_replace(" ", "_", $cvor->getElementsByTagName('mjesto')->item(0)->nodeValue));
					print $koordinate[0];
					echo "<br>";
					print $koordinate[1];
					
					echo "</td><td>";
					$nominatim = preuzmiNominatim(preuzmiMediaWikiAction($wiki));
					print $nominatim[0];
					echo "<br>";
					print $nominatim[1];
					
					echo "</td><td>";
					
					/*if (!empty($cvor->getElementsByTagName('pseudo_ime')->item(0)->nodeValue)) {
						print($cvor->getElementsByTagName('pseudo_ime')->item(0)->nodeValue);
						echo " ";
						print($cvor->getElementsByTagName('pseudo_prezime')->item(0)->nodeValue);
					} else {
						print "-";
					}
					
					echo "</td><td>";*/
					
					foreach ($cvor->childNodes as $dijete) {
						if ($dijete->nodeName == "knjiga") {
							print ($dijete->getElementsByTagName('naslov')->item(0)->nodeValue);
							echo " (";
							print ($dijete->getAttribute("god_izdanja"));
							echo ")<br />";
						}
					}
					
					echo "</td><td width=\"200px\">";
					print (oblikujSazetak($wikimedia['extract']));
					echo "</td></tr>";

				}
			?>
	
			</table>
		</div>
	</body>
			
	<footer>
		<p>Autor: Nika Gibanica</p>
	</footer>
</html>