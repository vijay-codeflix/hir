// function initMap(ways_points) {
//     var directionsService = new google.maps.DirectionsService;
//     var directionsRenderer = new google.maps.DirectionsRenderer;
//     var map = new google.maps.Map(document.getElementById('map'), {
//       zoom: 6,
//       center: {lat: 41.85, lng: -87.65}
//     });
//     directionsRenderer.setMap(map);

//     calculateAndDisplayRoute(directionsService, directionsRenderer, ways_points);
// }

// function calculateAndDisplayRoute(directionsService, directionsRenderer, ways_points) {
//     var waypts = [];
//     var checkboxArray = document.getElementById('waypoints');
//     // console.log(ways_points[ways_points.length - 1 ].lat)
//     // console.log(ways_points[ways_points.length - 1].lng)
//     for (var i = 0; i < ways_points.length; i++) {
      
//         waypts.push({
//           location: new google.maps.LatLng(ways_points[0].lat, ways_points[0].lng),//checkboxArray[i].value,
//           stopover: true
//         });
      
//     }
//     console.log(document.getElementById('start').value);
//     console.log(document.getElementById('end').value);

//      console.log(waypts);
//     directionsService.route({
//       origin: document.getElementById('start').value,
//       destination: document.getElementById('end').value,
//       waypoints: waypts,
//       optimizeWaypoints: true,
//       travelMode: 'DRIVING'
//     }, function(response, status) {
//       if (status === 'OK') {
//         directionsRenderer.setDirections(response);
//         var route = response.routes[0];
//         var summaryPanel = document.getElementById('directions-panel');
//         summaryPanel.innerHTML = '';
//         // For each route, display summary information.
//         for (var i = 0; i < route.legs.length; i++) {
//           var routeSegment = i + 1;
//           summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
//               '</b><br>';
//           summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
//           summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
//           summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
//         }
//       } else {
//         window.alert('Directions request failed due to ' + status);
//       }
//     });
// }
var pointsValues = [...Array(1000).keys()];;

