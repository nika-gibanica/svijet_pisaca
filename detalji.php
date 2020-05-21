<?php

error_reporting(E_ALL);

sleep(2);

$dom = new DOMDocument();
$dom->load('podaci.xml');

$xpath = new DOMXPath($dom);
$xpath->registerNamespace("php", "http://php.net/xpath");
$xpath->registerPHPFunctions();	

$id = $_REQUEST['id'];
$rezultat = $xpath->query("/pisci/pisac[@id='" . $id . "']");

?>	

<div>
	<h4>DODATNI PODACI</h4>
		
	<table id="detalj">
		<tr>
			<td class="naziv_podatka">Web</td>
			<td>
				<?php
					print($rezultat[0]->getElementsByTagName('web')->item(0)->nodeValue);
				?>
			</td>
		</tr>
		<tr>
			<td class="naziv_podatka">Pseudonim</td>
			<td>
				<?php
					if (!empty($rezultat[0]->getElementsByTagName('pseudo_ime')->item(0)->nodeValue)) {
						print($rezultat[0]->getElementsByTagName('pseudo_ime')->item(0)->nodeValue);
						echo " ";
						print($rezultat[0]->getElementsByTagName('pseudo_prezime')->item(0)->nodeValue);
					} else {
						print "-";
					}
				?>
			</td>
		</tr>
		<tr>
			<td class="naziv_podatka">Knjige</td>
			<td>
				<?php
					foreach ($rezultat[0]->childNodes as $dijete) {
						if ($dijete->nodeName == "knjiga") {
							print ($dijete->getElementsByTagName('naslov')->item(0)->nodeValue);
							echo " (";
							print ($dijete->getAttribute("god_izdanja"));
							echo ")<br />";
						}
					}
				?>
			</td>
		</tr>
	</table>	
</div>	