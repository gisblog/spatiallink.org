<script type="text/javascript">
//<![CDATA[

	// Using these routines is very simple.
	//
	// 1) Load the routines into your code:
	//    <script src="http://www.acme.com/resources/javascript/Clusterer.js" type="text/javascript"></script>
	//
	// 2) Create a Clusterer object, passing it your map object:
	//
	//        var clusterer = new Clusterer( map );
	//
	// 3) Wherever you now do map.addOverlay( marker ), instead call
	//    clusterer.AddMarker( marker, title ).  The title is just a
	//    short descriptive string to use in the cluster info-boxes.
	//
	// 4) If you are doing any map.removeOverlay( marker ) calls, change those
	//    to clusterer.RemoveMarker( marker ).
	//
	// That's it!  Everything else happens automatically.
	//
	// Copyright © 2005 by Jef Poskanzer <jef@mail.acme.com>.
	// All rights reserved.
		
	var defaultMaxVisibleMarkers = 150;
	var defaultGridSize = 5;
	var defaultMinMarkersPerCluster = 5;
	var defaultMaxLinesPerInfoBox = 10;
	
	var defaultIcon = new GIcon();
	defaultIcon.image = 'http://www.acme.com/resources/images/markers/blue_large.PNG';
	defaultIcon.shadow = 'http://www.acme.com/resources/images/markers/shadow_large.PNG';
	defaultIcon.iconSize = new GSize( 30, 51 );
	defaultIcon.shadowSize = new GSize( 56, 51 );
	defaultIcon.iconAnchor = new GPoint( 13, 34 );
	defaultIcon.infoWindowAnchor = new GPoint( 13, 3 );
	defaultIcon.infoShadowAnchor = new GPoint( 27, 37 );
	
	// Constructor.
	function Clusterer( map )
	    {
	    this.map = map;
	    this.markers = [];
	    this.clusters = [];
	    this.timeout = null;
	    this.currentZoomLevel = map.getZoomLevel();
	
	    this.maxVisibleMarkers = defaultMaxVisibleMarkers;
	    this.gridSize = defaultGridSize;
	    this.minMarkersPerCluster = defaultMinMarkersPerCluster;
	    this.maxLinesPerInfoBox = defaultMaxLinesPerInfoBox;
	    this.icon = defaultIcon;
	
	    GEvent.addListener( map, 'zoom', ClustererMakeCaller( ClustererDisplay, this ) );
	    GEvent.addListener( map, 'moveend', ClustererMakeCaller( ClustererDisplay, this ) );
	    GEvent.addListener( map, 'infowindowclose', ClustererMakeCaller( ClustererPopDown, this ) );
	    }
	
	// Call this to change the cluster icon.
	Clusterer.prototype.SetIcon = function ( icon )
	    {
	    this.icon = icon;
	    }
	
	// Call this to add a marker.
	Clusterer.prototype.AddMarker = function ( marker, title )
	    {
	    // Putting the map into a marker property is not mentioned in the API,
	    // but it lets up pop up an infobox for markers which have not yet been
	    // added to the map.
	    marker.map = this.map;
	    marker.title = title;
	    this.markers.push( marker );
	    this.DisplayLater();
	    }
	
	// Call this to remove a marker.
	Clusterer.prototype.RemoveMarker = function ( marker )
	    {
	    for ( var i = 0; i < this.markers.length; ++i )
		if ( this.markers[i] == marker )
		    {
		    if ( marker.onMap )
			this.map.removeOverlay( marker );
		    for ( j = 0; j < this.clusters.length; ++j )
			{
			cluster = clusterer.clusters[j];
			if ( cluster != null )
			    {
			    for ( k = 0; k < cluster.markers.length; ++k )
				if ( cluster.markers[k] == marker )
				    {
				    cluster.markers[k] = null;
				    --cluster.markerCount;
				    break;
				    }
			    if ( cluster.markerCount == 0 )
				{
				this.ClearCluster( cluster );
				clusterer.clusters[j] = null;
				}
			    else if ( cluster == this.poppedUpCluster )
				ClustererRePop( this );
			    }
			}
		    this.markers[i] = null;
		    break;
		    }
	    this.DisplayLater();
	    }
	
	Clusterer.prototype.DisplayLater = function ()
	    {
	    if ( this.timeout != null )
		window.clearTimeout( this.timeout );
	    this.timeout = window.setTimeout( ClustererMakeCaller( ClustererDisplay, this ), 50 );
	    }
	
	function ClustererDisplay( clusterer )
	    {
	    var i, j, marker, cluster, point;
	
	    window.clearTimeout( clusterer.timeout );
	
	    var newZoomLevel = clusterer.map.getZoomLevel();
	    if ( newZoomLevel != clusterer.currentZoomLevel )
		{
		// When the zoom level changes, we have to remove all the clusters.
		for ( i = 0; i < clusterer.clusters.length; ++i )
		    if ( clusterer.clusters[i] != null )
			{
			clusterer.ClearCluster( clusterer.clusters[i] );
			clusterer.clusters[i] = null;
			}
		clusterer.clusters.length = 0;
		clusterer.currentZoomLevel = newZoomLevel;
		}
	
	    // Get the current bounds of the visible area.
	    var bounds = clusterer.map.getBoundsLatLng();
	
	    // Expand the bounds a little, so things look smoother when scrolling
	    // by small amounts.
	    var dx = bounds.maxX - bounds.minX;
	    var dy = bounds.maxY - bounds.minY;
	    dx *= 0.10;
	    dy *= 0.10;
	    bounds.maxX += dx;
	    bounds.minX -= dx;
	    bounds.maxY += dy;
	    bounds.minY -= dy;
	
	    // Partition the markers into visible and non-visible lists.
	    var visibleMarkers = [];
	    var nonvisibleMarkers = [];
	    for ( i = 0; i < clusterer.markers.length; ++i )
		{
		marker = clusterer.markers[i];
		if ( marker != null )
		    if ( ClustererPointInBounds( marker.point, bounds ) )
			visibleMarkers.push( marker );
		    else
			nonvisibleMarkers.push( marker );
		}
	
	    // Take down the non-visible markers.
	    for ( i = 0; i < nonvisibleMarkers.length; ++i )
		{
		marker = nonvisibleMarkers[i];
		if ( marker.onMap )
		    {
		    clusterer.map.removeOverlay( marker );
		    marker.onMap = false;
		    }
		}
	
	    // Take down the non-visible clusters.
	    for ( i = 0; i < clusterer.clusters.length; ++i )
		{
		cluster = clusterer.clusters[i];
		if ( cluster != null && ! ClustererPointInBounds( cluster.marker.point, bounds ) && cluster.onMap )
		    {
		    clusterer.map.removeOverlay( cluster.marker );
		    cluster.onMap = false;
		    }
		}
	
	    // Clustering!  This is some complicated stuff.  We have three goals
	    // here.  One, limit the number of markers & clusters displayed, so the
	    // maps code doesn't slow to a crawl.  Two, when possible keep existing
	    // clusters instead of replacing them with new ones, so that the app pans
	    // better.  And three, of course, be CPU and memory efficient.
	    if ( visibleMarkers.length > clusterer.maxVisibleMarkers )
		{
		// Add to the list of clusters by splitting up the current bounds
		// into a grid.
		var latRange = bounds.maxY - bounds.minY;
		var latInc = latRange / clusterer.gridSize;
		var lonInc = latInc / Math.cos( ( bounds.maxY + bounds.minY ) / 2.0 * Math.PI / 180.0 );
		for ( var lat = bounds.minY; lat <= bounds.maxY; lat += latInc )
		    for ( var lon = bounds.minX; lon <= bounds.maxX; lon += lonInc )
			{
			cluster = new Object();
			cluster.clusterer = clusterer;
			cluster.bounds = new GBounds( lon, lat, lon + lonInc, lat + latInc );
			cluster.markers = [];
			cluster.markerCount = 0;
			cluster.onMap = false;
			cluster.marker = null;
			clusterer.clusters.push( cluster );
			}
	
		// Put all the unclustered visible markers into a cluster - the first
		// one it fits in, which favors pre-existing clusters.
		for ( i = 0; i < visibleMarkers.length; ++i )
		    {
		    marker = visibleMarkers[i];
		    if ( marker != null && ! marker.inCluster )
			{
			for ( j = 0; j < clusterer.clusters.length; ++j )
			    {
			    cluster = clusterer.clusters[j];
			    if ( cluster != null && ClustererPointInBounds( marker.point, cluster.bounds ) )
				{
				cluster.markers.push( marker );
				++cluster.markerCount;
				marker.inCluster = true;
				}
			    }
			}
		    }
	
		// Get rid of any clusters containing only a few markers.
		for ( i = 0; i < clusterer.clusters.length; ++i )
		    if ( clusterer.clusters[i] != null && clusterer.clusters[i].markerCount < clusterer.minMarkersPerCluster )
			{
			clusterer.ClearCluster( clusterer.clusters[i] );
			clusterer.clusters[i] = null;
			}
	
		// Shrink the clusters list.
		for ( i = clusterer.clusters.length - 1; i >= 0; --i )
		    if ( clusterer.clusters[i] != null )
			break;
		    else
			--clusterer.clusters.length;
	
		// Ok, we have our clusters.  Go through the markers in each
		// cluster and remove them from the map if they are currently up.
		for ( i = 0; i < clusterer.clusters.length; ++i )
		    {
		    cluster = clusterer.clusters[i];
		    if ( cluster != null )
			{
			for ( j = 0; j < cluster.markers.length; ++j )
			    {
			    marker = cluster.markers[j];
			    if ( marker != null && marker.onMap )
				{
				clusterer.map.removeOverlay( marker );
				marker.onMap = false;
				}
			    }
			}
		    }
	
		// Now make cluster-markers for any clusters that need one.
		for ( i = 0; i < clusterer.clusters.length; ++i )
		    {
		    cluster = clusterer.clusters[i];
		    if ( cluster != null && cluster.marker == null )
			{
			// Figure out the average coordinates of the markers in this
			// cluster.
			var xTotal = 0.0, yTotal = 0.0;
			for ( j = 0; j < cluster.markers.length; ++j )
			    {
			    marker = cluster.markers[j];
			    if ( marker != null )
				{
				xTotal += ( + marker.point.x );
				yTotal += ( + marker.point.y );
				}
			    }
			point = new GPoint( xTotal / cluster.markerCount, yTotal / cluster.markerCount );
			marker = new GMarker( point, clusterer.icon );
			cluster.marker = marker;
			GEvent.addListener( marker, 'click', ClustererMakeCaller( ClustererPopUp, cluster ) );
			}
		    }
		}
	
	    // Display the visible markers not already up and not in clusters.
	    for ( i = 0; i < visibleMarkers.length; ++i )
		{
		marker = visibleMarkers[i];
		if ( marker != null && ! marker.onMap && ! marker.inCluster )
		    {
		    clusterer.map.addOverlay( marker );
		    var title = marker.title;
		    if ( title )
			ClustererSetTooltip( marker, title );
		    marker.onMap = true;
		    }
		}
	
	    // Display the visible clusters not already up.
	    for ( i = 0; i < clusterer.clusters.length; ++i )
		{
		cluster = clusterer.clusters[i];
		if ( cluster != null && ! cluster.onMap && ClustererPointInBounds( cluster.marker.point, bounds ) )
		    {
		    clusterer.map.addOverlay( cluster.marker );
		    cluster.onMap = true;
		    }
		}
	
	    // In case a cluster is currently popped-up, re-pop to get any new
	    // markers into the infobox.
	    ClustererRePop( clusterer );
	    }
	
	function ClustererPopUp( cluster )
	    {
	    var clusterer = cluster.clusterer;
	    var html = '<table width="300">';
	    var n = 0;
	    for ( var i = 0; i < cluster.markers.length; ++i )
		{
		var marker = cluster.markers[i];
		if ( marker != null )
		    {
		    ++n;
		    html += '<tr><td>';
		    if ( marker.icon.smallImage )
			html += '<img src="' + marker.icon.smallImage + '">';
		    else
			html += '<img src="' + marker.icon.image + '" width="' + ( marker.icon.iconSize.width / 2 ) + '" height="' + ( marker.icon.iconSize.height / 2 ) + '">';
		    html += '</td><td>' + marker.title + '</td></tr>';
		    if ( n == clusterer.maxLinesPerInfoBox - 1 && cluster.markerCount > clusterer.maxLinesPerInfoBox  )
			{
			html += '<tr><td colspan="2">...and ' + ( cluster.markerCount - n ) + ' more</td></tr>';
			break;
			}
		    }
		}
	    html += '</table>';
	    clusterer.poppedUpCluster = cluster;
	    cluster.marker.openInfoWindowHtml( html );
	    }
	
	function ClustererRePop( clusterer )
	    {
	    if ( clusterer.poppedUpCluster != null )
		ClustererPopUp( poppedUpCluster );
	    }
	
	function ClustererPopDown( clusterer )
	    {
	    clusterer.poppedUpCluster = null;
	    }
	
	Clusterer.prototype.ClearCluster = function ( cluster )
	    {
	    var i, marker;
	
	    for ( i = 0; i < cluster.markers.length; ++i )
		if ( cluster.markers[i] != null )
		    {
		    cluster.markers[i].inCluster = false;
		    cluster.markers[i] = null;
		    }
	    cluster.markers.length = 0;
	    cluster.markerCount = 0;
	    if ( cluster == this.poppedUpCluster )
		this.map.closeInfoWindow();
	    if ( cluster.onMap )
		{
		this.map.removeOverlay( cluster.marker );
		cluster.onMap = false;
		}
	    }
	
	function ClustererPointInBounds( point, bounds )
	    {
	    var x = ( + point.x );
	    var y = ( + point.y );
	    return x >= bounds.minX && x <= bounds.maxX &&
		   y >= bounds.minY && y <= bounds.maxY;
	    }
	
	// This returns a function closure that calls the given routine with the
	// specified arg.
	function ClustererMakeCaller( func, arg )
	    {
	    return function () { func( arg ); };
	    }
	
	function ClustererSetTooltip( marker, title )
	    {
	    // Tooltips are in text, not HTML, so we have to strip any HTML stuff
	    // out of the title.
	    var textTitle = ClustererDeHtmlize( title );
	
	    // Now add the title to whichever parts of the marker are present.
	    if ( marker.transparentIcon )
		marker.transparentIcon.setAttribute( 'title' , textTitle );
	    if ( marker.imageMap )
		marker.imageMap.setAttribute( 'title' , textTitle );
	    if ( marker.iconImage )
		marker.iconImage.setAttribute( 'title' , textTitle );
	    }
	
	function ClustererEntityToIso8859( inStr )
	    {
	    var outStr = '';
	    for ( var i = 0; i < inStr.length; ++i )
		{
		var c = inStr.charAt( i );
		if ( c != '&' )
		    outStr += c;
		else
		    {
		    var semi = inStr.indexOf( ';', i );
		    if ( semi == -1 )
			outStr += c;
		    else
			{
			var entity = inStr.substring( i + 1, semi );
			if ( entity == 'iexcl' ) outStr += '¡';
			else if ( entity == 'copy' ) outStr += '©';
			else if ( entity == 'laquo' ) outStr += '«';
			else if ( entity == 'reg' ) outStr += '®';
			else if ( entity == 'deg' ) outStr += '°';
			else if ( entity == 'raquo' ) outStr += '»';
			else if ( entity == 'iquest' ) outStr += '¿';
			else if ( entity == 'Agrave' ) outStr += 'À';
			else if ( entity == 'Aacute' ) outStr += 'Á';
			else if ( entity == 'Acirc' ) outStr += 'Â';
			else if ( entity == 'Atilde' ) outStr += 'Ã';
			else if ( entity == 'Auml' ) outStr += 'Ä';
			else if ( entity == 'Aring' ) outStr += 'Å';
			else if ( entity == 'AElig' ) outStr += 'Æ';
			else if ( entity == 'Ccedil' ) outStr += 'Ç';
			else if ( entity == 'Egrave' ) outStr += 'È';
			else if ( entity == 'Eacute' ) outStr += 'É';
			else if ( entity == 'Ecirc' ) outStr += 'Ê';
			else if ( entity == 'Euml' ) outStr += 'Ë';
			else if ( entity == 'Igrave' ) outStr += 'Ì';
			else if ( entity == 'Iacute' ) outStr += 'Í';
			else if ( entity == 'Icirc' ) outStr += 'Î';
			else if ( entity == 'Iuml' ) outStr += 'Ï';
			else if ( entity == 'Ntilde' ) outStr += 'Ñ';
			else if ( entity == 'Ograve' ) outStr += 'Ò';
			else if ( entity == 'Oacute' ) outStr += 'Ó';
			else if ( entity == 'Ocirc' ) outStr += 'Ô';
			else if ( entity == 'Otilde' ) outStr += 'Õ';
			else if ( entity == 'Ouml' ) outStr += 'Ö';
			else if ( entity == 'Oslash' ) outStr += 'Ø';
			else if ( entity == 'Ugrave' ) outStr += 'Ù';
			else if ( entity == 'Uacute' ) outStr += 'Ú';
			else if ( entity == 'Ucirc' ) outStr += 'Û';
			else if ( entity == 'Uuml' ) outStr += 'Ü';
			else if ( entity == 'Yacute' ) outStr += 'Ý';
			else if ( entity == 'szlig' ) outStr += 'ß';
			else if ( entity == 'agrave' ) outStr += 'à';
			else if ( entity == 'aacute' ) outStr += 'á';
			else if ( entity == 'acirc' ) outStr += 'â';
			else if ( entity == 'atilde' ) outStr += 'ã';
			else if ( entity == 'auml' ) outStr += 'ä';
			else if ( entity == 'aring' ) outStr += 'å';
			else if ( entity == 'aelig' ) outStr += 'æ';
			else if ( entity == 'ccedil' ) outStr += 'ç';
			else if ( entity == 'egrave' ) outStr += 'è';
			else if ( entity == 'eacute' ) outStr += 'é';
			else if ( entity == 'ecirc' ) outStr += 'ê';
			else if ( entity == 'euml' ) outStr += 'ë';
			else if ( entity == 'igrave' ) outStr += 'ì';
			else if ( entity == 'iacute' ) outStr += 'í';
			else if ( entity == 'icirc' ) outStr += 'î';
			else if ( entity == 'iuml' ) outStr += 'ï';
			else if ( entity == 'ntilde' ) outStr += 'ñ';
			else if ( entity == 'ograve' ) outStr += 'ò';
			else if ( entity == 'oacute' ) outStr += 'ó';
			else if ( entity == 'ocirc' ) outStr += 'ô';
			else if ( entity == 'otilde' ) outStr += 'õ';
			else if ( entity == 'ouml' ) outStr += 'ö';
			else if ( entity == 'oslash' ) outStr += 'ø';
			else if ( entity == 'ugrave' ) outStr += 'ù';
			else if ( entity == 'uacute' ) outStr += 'ú';
			else if ( entity == 'ucirc' ) outStr += 'û';
			else if ( entity == 'uuml' ) outStr += 'ü';
			else if ( entity == 'yacute' ) outStr += 'ý';
			else if ( entity == 'yuml' ) outStr += 'ÿ';
			else if ( entity == 'nbsp' ) outStr += ' ';
			else if ( entity == 'lt' ) outStr += '<';
			else if ( entity == 'gt' ) outStr += '>';
			else if ( entity == 'amp' ) outStr += '&';
			else outStr += '&' + entity + ';';
			i += entity.length + 1;
			}
		    }
		}
	    return outStr;
	    }
	
	function ClustererDeEntityize( inStr )
	    {
	    var outStr = '';
	    for ( var i = 0; i < inStr.length; ++i )
		{
		var c = inStr.charAt( i );
		if ( c != '&' )
		    outStr += c;
		else
		    {
		    var semi = inStr.indexOf( ';', i );
		    if ( semi != -1 )
			i = semi;
		    }
		}
	    return outStr;
	    }
	
	function ClustererDeElementize( inStr )
	    {
	    var outStr = '';
	    for ( var i = 0; i < inStr.length; ++i )
		{
		var c = inStr.charAt( i );
		if ( c != '<' )
		    outStr += c;
		else
		    {
		    var gt = inStr.indexOf( '>', i );
		    if ( gt != -1 )
			i = gt;
		    }
		}
	    return outStr;
	    }
	
	function ClustererDeHtmlize( str )
	    {
	    return ClustererDeEntityize( ClustererEntityToIso8859( ClustererDeElementize( str ) ) );
	    }
    
//]]>
</script>