function initMap(data) {
    var service = new google.maps.DirectionsService;
    var map = new google.maps.Map(document.getElementById('map'));

    // list of points
    var stations = data

    // Zoom and center map automatically by stations (each station will be in visible map area)
    var lngs = stations.map(function(station) { return parseFloat(station.lng); });
    var lats = stations.map(function(station) { return parseFloat(station.lat); });
    map.fitBounds({
        west: Math.min.apply(null, lngs),
        east: Math.max.apply(null, lngs),
        north: Math.min.apply(null, lats),
        south: Math.max.apply(null, lats),
    });

    // Show stations on the map as markers
    var number = 0;
    var number2 = 0;
    var labels = 'Start';
    var labelIndex = 0;
    var icon = 'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_black.png';
    for (var i = 0; i < stations.length; i++) {
        //console.log({lat: parseFloat(stations[i]['lat']),lng: parseFloat(stations[i]['lng']) })
        //console.log('stations',stations[i])
        var color = 'black'
        var labelClass = 'map-marker-labels'
        number2++;
        if( i == 0 ){
            colorType = 'green';
            title = 'S'
            color = 'green'
            labelClass = 'map-marker-labels-start'
            var marker = {
                position: {lat: parseFloat(stations[i]['lat']),lng: parseFloat(stations[i]['lng']) },
                map: map,
                //label: pointsValues[number2].toString(),//labels[labelIndex++ % labels.length],
                title: stations[i]['time'], //+ ' - Point ' + (number++),
                // labelContent: title,
                // labelAnchor: new google.maps.Point(10, 50),
                // labelClass: labelClass, // the CSS class for the label
                // labelInBackground: false,
                //info:'Hello',
                //icon: "https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_"+colorType+pointsValues[number2]+".png"
                icon: pinSymbol(color, title)
            };
        }else if((stations.length-1) == i) {
            colorType = 'red';
            title = 'D'
            color = 'red'
            labelClass = 'map-marker-labels-end'
            var marker = {
                position: {lat: parseFloat(stations[i]['lat']),lng: parseFloat(stations[i]['lng']) },
                map: map,
                //label: pointsValues[number2].toString(),//labels[labelIndex++ % labels.length],
                title: stations[i]['time'] + ' - Point ' + (number++),
                //icon: "https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_"+colorType+pointsValues[number2]+".png"
                icon: pinSymbol(color, title)
            };
        }else{
            colorType = 'black';
            var marker = {
                position: {lat: parseFloat(stations[i]['lat']),lng: parseFloat(stations[i]['lng']) },
                map: map,
                //label: pointsValues[number2].toString(),//labels[labelIndex++ % labels.length],
                title: stations[i]['time'], // + ' - Point ' + (number++),
                icon: "https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_"+colorType+pointsValues[number2]+".png"
                //icon: pinSymbol(color, title)
            };
        }    
        //Add new     
        var marker =  new google.maps.Marker(marker);
        var info = { content: 'Time: ' + stations[i]['time'] }        
        var iw = new google.maps.InfoWindow(info)

        // google.maps.event.addListener(marker, 'click', function (e) {
        //     iw.open(map, this)
        // })
        var content = 'Time: ' + stations[i]['time'];
        var infowindow = new google.maps.InfoWindow()
        google.maps.event.addListener(marker,'click', (
            function(marker,content,infowindow){ 
                return function() {
                    infowindow.setContent(content);
                    infowindow.open(map,marker);
                };
        })(marker,content,infowindow));  
    }

    // Divide route to several parts because max stations limit is 25 (23 waypoints + 1 origin + 1 destination)
    for (var i = 0, parts = [], max = 25 - 1; i < stations.length; i = i + max)
        parts.push(stations.slice(i, i + max + 1));

    // Service callback to process service results
    var service_callback = function(response, status) {
        if (status != 'OK') {
            console.log('Directions request failed due to ' + status);
            return;
        }
        var renderer = new google.maps.DirectionsRenderer;
        renderer.setMap(map);
        renderer.setOptions({ suppressMarkers: true, preserveViewport: true });
        renderer.setDirections(response);
    };

    // Send requests to service to get route (for stations count <= 25 only one request will be sent)
    for (var i = 0; i < parts.length; i++) {
        // Waypoints does not include first station (origin) and last station (destination)
        var waypoints = [];
        for (var j = 1; j < parts[i].length - 1; j++)
            waypoints.push({location:{lat:parseFloat(parts[i][j].lat), lng:parseFloat(parts[i][j].lng) } , stopover: false}); //console.log('parts[i][j]', parts[i][j] )
        // Service options
        var service_options = {
            origin: {lat: parseFloat(parts[i][0]['lat']),lng: parseFloat(parts[i][0]['lng']) }, //parts[i][0],
            destination: {lat: parseFloat(parts[i][parts[i].length - 1]['lat']),lng: parseFloat(parts[i][parts[i].length - 1]['lng']) }, //parts[i][parts[i].length - 1],
            waypoints: waypoints,
            travelMode: 'DRIVING',
            optimizeWaypoints: false
        };
        // Send request
        service.route(service_options, service_callback);
    }
  }

function pinSymbol(color, title) {
      return {
        path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z',
        fillColor: color,
        fillOpacity: 1,
        strokeColor: '#000',
        strokeWeight: (title === 'S' || title === 'D') ? 1.75 : 1.5,
        scale: (title === 'S' || title === 'D') ? 1.75 : 1.5
      }
    }

$(function(){ 
    var url = $(location).attr('href'),
    parts = url.split("/");
    map_id = parts[parts.length-1];
    $.ajax({
        url: base_url + "admin/users/getLiveUserRoute/"+map_id,
        method: 'POST',
        success:function(data) {
            data = JSON.parse(data);
            var mapArray = [];
            if(data.length > 0){

                // data.forEach(element => {
                //     mapArray.push({lat: parseFloat(element.lat), lng: parseFloat(element.lng)}); 
                // });  
                initMap(data);               
            }
        }
    });
    
});