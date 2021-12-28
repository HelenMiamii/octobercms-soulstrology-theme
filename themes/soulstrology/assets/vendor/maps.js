    function activatePlacesSearch(){
        
        var input = document.getElementById('place');
        var options = {
                  types: ['(cities)'],
                    };
                    
        var autocomplete = new google.maps.places.Autocomplete(input, options);
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();
        var lat = document.getElementById('latitude').value;
        var lon = document.getElementById('longitude').value;
        var loc = [ lat, lon ];
        var year = document.getElementById('year').value;
        var month = document.getElementById('month').value - 1;
        var day = document.getElementById('day').value;
        var hour = document.getElementById('hour').value;
        var min = document.getElementById('minute').value;
        var date = new Date( year, month, day, hour, min, 0, 0 );
        console.log(date);
        var timestamp = date / 1000;
        var ourRequest = new XMLHttpRequest();
        ourRequest.open('GET', 'https://maps.googleapis.com/maps/api/timezone/json?location='+ loc + '&timestamp=' + timestamp + '&key=AIzaSyBXEAYjBPbDub-KJ3IiVSs7Un2JXU29hQM');
        
        ourRequest.onload = function(){
        var ourData = JSON.parse(ourRequest.responseText);
        console.log(ourData);
        var zoneOne = ourData.rawOffset + ourData.dstOffset;
        var zone = zoneOne / 3600;
        document.getElementById('timezone').value = zone;
        };
        ourRequest.send();
        });
        
    }