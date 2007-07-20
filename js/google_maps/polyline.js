
    if (GBrowserIsCompatible()) {
      // this variable will collect the html which will eventually be placed in the side_bar
      var side_bar_html = "";
    
      // arrays to hold copies of the markers and html used by the side_bar
      // because the function closure trick doesnt work there
      var gmarkers = [];
      var htmls = [];
      var i = 0;

      var background = [];
          background["green"] = "img/pin_1.png";
          background["red"]   = "img/pin_2.png";

      // A function to create the marker and set up the event window
      function createMarker(point,name,html,ba,normalMarker) {      
//	  	var mylabel = {"url":overlay[ov], "anchor":new GLatLng(4,4), "size":new GSize(12,12)};
//        var Icon = new GIcon(G_DEFAULT_ICON, background[ba], mylabel);
		if (normalMarker){ 
			var Icon = new GIcon(G_DEFAULT_ICON, background[ba]);
		} else {
			var Icon = new GIcon();
			//Icon.image = "http://maps.google.com/mapfiles/kml/pal3/icon60.png";
			Icon.image =markerBg;
			 Icon.iconSize=new GSize(24,24);
			// Icon.iconSize=new GSize(16,16);
			Icon.shadow = "http://maps.google.com/mapfiles/kml/pal3/icon61s.png";
			Icon.shadowSize=new GSize(59,32);
			Icon.iconAnchor=new GPoint(12,16);

			Icon.infoWindowAnchor=new GPoint(16,0);
		}
       		var marker = new GMarker(point,Icon,{title:name});
//        var marker = new GMarker(point,{title:name});
 	    GEvent.addListener(marker, "click", function() {
         	marker.openInfoWindowHtml(html);
        });
        // save the info we need to use later for the side_bar
		if (normalMarker) {
			gmarkers[i] = marker;
			htmls[i] = html;
			// add a line to the side_bar html
			side_bar_html += '<a href="javascript:myclick(' + i + ')">' + name + '</a><br>';
			i++;
		} else {
				posMarker=marker;
		}
        return marker;
      }


      // This function picks up the click and opens the corresponding info window
      function myclick(i) {
        gmarkers[i].openInfoWindowHtml(htmls[i]);
      }


      // create the map
//      var map = new GMap2(document.getElementById("map"),   {mapTypes:[G_HYBRID_MAP,G_SATELLITE_MAP,G_NORMAL_MAP]}); 
//      map.addControl(new GLargeMapControl());
//      map.addControl(new GMapTypeControl());
//      map.setCenter(new GLatLng(0,0), 4);

      var side_bar_html = "";
      
	  var center_lat;
	  var center_lon;
	  var bounds ;

	  process_polyline   = function(doc) {
        // === split the document into lines ===
        lines = doc.split("\n");
	    parts = lines[2].split(",");
        var min_lat = parseFloat(parts[0]);
        var max_lat = parseFloat(parts[1]);
        var min_lon = parseFloat(parts[2]);
        var max_lon = parseFloat(parts[3]);

		center_lat=(max_lat+min_lat)/2;
		center_lon=(max_lon+min_lon)/2;
			
		bounds = new GLatLngBounds(new GLatLng(max_lat,min_lon ),new GLatLng(min_lat,max_lon));
		zoom=map.getBoundsZoomLevel(bounds);	
		map.setCenter(new GLatLng( center_lat,center_lon), zoom);

		process_waypoints();
		var encodedPolyline = new GPolyline.fromEncoded({
			color: "#FF0000",
			weight: 2,
			opacity:1,
			points: lines[3],
			levels: lines[4],
			zoomFactor: 16,
			numLevels:4
		});
		map.addOverlay(encodedPolyline);

	   }
	   
      // === Define the function thats going to process the text file ===
      function process_waypoints(){
        for (var j=0; j<2; j++) {
          if (lines[j].length > 1) {
            // === split each line into parts separated by "|" and use the contents ===
            parts = lines[j].split("|");
            var lat = parseFloat(parts[0]);
            var lng = parseFloat(parts[1]);
            var html = parts[2];
            var label = parts[3];
            var point = new GLatLng(lat,lng);
            // create the marker
			var bg;
			if (j==0) bg="green";
			else bg="red";
            var marker = createMarker(point,label,html,bg,1);
            map.addOverlay(marker);
			if (j==0) { //create  also the running icon
				 var marker = createMarker(point,label,html,markerBg,0);
	             map.addOverlay(marker);
			}
          }
        }
        // put the assembled side_bar_html contents into the side_bar div
		var curHtml=document.getElementById("side_bar").innerHTML;
        document.getElementById("side_bar").innerHTML =  curHtml + side_bar_html;
      }                   
	 //fname is derevied from args
      GDownloadUrl(fname, process_polyline);	 	
	  
	  
    }

    else {
      alert("Sorry, the Google Maps API is not compatible with this browser");
    }
    // This Javascript is based on code provided by the
    // Blackpool Community Church Javascript Team
    // http://www.commchurch.freeserve.co.uk/   
    // http://www.econym.demon.co.uk/googlemaps/


function zoomToFlight() {		
		zoom=map.getBoundsZoomLevel(bounds);	
		map.setCenter(new GLatLng( center_lat,center_lon), zoom);
}