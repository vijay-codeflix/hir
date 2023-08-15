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
        url: base_url + "admin/dealers/getDealersmark",
        method: 'POST',
        success:function(data) {
            data = JSON.parse(data);
            var mapArray = [];
            if(data.length > 0){
                data.forEach(element => {
                    mapArray.push({lat: element.lat, lng: element.lng, title: 'Location', animation: google.maps.Animation.DROP, infoWindow: {content: '<strong> Dealer - </strong>'+ ((element.dealer_name == '') ? element.firm_name :  element.dealer_name +' - '+ element.firm_name )+'<br /><span> Branch - '+ element.name +'</span>'}}); 
                });                
            }
            //console.log(mapArray);
            CompMaps.init(mapArray); 
        }
    });
    
});