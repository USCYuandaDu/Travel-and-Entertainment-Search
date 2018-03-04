<html>
    <head>
        <title>Travel and Entertainment Search</title>

        <style>
            body {
                font-family: "fsalbert", sans-serif;
            }
            #searchScreen {
                margin: 100px auto;
                padding: 5px;
                width: 600px;
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


        </style>

    </head>

    <body>

        <div id="searchScreen">
            <h1>Travel and Entertainment Search</h1>
            <hr>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <b>Keyword</b> <input type="text" name="keyword" id="keyword" required  value="<?php echo isset($_POST['keyword']) ? $_POST['keyword'] : '' ?>"/><br>
                <b>Category</b> <SELECT NAME="category" id="category" value="<?php echo isset($_POST['category']) ? $_POST['category'] : '' ?>">
                    <OPTION SELECTED> default</OPTION>
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
                <INPUT TYPE="radio" Name="from" id = "here" value = "here" required checked>Here<BR>
                <INPUT TYPE="radio" Name="from" id="location" value = "start" <?php if (isset($_POST['from']) && $_POST['from'] == "start") echo "checked";?>><input id="loc" type="text" name="location" placeholder="location" required value="<?php echo isset($_POST['location']) ? $_POST['location'] : '' ?>"/><BR>
                <input type="submit" id = "searchBtn" name="submit" value="Search" disabled>
                <button type="button" id = "clearBtn">Clear</button>
            </form>

        </div>
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
                    console.log(myObj1["lat"]);
                    console.log(myObj1["lon"]);
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
            }

            dot1.addEventListener("click", click_d1);
            dot2.addEventListener("click", click_d2);
            clear_btn.addEventListener("click", clear_table);
        </script>
        <?php

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo $_POST['keyword'];
                echo $_POST['category'];
                echo $_POST['distance'];

            }
        ?>


    </body>
</html>



