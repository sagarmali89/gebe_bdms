
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="Sagar Mali">
        <link rel="icon" href="">

        <title>Water & Electricity Schedules</title>

        <!-- Bootstrap core CSS -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://getbootstrap.com/docs/4.1/dist/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- Custom styles for this template -->

        <style>
            /* Always set the map height explicitly to define the size of the div
             * element that contains the map. */
            #map {
                height: 600px;
                width: 900px;
                display: block;
            }
            /* Optional: Makes the sample page fill the window. */
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            html {
                font-size: 14px;
            }
            @media (min-width: 768px) {
                html {
                    font-size: 16px;
                }
            }

            .container {
                max-width: 960px;
            }

            .pricing-header {
                max-width: 700px;
            }

            .card-deck .card {
                min-width: 220px;
            }
            h3{
                text-align: center;
            }
        </style>
    </head>

    <body>

        <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
            <h5 class="my-0 mr-md-auto font-weight-normal">GEBE</h5>
            <nav class="my-2 my-md-0 mr-md-3">
                 <a class="btn btn-outline-primary" href="index.php/login">Log In</a>

            </nav>
            <a class="btn btn-outline-primary" href="#">Sign up</a>
        </div>

        <div class="container">
            <h3>Map</h3>
            <div class="row col-md-12">
                <div id="map"></div>
            </div>
        </div>
        <footer class="pt-4 my-md-5 pt-md-5 border-top">
            <div class="row">
                <div class="col-12 col-md">
                    <!--<img class="mb-2" src="../../assets/brand/bootstrap-solid.svg" alt="" width="24" height="24">-->
                    <small class="d-block mb-3 text-muted">&copy; 2018-2019</small>
                </div>
                <div class="col-6 col-md">
                    <h5>Features</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="#">Cool stuff</a></li>
                        <li><a class="text-muted" href="#">Random feature</a></li>
                        <li><a class="text-muted" href="#">Team feature</a></li>
                        <li><a class="text-muted" href="#">Stuff for developers</a></li>
                        <li><a class="text-muted" href="#">Another one</a></li>
                        <li><a class="text-muted" href="#">Last time</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md">
                    <h5>Resources</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="#">Resource</a></li>
                        <li><a class="text-muted" href="#">Resource name</a></li>
                        <li><a class="text-muted" href="#">Another resource</a></li>
                        <li><a class="text-muted" href="#">Final resource</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md">
                    <h5>About</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="#">Team</a></li>
                        <li><a class="text-muted" href="#">Locations</a></li>
                        <li><a class="text-muted" href="#">Privacy</a></li>
                        <li><a class="text-muted" href="#">Terms</a></li>
                    </ul>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script>
            // This example displays a map with the language and region set
            // to Japan. These settings are specified in the HTML script element
            // when loading the Google Maps JavaScript API.
            // Setting the language shows the map in the language of your choice.
            // Setting the region biases the geocoding results to that region.
            var map;
            var markers = [];
            var i = 0;
            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 14,
                    //st martin
                    center: {lat: 18.0252543, lng: -63.0426295}
                    //nashik
                    // center: {lat: 19.9974533, lng: 73.78980229999999}
                });
                var iconBase = 'http://dispatch.nvgebe.com/water_shd/assets/images/';
                var water_icon = {
                    url: iconBase + 'no_water.png',
                    scaledSize: new google.maps.Size(30, 30), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(0, 0) // anchor
                }
                var electricity_icon = {
                    url: iconBase + 'no_electricity.png',
                    scaledSize: new google.maps.Size(30, 30), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(0, 0) // anchor
                }

                var water = [
<?php
//print_r($water_schedules);
if (!empty($water_schedules)) {
    foreach ($water_schedules as $ro) {
        $expired = 0;
        if ($date == $ro->to_date) {
            if ($time > $ro->to_time) {
                $expired = 1;
            }
        }
        if (!$expired) {
            ?>
                                {
                                    position: new google.maps.LatLng(<?php echo $ro->lat; ?>, <?php echo $ro->longi; ?>),
                                            title: 'No Water',
                                    comments: '<?php echo $ro->comments; ?>',
                                            address: '<?php echo $ro->location; ?>',
                                    marker_id: <?php echo $ro->id; ?>
                                },
            <?php
        }
    }
}
?>

                ];

                water.forEach(function (feature) {
                    var contentString = '<b>' + feature.comments + '</b></br>' + ' <u>Address</u>: ' + feature.address;
                    var infowindow = new google.maps.InfoWindow({
                        content: contentString,
                        maxWidth: 200
                    });

                    var marker = new google.maps.Marker({
                        position: feature.position,
                        icon: water_icon,
                        title: feature.title,
                        map: map
                    });
                    google.maps.event.addListener(marker, 'click', (function (marker, infowindow) {
                        return function () {
                            infowindow.setContent(contentString);
                            infowindow.open(map, marker);
                        };
                    })(marker, infowindow));
                });
                var electricity = [
<?php
if (!empty($electricity_schedules)) {
    foreach ($electricity_schedules as $ro1) {
        $expired = 0;
        if ($date == $ro->to_date) {
            if ($time > $ro->to_time) {
                $expired = 1;
            }
        }
        if (!$expired) {
            ?>
                                {
                                    position: new google.maps.LatLng(<?php echo $ro1->lat; ?>, <?php echo $ro1->longi; ?>),
                                            title: 'No Electricity',
                                    comments: '<?php echo $ro1->comments; ?>',
                                            address: '<?php echo $ro1->location; ?>'
                                },
            <?php
        }
    }
}
?>
                ];


                electricity.forEach(function (feature) {
                    var contentString = '<b>' + feature.comments + '</b></br>' + ' <u>Address</u>: ' + feature.address;

                    var infowindow = new google.maps.InfoWindow({
                        content: contentString,
                        maxWidth: 200
                    });
                    var marker = new google.maps.Marker({
                        position: feature.position,
                        icon: electricity_icon,
                        title: feature.title,
                        map: map
                    });
                    google.maps.event.addListener(marker, 'click', (function (marker, infowindow) {
                        return function () {
                            infowindow.setContent(contentString);
                            infowindow.open(map, marker);
                        };
                    })(marker, infowindow));
                });
            }



        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADnmRWZ9CpKrAxD6WUsBPp0gc6FkWVEy8&sensor=false&callback=initMap&language=en">
        </script>
    </body>
</html>
