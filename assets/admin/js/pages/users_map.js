/*
 *  Document   : compMaps.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Maps page
 */

var CompMaps = function(para) {

    return {
        init: function(para) {
            /*
             * With Gmaps.js, Check out examples and documentation at http://hpneo.github.io/gmaps/examples.html
             */

            // Set default height to all Google Maps Containers
            $('.gmap').css('height', '850px');

            // Initialize map with markers
            new GMaps({
                div: '#gmap-markers',
                lat: 20.593683,
                lng: 78.962883,
                zoom: 4,
                scrollwheel: false
            }).addMarkers(para
               /* [
                {lat: 20, lng: -31, title: 'Marker #1', animation: google.maps.Animation.DROP, infoWindow: {content: '<strong>Marker #1: HTML Content</strong>'}},
                {lat: -10, lng: 0, title: 'Marker #2', animation: google.maps.Animation.DROP, infoWindow: {content: '<strong>Marker #2: HTML Content</strong>'}},
                {lat: -20, lng: 85, title: 'Marker #3', animation: google.maps.Animation.DROP, infoWindow: {content: '<strong>Marker #3: HTML Content</strong>'}},
                {lat: -20, lng: -110, title: 'Marker #4', animation: google.maps.Animation.DROP, infoWindow: {content: '<strong>Marker #4: HTML Content</strong>'}}
            ]*/ );
           // alert(para);
        }
    };
}();

$(function(){ 
    $.ajax({
        url: base_url + "admin/users/getLiveAttandance",
        method: 'POST',
        success:function(data) {
            data = JSON.parse(data);
            var mapArray = [];
            if(data.length > 0){
                data.forEach(element => {
                    //mapArray.push({lat: element.lat, lng: element.long, title: 'Location', animation: google.maps.Animation.DROP, infoWindow: {content: '<strong>'+ element.name +'</strong><br /><span>'+ element.dateTime +'</span><a href="viewEmployeeDetails/'+element.id+'">View Route</a>'}}); 
                      mapArray.push({lat: element.lat, lng: element.long, title: 'Location', animation: google.maps.Animation.DROP, infoWindow: {content: '<ul class="list-unstyled"> <li><strong>Name - </strong>'+ element.name +'</li><li><strong>Last Ping - </strong><span>'+ element.dateTime +'</span></li><li><strong>GPS Status - </strong>'+ element.gps +'</li><li><strong>Network - </strong>'+ element.network +'</li><li><strong>Battery - </strong>'+ element.battery +'</li><li><button class="btn btn-sm btn-info"><a href="viewEmployeeDetails/'+element.id+'">View Route</a></button></li></ul>'}});  
                });                
            }
            console.log(mapArray);
            CompMaps.init(mapArray); 
        }
    });
    
});