<?php

function provjeraUnosa() {
	$uvjeti = array();

	if (!empty($_REQUEST['ime'])) {
		$uvjeti[] = upitIme();
	}

	if (!empty($_REQUEST['prezime'])) {
		$uvjeti[] = upitPrezime();
	}

	if (!empty($_REQUEST['mjesto'])) {
		$uvjeti[] = upitMjesto();
	}

	if (!empty($_REQUEST['drzava'])) {
		$uvjeti[] = upitDrzava();
	}

	if (!empty($_REQUEST['starost'])) {
		$uvjeti[] = upitStarost();
	}

	if (!empty($_REQUEST['web_postoji'])) {
		$uvjeti[] = upitWebPostoji();
	}

	if (!empty($_REQUEST['pseudo_postoji'])) {
		$uvjeti[] = upitPseudoPostoji();
	}

	if (!empty($_REQUEST['pseudo_ime'])) {
		$uvjeti[] = upitPseudoIme();
	}

	if (!empty($_REQUEST['pseudo_prezime'])) {
		$uvjeti[] = upitPseudoPrezime();
	}

	if (!empty($_REQUEST['nagrada_postoji'])) {
		$uvjeti[] = upitNagradaPostoji();
	}

	if (!empty($_REQUEST['nagrada'])) {
		$uvjeti[] = upitNagrada();
	}

	if (!empty($_REQUEST['godina_nagrade']) && $_REQUEST['godina_nagrade'] != "----") {
		$uvjeti[] = upitGodNagrade();
	}

	if (!empty($_REQUEST['naslov'])) {
		$uvjeti[] = upitNaslov();
	}

	if (!empty($_REQUEST['godina_izdanja']) && $_REQUEST['godina_izdanja'] != "----") {
		$uvjeti[] = upitGodIzdanja();
	}

	if (!empty($_REQUEST['publika'])) {
		$uvjeti[] = upitPublika();
	}

	if (!empty($_REQUEST['zanr'])) {
		$uvjeti[] = upitZanr();
	}

	if (!empty($_REQUEST['isbn'])) {
		$uvjeti[] = upitIsbn();
	}
	
	return $uvjeti;
}

function upitIme(){
	$ime = $_REQUEST['ime'];
	if (!empty($_REQUEST['ne_ime'])) {
		return "ime[not(contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($ime) . "'))]";
	} else {
		return "ime[contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($ime) . "')]";
	}
}

function upitPrezime(){
	$prezime = $_REQUEST['prezime'];
	if (!empty($_REQUEST['ne_prezime'])) {
		return "prezime[not(contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($prezime) . "'))]";
	} else {
		return "prezime[contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($prezime) . "')]";
	}
}

function upitMjesto(){
	$mjesto = $_REQUEST['mjesto'];
	if (!empty($_REQUEST['ne_mjesto'])) {
		return "mjesto_rod/mjesto[not(contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($mjesto) . "'))]";
	} else {
		return "mjesto_rod/mjesto[contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($mjesto) . "')]";
	}
}

function upitDrzava(){
	$drzava = $_REQUEST['drzava'];
	if (!empty($_REQUEST['ne_drzava'])) {
		return "mjesto_rod/drzava[not(contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($drzava) . "'))]";
	} else {
		return "mjesto_rod/drzava[contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($drzava) . "')]";
	}
}

function upitStarost(){
	$starost = $_GET['starost'];
	if ($starost == "manje_od_20") {
		return "substring(./datum_rod, 7, 4) > 2000";
	} else if ($starost == "20_30") {
		return "substring(./datum_rod, 7, 4) <= 2000 and substring(./datum_rod, 7, 4) >= 1990";
	} else if ($starost == "31_40") {
		return "substring(./datum_rod, 7, 4) <= 1989 and substring(./datum_rod, 7, 4) >= 1980";
	} else if ($starost == "41_50") {
		return "substring(./datum_rod, 7, 4) <= 1979 and substring(./datum_rod, 7, 4) >= 1970";
	} else if ($starost == "51_60") {
		return "substring(./datum_rod, 7, 4) <= 1969 and substring(./datum_rod, 7, 4) >= 1960";
	} else if ($starost == "vise_od_60") {
		return "substring(./datum_rod, 7, 4) < 1960";
	}
}

function upitWebPostoji() {
	$web_postoji = $_REQUEST['web_postoji'];
	$vrijednosti = array();
	foreach ($web_postoji as $w) {
		if ($w == "postoji") {
			$vrijednosti[] = "@postoji='1'";
		} elseif ($w == "ne_postoji") {
			$vrijednosti[] = "@postoji='0'";
		}
	}
	$p = implode(" or ", $vrijednosti);
	return "web_postoji[" . $p . "]";
}

function upitPseudoPostoji() {
	$pseudo_postoji = $_REQUEST['pseudo_postoji'];
	if (isset($_REQUEST['pseudo_postoji']) && $pseudo_postoji == 'postoji') {
		return "pseudonim_postoji[@postoji='1']";
	}
}

function upitPseudoIme() {
	$pseudo_ime = $_REQUEST['pseudo_ime'];
	return "pseudonim/pseudo_ime[contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($pseudo_ime) . "')]";
}

