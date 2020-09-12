<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css" />
    </head>
    <body>
        
        <h1>Jacket or no Jacket</h1>   
        <input type="text" class="form-control" id="city" placeholder="Enter city name here (E.g New York)" >
        <button type="submit" onclick="getWeatherFromApi()" class="btn btn-primary">Get from API</button>
        <button type="submit" onclick="getWeatherFromDB()" class="btn btn-warning">Get from DB</button>
        
        <div className="details">
            <p id="city_name"></p>
            <p id="period"></p>
            <p id="start_time"></p>
            <p id="end_time"></p>
        </div>
        
        <button id="second-button" type="button" onclick="saveWeatherToDB()"  class="btn btn-success hide">save forecast</button>
        <table class="table table-borded table-striped" id="weather_table"></table>
        
        <script>
            var data_global;
            function getWeatherFromApi() {
                $('#weather_table').empty();
                var city = document.getElementById("city").value;
                if(city){
                    var jsonURL = 'http://api.openweathermap.org/data/2.5/forecast?q=' + city + '&units=metric&appid=e4b8b08c185638b825af37facfe1fabb';
                    $.getJSON(jsonURL, function(data) {
                        data_global = data;
                        var city_name = data.city.name;
                        var start = data.list[0].dt_txt; 
                        var end = data.list[data.list.length-1].dt_txt;
                        document.getElementById("city_name").innerHTML = city_name;
                        document.getElementById("period").innerHTML = 'period';
                        document.getElementById("start_time").innerHTML = 'start at ' +start;
                        document.getElementById("end_time").innerHTML = 'ended at ' +end;
                        $('#second-button').removeClass('hide');
                        var weather_data= '<tr><th>date time</th><th>min temp</th><th>max temp</th><th>wind speed</th></tr>';
                        $.each(data.list, function(key, value){
                               weather_data += '<tr>';
                               weather_data += '<td>'+value.dt_txt+'</td>';
                               weather_data += '<td>'+value.main.temp_min+'°C</td>';
                               weather_data += '<td>'+value.main.temp_max+'°C</td>';
                               weather_data += '<td>'+value.wind.speed+'km/h</td>';
                               weather_data += '</tr>';
                        });
                        $('#weather_table').append(weather_data);
                    });
                }
            }
            function getWeatherFromDB(){
                $('#weather_table').empty();
                var city_name = document.getElementById("city").value;
                $.ajax({
                    url: '/weather/getDB.php',
                    type: 'POST',
                    data: { city_name: city_name },
                    success: function(data) {
                        if(data != 'none'){
                            var weatherJSON = $.parseJSON(data);
                            var weather_data= '<tr><th>date time</th><th>min temp</th><th>max temp</th><th>wind speed</th></tr>';
                            weather_data += '<tr>';
                            weather_data += '<td>'+weatherJSON.t_date_time+'</td>';
                            weather_data += '<td>'+weatherJSON.t_temp_min+'°C</td>';
                            weather_data += '<td>'+weatherJSON.t_temp_max+'°C</td>';
                            weather_data += '<td>'+weatherJSON.w_speed+'km/h</td>';
                            weather_data += '</tr>';
                            document.getElementById("city_name").innerHTML = weatherJSON.c_city_name;
                            document.getElementById("start_time").innerHTML = 'updated at ' +weatherJSON.updated_at;
                            $('#weather_table').append(weather_data);
                        }
                    }
                })
            }
            function saveWeatherToDB(){
                var city_name = data_global.city.name;
                var temp_min = data_global.list[0].main.temp_min;
                var temp_max = data_global.list[0].main.temp_max;
                var date_time = data_global.list[0].dt_txt;
                var wind = data_global.list[0].wind.speed;
                $.ajax({
                    url: '/weather/postDB.php',
                    type: 'POST',
                    data: { city_name: city_name, temp_min: temp_min, temp_max: temp_max, date_time: date_time, wind: wind},
                    success: success
                });
            }
        </script>
</body>
</html>
