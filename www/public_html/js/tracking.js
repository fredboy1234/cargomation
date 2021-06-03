$(document).ready(function(){
   
     //This is for live tracking
    var map = L.map('livemap').setView([10.3130247, 123.9471531], 5);
    var livepolyArray = [];

    L.tileLayer('https://{s}.tile.jawg.io/jawg-matrix/{z}/{x}/{y}{r}.png?access-token=iPr7S2yMM5rvXzDFNlFW35qgk2HTvVSuZTgY6EWcMYgYknPfEnPYAhIbB366OUeC', {
      attribution: '<a href="http://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank">&copy; <b>Jawg</b>Maps</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      minZoom: 0,
      maxZoom: 13,
      accessToken: 'iPr7S2yMM5rvXzDFNlFW35qgk2HTvVSuZTgY6EWcMYgYknPfEnPYAhIbB366OUeC'
    }).addTo(map);

    //set marker and popup
    var greenIcon = L.divIcon({
        html: '',
        iconSize: [5, 5],
        className: 'liveIcon'
    });

    var currentlocationIcon = L.divIcon({
        iconSize: [30, 30],
        iconAnchor: [10, 10],
        popupAnchor: [10, 0],
        shadowSize: [0, 0],
        className: 'animated-icon my-icon-id' 
    })


    $.each(livedata.data.route,function(key,val){
        if(val.type="SEA"){
            $.each(val.path,function(kk,ikal){
                L.marker(ikal,{icon: greenIcon}).addTo(map);
                livepolyArray.push(ikal); 
            });  
        } 
    });
    
    L.marker(livedata.data.pin,{icon: currentlocationIcon}).addTo(map);

    var polyline = L.polyline(livepolyArray
        , {
        color: '#00FF00',
        weight: 3,
        opacity: 0.7,
        dashArray: '4',
        lineJoin: 'miter',
        lineCap: 'square'
    }).addTo(map);

    //map.fitBounds(polyline.getBounds());
    console.log(polyline.getBounds());
    map.setView(livedata.data.pin, 6);
});