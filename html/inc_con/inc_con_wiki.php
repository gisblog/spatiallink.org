	<!-- include content-->
	<!--<span> can NOT enclose <table> for XHTML, but it can enclose <span>. <span> can include its subsets, but outside of elements like <form>-->
			<table width="520" cellspacing="0" cellpadding="0" class="medium">
		<tr>
	<td class="medium_bold">
	>> WIKI
	</td>
	<td class="medium_right_middle">
		<img src="/images/phpthumb/phpThumb.php?src=/images/img_wiki.gif" alt="spatiallink_org" width="50" height="50" />
	</td>
		</tr>
		<tr>
	<!--&8226; = &bull;-->	
	<td colspan="2" class="medium">
		<!--google scholar search-->
		<form method="get" action="http://scholar.google.com/scholar">
		<input type="text" name="q" size="52" maxlength="255" value="" />
		<input type="submit" name="btng" value="G Scholar Search" />
		</form>
		<!--google scholar search-->
		<!--google book search-->
		<form method="get" action="http://books.google.com/books">
		<input type="text" name="q" size="52" maxlength="255" value="" />
		<input type="submit" name="btng" value="G Book Search" />
		</form>
		<!--google print search-->		
		<!--<br />-->
		<br />	
		&#8226;&nbsp;<a href="http://www.spatiallink.org/gistools/discuss/wiki/index.php?wiki=MainPage">Main Page</a>::
		<br />
		<br />
		&#8226;&nbsp;<a href="http://www.spatiallink.org/gistools/discuss/wiki/index.php?wiki=NewPage">Start a New Page</a>::
		<br />
		<br />
		&#8226;&nbsp;Existing Pages:: [Date::Time::Host::IP]
		<br />
		<?php
		//	note: $varfilepath gives the filepath of this file- wiki.php
		$fileurl = "<a href=\"/gistools/discuss/wiki";
		$data_dir = "/wikidata/";
		$history_dir = "/wikihistory/";	
		//	note the /
		if ($filehandle = opendir(".".$data_dir)) {
			while (false !== ($filename = readdir($filehandle))) {
				if ($filename != "." && $filename != "..") {
					/*	history: file_exists requires full path */
					if (file_exists("/var/chroot/home/content/57/3881957/html/gistools/discuss/wiki".$history_dir.$filename)) {
						$history_link = "[".$fileurl.$history_dir.$filename."\">Recent History</a>]";
					} else {
						$history_link = "";
					}
					/*	history */
					print "&#186;&nbsp;".$fileurl."/index.php?wiki=".$filename."\">".$filename."</a> ".$history_link."<br />";
				}
			}
			closedir($filehandle);
		}
		?>
		<!--<br />-->
		<br />
		&#8226;&nbsp;White Papers::
		<br />
		<?php
		// include rss
		include '/var/chroot/home/content/57/3881957/html/inc_con/rss/rss_wiki.spatial.php';
		?>
		<!--<br />-->
		<br />
		&#8226;&nbsp;Related::
		<br />
		&#186;&nbsp;<a href="http://ocw.mit.edu/">MIT OpenCourseWare</a>
		<br />
		&#186;&nbsp;<a href="http://mapserver.gis.umn.edu/cgi-bin/wiki.pl">MapServer WIKI</a>	
		<br />
		&#186;&nbsp;<a href="http://www.openstreetmap.org/">OpenStreetMap</a>
		<br />
		&#186;&nbsp;<a href="http://www.geowiki.co.uk/geowiki/">GeoWIKI</a>
		<br />
		&#186;&nbsp;<a href="http://en.wikipedia.org/">WIKIpedia</a>
		<br />
		&#186;&nbsp;<a href="http://www.imaginationcubed.com/Imagine">GE Imagination Cubed</a>	
		<br />
		<br />
		&#186;&nbsp;<a href="http://www.opengeospatial.org/specs/">Open Geospatial Consortium Documents</a>
		<br />
		&#186;&nbsp;<a href="http://highwire.stanford.edu/">Stanford HighWire Press</a>
		<br />
		&#186;&nbsp;<a href="http://www.computer.org/portal/site/csdl/">IEEE Digital Library</a>
		<br />
		&#186;&nbsp;<a href="http://www.knowledgestorm.com/">KnowledgeStorm Directory</a>
		<br />
		&#186;&nbsp;<a href="http://itpapers.techrepublic.com/">TechRepublic White Paper Directory</a>
		<br />
		&#186;&nbsp;<a href="http://www.esri.com/library/">ESRI Library</a>
		<br />
		&#186;&nbsp;<a href="http://www.intergraph.com/sgi/resources/">Intergraph Resource Library</a>
		<br />
		&#186;&nbsp;<a href="http://extranet.mapinfo.com/support/documentation/">MapInfo Documentation</a>
		<br />
		<br />
	</td>
		</tr>
			</table>
	<!-- include content-->