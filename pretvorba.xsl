<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:output method="xml" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" />
	<xsl:template match="/">
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
					<table id="tablica">
						<tr>
							<th>Ime i prezime</th>
							<th>Web</th>
							<th>Datum rođenja</th>
							<th>Mjesto rođenja</th>
							<th>Pseudonim</th>
							<th>Knjige</th>
						</tr>
						
						<xsl:for-each select="pisci/pisac">
							<tr>
								<td><xsl:value-of select="ime" /> <xsl:value-of select="prezime" /></td>
														
								<xsl:choose>
									<xsl:when test="web_postoji[@postoji='1']">
										<td>
											<a>
												<xsl:attribute name="href">
													<xsl:value-of select="web" />
												</xsl:attribute>
												<xsl:value-of select="web" />
											</a>
										</td>
									</xsl:when>
									<xsl:otherwise>
										<td>-</td>
									</xsl:otherwise>
								</xsl:choose>
								
								<td><xsl:value-of select="datum_rod" /></td>
								<td><xsl:value-of select="mjesto_rod/mjesto" />, <xsl:value-of select="mjesto_rod/drzava" /></td>
							
								<xsl:choose>
									<xsl:when test="pseudonim_postoji[@postoji='1']">
										<td><xsl:value-of select="pseudonim/pseudo_ime" /> <xsl:value-of select="pseudonim/pseudo_prezime" /></td>
									</xsl:when>
									<xsl:otherwise>
										<td>-</td>
									</xsl:otherwise>
								</xsl:choose>
							
								<td>
									<xsl:for-each select="knjiga">
										<xsl:value-of select="naslov" /> (<xsl:value-of select="./@god_izdanja" />)<br />
									</xsl:for-each>						
								</td>
							</tr>
						</xsl:for-each>
					</table>
				</div>
			</body>
			
			<footer>
				<p>Autor: Nika Gibanica</p>
			</footer>
		</html>
	</xsl:template>
</xsl:stylesheet>