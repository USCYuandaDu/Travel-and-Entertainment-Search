<?php
    if(isset($_POST['place_id']) ) {
        $place_id = $_POST['place_id'];
        $url_get_details =  "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$place_id."&key=AIzaSyCAvy8XyXx7FbQl5A0YBeBwJptHHECMK9c";
        $url_get_details = str_replace(" ", "%20", $url_get_details);
        $json_result_details = file_get_contents($url_get_details);
        $data = json_decode($json_result_details);
        $photos = array_key_exists("photos", $data->result) ? $data->result->photos : array();
        $reviews = array_key_exists("reviews", $data->result) ? $data->result->reviews : array();
        //print_r($data);
        if(sizeof($photos) > 5) {
            $photos = array_slice($photos, 0, 5);
        }
        if(sizeof($reviews) > 5) {
            $reviews = array_slice($reviews, 0, 5);
        }
        //print_r($photos);
        $index = 0;
        foreach($photos as $photo) {
            $refer = $photo->photo_reference;
            $width = $photo->width;
            $height = $photo->height;
            $url_get_small_photo = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=750&photoreference=".$refer."&key=AIzaSyCAvy8XyXx7FbQl5A0YBeBwJptHHECMK9c";
            $url_get_big_photo = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=".$width."&maxheight=".$height."&photoreference=".$refer."&key=AIzaSyCAvy8XyXx7FbQl5A0YBeBwJptHHECMK9c";
            $small_photo = file_get_contents($url_get_small_photo);
            $big_photo = file_get_contents($url_get_big_photo);
            $file_small_name = $index."_small.png";
            $file_big_name = $index."_big.png";
            $index++;
            file_put_contents($file_small_name, $small_photo);
            file_put_contents($file_big_name, $big_photo);
        }
        $photos_and_reviews = array();
        array_push($photos_and_reviews, $photos, $reviews);
        print_r(json_encode($photos_and_reviews));
        exit();
    }
