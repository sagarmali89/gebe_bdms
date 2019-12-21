<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php if (isset($edit_breakdown) && $edit_breakdown > 0) { ?>
                <i class="fa fa-file-text-o"></i>
            <?php } else { ?>
                <i class="fa fa-file-o"></i>
            <?php } ?>

            Map
            <small><?php
                echo $dashboardTitle;
                ?></small>
        </h1>
    </section>
    <style>
        .modal-dialog{
            width: 900px;
            height: 810px;
        }
        .modal-content{
            height: 100%;
        }
    </style>
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <section class="content">

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $dashboardTitle; ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div id="map"></div>
                            </div>
                        </div>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js" charset="utf-8"></script>
<!--<script type="text/javascript" src="http://jdewit.github.io/bootstrap-timepicker/js/bootstrap-timepicker.js" charset="utf-8"></script>-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />

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


<script type="text/javascript">
    jQuery(document).ready(function () {
        var table = $('#example').DataTable();
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '-7d',
            autoclose: true
        });
        $('.edit_tech').on('click', function () {
            var tech_id = $(this).data('tech_id');
            $.ajax({
                url: "<?php echo base_url() . $redirect_key . 'getTechnicianDetails'; ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    tech_id: tech_id,
                },
                success: function (data) {
                    $('#updateAbsenceModal .name_update').val(data.name);
                    $('#updateAbsenceModal .ability_update').val(data.ability);
                    $('#updateAbsenceModal #technician_id').val(data.id);
                    //                    $('#updateAbsenceModal #update_node_id').val(data.node_id);
                    $('#updateAbsenceModal').modal('show');
                }
            });
        });
        $('.delete_btn').on('click', function () {
            var del_id = $(this).data('del_id');
            var del_tbl = $(this).data('del_tbl');
            var del_this = $(this);
            var x = confirm('are you sure you want to delete this ?');
            if (x) {
                $.ajax({
                    url: "<?php echo base_url() . $redirect_key . 'deleteRow'; ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        del_id: del_id,
                        del_tbl: del_tbl,
                    },
                    success: function (data) {
                        del_this.parent().parent().hide();
                    }
                });
            }
        });
    });</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADnmRWZ9CpKrAxD6WUsBPp0gc6FkWVEy8&callback=initMap&language=en">
</script>