function upitPseudoPrezime() {
	$pseudo_prezime = $_REQUEST['pseudo_prezime'];
	return "pseudonim/pseudo_prezime[contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($pseudo_prezime) . "')]";
}

function upitNagradaPostoji() {
	$nagrada_postoji = $_REQUEST['nagrada_postoji'];
	if (isset($_REQUEST['nagrada_postoji']) && $nagrada_postoji == 'postoji') {
		return "knjiga/nagrada_postoji[@postoji='1']";
	}
}

function upitNagrada() {
	$nagrada = $_REQUEST['nagrada'];
	if (!empty($_REQUEST['ne_nagrada'])){
		return "knjiga/nagrada[not(contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($nagrada) . "'))]";
	} else {
		return "knjiga/nagrada[contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($nagrada) . "')]";
	}
}

function upitGodNagrade() {
	$god_nag = $_REQUEST['godina_nagrade'];
	return "knjiga/nagrada[@god_dodjele='" . $god_nag . "']";
}

function upitNaslov() {
	$naslov = $_REQUEST['naslov'];
	if (!empty($_REQUEST['ne_naslov'])) {
		return "knjiga/naslov[not(contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($naslov) . "'))]";
	} else {
		return "knjiga/naslov[contains(php:functionString('mb_strtolower', text()), '" . mb_strtolower($naslov) . "')]";
	}
}

function upitGodIzdanja() {
	$god_izd = $_REQUEST['godina_izdanja'];
	return "knjiga[@god_izdanja='" . $god_izd . "']";
}

function upitPublika() {
	$publika = $_REQUEST['publika'];
	$vrijednosti = array();
	foreach ($publika as $pub) {
		$vrijednosti[] = "@publika='" . $pub . "'";
	}
	$v = implode(" or ", $vrijednosti);
	return "knjiga[" . $v . "]";
}

function upitZanr() {
	$zanr = $_REQUEST['zanr'];
	$zanrovi = array();
	foreach ($zanr as $z) {
		$zanrovi[] = "@zanr[contains(., '" . $z . "')]";
	}
	$g = implode(" or ", $zanrovi);
	return "knjiga[" . $g . "]";
}

function upitIsbn(){
	$isbn = $_REQUEST['isbn'];
	return "knjiga[@isbn='" . $isbn . "']";	
}

function kontekst(){
	$options = array('http' => array('user_agent' => 'nika.gibanica@fer.hr'));
	$context = stream_context_create($options);
	
	return $context;
}

function preuzmiWikimedia($handle) {
	$json = file_get_contents('https://en.wikipedia.org/api/rest_v1/page/summary/' . $handle, false, kontekst());
	$odgovor = json_decode($json, true);
	
	return $odgovor;
}

function oblikujSazetak($sazetak) {
	if (strlen($sazetak) > 150) {
		return substr($sazetak, 0, 150) . "...";
	}
	return $sazetak;
}

function izvuciKoordinate($mjesto){
	$mjesto_wikimedia = preuzmiWikimedia($mjesto);
	
	$duzina = $mjesto_wikimedia['coordinates']['lon'];
	$sirina = $mjesto_wikimedia['coordinates']['lat'];
	$koordinate = array($sirina, $duzina);
	
	return $koordinate;
}

function preuzmiMediaWikiAction ($handle) {
	$json = file_get_contents('https://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&rvsection=0&titles=' . $handle . '&format=json', false, kontekst());
	$odgovor = json_decode($json, true);
	$pages = $odgovor['query']['pages'];
	$indeksirano = array_values($pages);
	$broj = $indeksirano[0];
	$za_parsiranje = $broj['revisions']['0']['*'];
	
	$dijelovi = explode("|", $za_parsiranje);
	foreach ($dijelovi as $dio) {
		if(preg_match("/birth_place/", $dio)) {
			$trazeno = $dio;
			break;
		}
	}
	
	$trazeno = str_replace("[", "", $trazeno);
	$trazeno = str_replace ("]", "", $trazeno);
	$trazeno = str_replace(".", "", $trazeno);
	$lokacija = preg_replace("/birth_place\s+=\s+/", "", $trazeno);

	return $lokacija;
}

function preuzmiNominatim($adr) {
	$url = urlencode($adr);
	
	/*hardkodirano uređivanje url-a, ali nisam mogla pronaći drugo rješenje
	s obzirom da ne znam ni zašto se taj dio pojavljuje za jedan podatak,
	a ostali podaci se encode-aju normalno*/
	$url = preg_replace("/%3C%21--As\+per\+WP%3ALINKDIRECT\+and\+Template%3Ainfobox\+person%2C\+birthplace\+indicates\+city%2C\+state%2C\+then\+country\+No\+need\+to\+spell\+out\+%22United\+States%3B\+%27US%27\+is\+fine--%3E/", "", $url);
	
	$xml = simplexml_load_string(file_get_contents('http://nominatim.openstreetmap.org/search?q=' . $url . '&format=xml', false, kontekst()));
	$podaci = $xml->children();

	foreach ($podaci as $p) {
		if ($p->getName() == "place") {
			foreach($p->attributes() as $at => $v) {
				if ($at == "lat") {
					$sirina = $v;
				} elseif ($at == "lon") {
					$duzina = $v;
				}
			}
			break;
		}
	}
	
	$koordinate = array($sirina, $duzina);
	return $koordinate;
}

?>