?>
<html>
    <head>
        <title>Travel and Entertainment Search</title>

        <style>
            body {
                font-family: "libre baskerville";
            }
            #searchScreen {
                margin: 100px auto;
                padding: 5px;
                width: 600px;
                height:230px;
                background-color: #f7f7f7;
                border: 2px solid #c2c2c2;
            }

            h1 {
                margin: 0;
                padding: 5px 10px;
                text-align: center;
                font-style: italic;
            }

            hr {
                color: #c2c2c2;
            }

            ul,  li{display:block; position:relative;
            left:  145px;
            top: -18px;}

            #searchBtn {
                position:relative;
                left: 50px;
                top: -40px;
            }
            #clearBtn {
                position:relative;
                left: 53px;
                top: -40px;
            }

            table
            {
                border-collapse:collapse;
                margin: 100px auto;
            }

            table,th, td
            {
                border: 1px solid black;
            }



        </style>

    </head>
    <body>
        <div id="searchScreen" style="z-index: 100000">
            <h1>Travel and Entertainment Search</h1>
            <hr>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <b>Keyword</b> <input type="text" name="keyword" id="keyword" required  value="<?php echo isset($_POST['keyword']) ? $_POST['keyword'] : '' ?>"/><br>
                <b>Category</b> <SELECT NAME="category" id="category" selected="<?php echo isset($_POST['category']) ? $_POST['category'] : '' ?>">
                    <OPTION> default</OPTION>
                    <OPTION> cafe </OPTION>
                    <OPTION> bakery </OPTION>
                    <OPTION> restaurant </OPTION>
                    <OPTION> beauty salon </OPTION>
                    <OPTION> casino </OPTION>
                    <OPTION> movie theater </OPTION>
                    <OPTION> lodging </OPTION>
                    <OPTION> airport </OPTION>
                    <OPTION> tran station </OPTION>
                    <OPTION> subway station  </OPTION>
                    <OPTION> bus station </OPTION>
                </SELECT><BR>
                <b>Distance (miles)</b>
                <input type="text" name="distance" id="distance" placeholder="10" value="<?php echo isset($_POST['distance']) ? $_POST['distance'] : '' ?>"/>
                <b>From</b>
                <ul id="ul1"><li>
                        <INPUT TYPE="radio" Name="from" id = "here" value = "here" checked>Here</li><BR>
                    <li>
                        <INPUT TYPE="radio" Name="from" id="location" value = "start" <?php if (isset($_POST['from']) && $_POST['from'] == "start") echo "checked";?>><input id="loc" type="text" name="location" placeholder="location" disabled value="<?php echo isset($_POST['location']) ? $_POST['location'] : '' ?>"/><BR>
                    </li>
                </ul>
                <input type="submit" id = "searchBtn" name="submit" value="Search" disabled>
                <button type="button" id = "clearBtn">Clear</button>
            </form>

        </div>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript">
            var xhttp = new XMLHttpRequest();
            var dot1 = document.getElementById("here");
            var dot2 = document.getElementById("location");
            var dot2_text = document.getElementById("loc");
            var keyword = document.getElementById("keyword");
            var distance = document.getElementById("distance");
            var clear_btn = document.getElementById("clearBtn");
            var sub_btn = document.getElementById("searchBtn");
            var category = document.getElementById("category");
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    myObj1 = JSON.parse(xhttp.responseText);
                    var point = new Array();
                    point[0] = myObj1["lat"];
                    point[1] = myObj1["lon"];
                    dot1.value = point;
                    document.getElementById("searchBtn").disabled = false;

                }
            };
            xhttp.open("GET", "http://ip-api.com/json", true);
            xhttp.send();


            function click_d1() {
                dot1.checked = true;
                dot2_text.disabled = true;
                dot2_text.required = false;
            }
            function click_d2() {
                dot2.checked = true;
                dot2_text.disabled = false;
                dot2_text.required = true;

            }
            function clear_table() {
                click_d1();
                dot2_text.value = "";
                dot2_text.placeholder = "location";
                keyword.value = "";
                distance.value = "";
                distance.placeholder = "10";
                category.value = "default";
                document.getElementById("table2").style.display = 'none';
                document.getElementById("b1").style.display = 'none';
                document.getElementById("b2").style.display = 'none';
                document.getElementById("b3").style.display = 'none';
            }
            dot1.addEventListener("click", click_d1);
            dot2.addEventListener("click", click_d2);
            clear_btn.addEventListener("click", clear_table);
        </script>
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $keyword = $_POST['keyword'];
                $category = $_POST['category'];
                $distance = $_POST['distance'] == "" ? 10 : (int)$_POST['distance'];
                $from = $_POST['from'];
                if($from == "start") {
                    $from = "";
                    $address = $_POST['location'];
                    $url_get_location =  "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=AIzaSyCwODd-uQyaZIcV8MWo3Ffo9ZxpyPmZf3M";
                    $url_get_location = str_replace(" ", "%20", $url_get_location);
                    $json_result_location = file_get_contents($url_get_location);
                    $data = json_decode($json_result_location);

                    if(sizeof($data->results) != 0) {
                        $x = $data->results[0]->geometry->location->lat;
                        $y = $data->results[0]->geometry->location->lng;
                        $from = $x.",".$y;
                    }
                    else {
                        $from = '';
                    }
                    $_POST['from'] = $from;

                }
                $distance = $distance * 1609;
                $url_get_infos = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$from."&radius=".$distance."&type=".$category."&keyword".$keyword."&key=AIzaSyCAvy8XyXx7FbQl5A0YBeBwJptHHECMK9c";
                $url_get_infos = str_replace(" ", "%20", $url_get_infos);
                $json_result_infos = file_get_contents($url_get_infos);
            }

        ?>
        <script language="javascript">
            var raw_results = <?php echo $json_result_infos;?>;
            var position = "<?php echo isset($_POST['from']) ? $_POST['from'] : '';?>";

            position = position.split(",");

            console.log(position);
            var point = {lat: -25.363, lng: 131.044};
            var jsonObj =raw_results["results"];
            document.write("<div id='map' style = 'height:400px; width:300px; position: absolute; z-index:1'></div>");
            document.write("<div ><button onclick='calcRouteWalk()' type='button' id ='b1' style='position:absolute; top:1px; z-index:100000'>Walk there</button></div>");
            document.write("<div ><button onclick='calcRouteBike()' type='button' id ='b2' style='position:absolute; top:10px; z-index:100000'>Bike there</button></div>");
            document.write("<div ><button onclick='calcRouteDrive()' type='button' id ='b3' style='position:absolute; top:20px; z-index:100000'>Drive there</button></div>");
            var map;
            var marker;
            var start;
            var end;
            var directionsService;
            var directionsDisplay;

            function initMap() {
                directionsService = new google.maps.DirectionsService();
                directionsDisplay = new google.maps.DirectionsRenderer();
                map = new google.maps.Map(document.getElementById('map'), {
                    center: point,
                    zoom: 10
                });
                marker = new google.maps.Marker({
                    position: point,
                    map: map
                });
                start = new google.maps.LatLng(parseFloat(position[0]), parseFloat(position[1]));


            }
            function calcRouteWalk() {
                directionsDisplay.setMap(null);
                marker.setMap(null);
                var request = {
                    origin: start,
                    destination: end,
                    travelMode: 'WALKING'
                };
                directionsService.route(request, function(result, status) {
                    if (status == 'OK') {
                        directionsDisplay.setDirections(result);
                    }
                });
                directionsDisplay.setMap(map);
            }
            function calcRouteBike() {
                directionsDisplay.setMap(null);
                marker.setMap(null);
                var request = {
                    origin: start,
                    destination: end,
                    travelMode: 'BICYCLING'
                };
                directionsService.route(request, function(result, status) {
                    if (status == 'OK') {
                        directionsDisplay.setDirections(result);
                    }
                });
                directionsDisplay.setMap(map);
            }
            function calcRouteDrive() {
                directionsDisplay.setMap(null);
                marker.setMap(null);
                var request = {
                    origin: start,
                    destination: end,
                    travelMode: 'DRIVING'
                };
                directionsService.route(request, function(result, status) {
                    if (status == 'OK') {
                        directionsDisplay.setDirections(result);
                    }
                });
                directionsDisplay.setMap(map);
            }
            document.getElementById("map").style.display = 'none';
            document.getElementById("b1").style.display = 'none';
            document.getElementById("b2").style.display = 'none';
            document.getElementById("b3").style.display = 'none';
            function showT1() {
                document.getElementById("p1").style.display = 'none';
                document.getElementById("p11").style.display = 'block';
                document.getElementById("t1").style.display = 'block';
                document.getElementById("m1").style.display = 'none';
                document.getElementById("m11").style.display = 'block';

            }
            function disapperT1() {
                document.getElementById("p1").style.display = 'block';
                document.getElementById("p11").style.display = 'none';
                document.getElementById("t1").style.display = 'none';
                document.getElementById("m1").style.display = 'block';
                document.getElementById("m11").style.display = 'none';
            }
            function showT2() {
                document.getElementById("p2").style.display = 'none';
                document.getElementById("p22").style.display = 'block';
                document.getElementById("t2").style.display = 'block';
                document.getElementById("m2").style.display = 'none';
                document.getElementById("m22").style.display = 'block';

            }
            function disapperT2() {
                document.getElementById("p2").style.display = 'block';
                document.getElementById("p22").style.display = 'none';
                document.getElementById("t2").style.display = 'none';
                document.getElementById("m2").style.display = 'block';
                document.getElementById("m22").style.display = 'none';
            }
            function clickReview() {
                if(document.getElementById("t1").style.display == 'none') {
                    showT1();
                    disapperT2();
                } else {
                    disapperT1();
                }
            }

            function clickPhotos() {
                if(document.getElementById("t2").style.display == 'none') {
                    showT2();
                    disapperT1();
                } else {
                    disapperT2();
                }
            }



            function generatereviewtable (obj, index) {
                var place_name = raw_results.results[index].name;
                document.write("<div><h2 style='text-align: center; '>"+place_name+"</h2></div>");
                document.write("<p id= 'p1' style='text-align:center; '>click to show reviews</p>");
                document.write("<p id= 'p11' style='text-align:center; '>click to hide reviews</p>");
                document.write("<img id = 'm1' style=' position:absolute;top: 70px; left: 48%; width:30px; height:20px; margin: 0px auto;' src = 'p1.png'>");
                document.write("<img id = 'm11' style='position:absolute; top: 70px; left: 48%; width:30px; height:20px; margin: 0px auto;' src = 'p2.png'>");
                if(obj[1].length == 0) {
                    document.write("<table id = 't1' style='border-collapse:collapse;  margin: 100px auto; width:600px' border = 1> <tbody><tr><th>No Reviews Found</th></tr></tbody></table> ");

                } else {
                    document.write("<table id = 't1' style='border-collapse:collapse;  margin: 100px auto;width:600px' border = 1><tbody>");
                    for(var i=0; i<obj[1].length; i++ ) {
                        document.write("<tr><th><img style = 'height=5px; width=5px;' src='" + obj[1][i].profile_photo_url +"'>" + obj[1][i].author_name + "</th></tr>");
                        document.write("<td>" + obj[1][i].text + "</td>");
                    }
                    document.write("</tbody></table>");
                }
                document.getElementById("t1").style.display = 'none';
                document.getElementById("p11").style.display = 'none';
                document.getElementById("m11").style.display = 'none';
            }


            function generatephototable (obj, index) {
                document.write("<p id= 'p2' value = 'show2' style='text-align:center; '>click to show photos</p>");
                document.write("<p id= 'p22' value = 'show2' style='text-align:center; '>click to hide photos</p>");
                document.write("<img id = 'm2' style='position:absolute;top: 100px; left: 48%; width:30px; height:20px; margin: 0px auto;' src = 'p1.png'>");
                document.write("<img id = 'm22' style='position:absolute;top: 100px; left: 48%;width:30px; height:20px; margin: 0px auto;' src = 'p2.png'>");
                if (obj[0].length == 0) {
                    document.write("<table id = 't2' style='margin: 0px auto; width:600px'> <tbody><tr><th>No Photos Found</th></tr></tbody></table> ");

                } else {
                    document.write("<table id = 't2' style='margin: 0px auto;width:600px' ><tbody>");
                    for(var i=0; i<obj[0].length; i++ ) {
                        document.write("<tr><th><a href='" + i + "_big.png'" + "><img  src='" + i +"_small.png'>" +"</a></th></tr>");

                    }
                    document.write("</tbody></table>");
                }
                document.getElementById("t2").style.display = 'none';
                document.getElementById("p22").style.display = 'none';
                document.getElementById("m22").style.display = 'none';
            }
            function selectRow(obj) {
                if(event.srcElement.tagName="TD") {

                    var curRow = event.srcElement.parentElement;
                    console.log(event.srcElement.offsetTop);
                    console.log(event.srcElement.offsetLeft);
                    var cName = event.srcElement.className;
                    var cur_index = Math.floor(curRow.rowIndex / 2);
                    if (cName == "name") {
                        //method1
                        var cur_place_id = raw_results.results[cur_index].place_id;
                        document.getElementById("table2").style.display = 'none';
                        var xmlhttp = new XMLHttpRequest();

                        xmlhttp.onreadystatechange = function() {
                            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                            }
                        }

                        xmlhttp.open("POST", "place.php", false);
                        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xmlhttp.send("place_id=" + cur_place_id);
                        var pictures_and_reviews = JSON.parse(xmlhttp.responseText);
                        console.log(pictures_and_reviews);

                        //table
                        //picture small(750) big
                        document.getElementById("searchScreen").style.display = 'block';

                        //
                        generatereviewtable (pictures_and_reviews, cur_index);
                        generatephototable (pictures_and_reviews, cur_index);
                        document.getElementById("p1").addEventListener("click", clickReview);
                        document.getElementById("p11").addEventListener("click", clickReview);
                        document.getElementById("p2").addEventListener("click", clickPhotos);
                        document.getElementById("p22").addEventListener("click", clickPhotos);

                    }
                    if (cName == "addr") {
                        var d_lat = raw_results.results[cur_index].geometry.location.lat;
                        var d_lng = raw_results.results[cur_index].geometry.location.lng;
                        var top = event.srcElement.offsetTop;
                        var left = event.srcElement.offsetLeft;
                        point = {lat: d_lat, lng: d_lng};
                        document.getElementById("map").style.top = top + 500;
                        document.getElementById("map").style.left = left + 400;
                        document.getElementById("b1").style.top = top + 500;
                        document.getElementById("b1").style.left = left + 400;
                        document.getElementById("b2").style.top = top + 515;
                        document.getElementById("b2").style.left = left + 400;
                        document.getElementById("b3").style.top = top + 530;
                        document.getElementById("b3").style.left = left + 400;
                        marker.setMap(null);
                        marker = new google.maps.Marker({
                            position: point
                        });
                        marker.setMap(map);
                        map.panTo(new google.maps.LatLng(d_lat, d_lng));
                        end = new google.maps.LatLng(d_lat, d_lng);
                        directionsDisplay.setMap(null);
                        if(document.getElementById("map").style.display == 'none') {
                            document.getElementById("map").style.display = 'block';
                            document.getElementById("b1").style.display = 'block';
                            document.getElementById("b2").style.display = 'block';
                            document.getElementById("b3").style.display = 'block';
                        } else {
                            document.getElementById("map").style.display = 'none';
                            document.getElementById("b1").style.display = 'none';
                            document.getElementById("b2").style.display = 'none';
                            document.getElementById("b3").style.display = 'none';
                        }
                    }
                }
                //<?php if(isset($_GET['text'])) echo "alert('".$_GET['text']."')";?>;

            }
            function generateTable () {
                document.write("<table border='1' id = 'table2' onclick='selectRow(this)'> <tbody> <tr><th>Category</th><th>Name</th><th>Address</th></tr>");
                for(var i=0; i<raw_results.results.length; i++) {
                    var id = "data_"+i;
                    document.write("<tr id = " + id + "><td><img src='" + raw_results.results[i].icon + "'></td>");
                    document.write("<td class='name'>" + raw_results.results[i].name +"</td>");
                    //document.write("</td>");
                    document.write("<td class='addr'>" + raw_results.results[i].vicinity +"</td>");
                    document.write("<tr>");
                }
                document.write("</table>");
            }
            console.log("<?php echo isset($_POST['from']) ? $_POST['from'] : "F";?>");
            if (raw_results["results"].length == 0 || ("<?php echo isset($_POST['from']) && $_POST['from'] == "" ? "T" : "F";?>" == "T")) {
                document.write("<table border='1'> <tbody> <tr><th>No records have been found</th></tr></table>");
            } else {
                if (raw_results != null) {
                    generateTable();
                }
            }

        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCmERdp0B4DmjlHT9f6DFD1q2XOIwdoqPA&callback=initMap"
                async defer></script>
    </body>
</html>



