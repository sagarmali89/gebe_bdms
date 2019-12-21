<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Schedules
            <small>Add, Edit, Delete, Export</small>
        </h1>
    </section>
    <style>
        .width50{

        }
        .modal-dialog{
            width: 900px;
            height: 810px;
        }
    </style>



    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class=" pull-left left">
                    <?php
                    $error = $this->session->flashdata('error');
                    if ($error) {
                        ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $this->session->flashdata('error'); ?>                    
                        </div>
                    <?php } ?>
                    <?php
                    $success = $this->session->flashdata('success');
                    if ($success) {
                        ?>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="form-group pull-right right">
                    <div class="col-xs-12 text-right">
                        <div class="form-group">
                            <span class="btn btn-primary add_street_name" data-toggle="modal" data-target="#addSendToModal"><i class="fa fa-plus"></i> Add New</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"></h3>
                        <div class="box-tools">

                        </div>
                    </div><!-- /.box-header -->

                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>From Date</th>
                                    <th>From Time</th>
                                    <th>To Date</th>
                                    <th>To Time</th>
                                    <th>Location</th>
                                    <th>Comment</th>
                                    <th>Created Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($schedules)) {
                                    foreach ($schedules->result() as $record) {
                                        $expired = 0;
                                        if ($date == $record->to_date) {
                                            if ($time > $record->to_time) {
                                                $expired = 1;
                                            }
                                        }
                                        if (!$expired) {
                                            ?>
                                            <tr>
                                                <td><?php echo $record->id; ?></td>
                                                <td><?php echo $record->scheduled_type; ?></td>

                                                <td><?php echo $record->from_date; ?></td>
                                                <td><?php echo $record->from_time; ?></td>
                                                <td><?php echo $record->to_date; ?></td>
                                                <td><?php echo $record->to_time; ?></td>
                                                <td><?php echo $record->location; ?></td>
                                                <td><?php echo $record->comments; ?></td>
                                                <td><?php echo date("Y-m-d", strtotime($record->createdDtm)) ?></td>
                                                <td class="text-center">
                                                    <span class="btn btn-sm btn-info update_schedule" data-to_time="<?php echo $record->to_time; ?>" data-to_date="<?php echo $record->to_date; ?>" data-from_time="<?php echo $record->from_time; ?>" data-from_date="<?php echo $record->from_date; ?>" data-comments="<?php echo $record->comments; ?>"  data-longi="<?php echo $record->longi; ?>"  data-lat="<?php echo $record->lat; ?>"  data-location="<?php echo $record->location; ?>"  title="Edit" data-shd_id="<?php echo $record->id; ?>"><i class="fa fa-pencil"></i></span>
                                                    <span class="btn btn-sm btn-danger delete_btn" href="#" data-del_tbl="schedules" data-del_id="<?php echo $record->id; ?>" title="Delete"><i class="fa fa-trash"></i></span>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

                    </div><!-- /.box-body -->

                </div><!-- /.box -->
            </div>
        </div>
    </section>

    <!-- add scheduled modal -->
    <div id="addSendToModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form action="<?php echo base_url() . $redirect_key; ?>doAddSchedule"  method="post">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Schedule</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label for="shd_types">Not Availability Of</label>
                            <?php $departments = array('Electricity', 'Water'); ?>
                            <select name="scheduled_type" class="scheduled_type form-control">
                                <?php
                                if (!empty($departments)) {
                                    foreach ($departments as $record) {
                                        ?>
                                        <option value="<?php echo $record; ?>"> <?php echo $record; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group form-inline">

                            <label for="from_date">From Date</label>
                            <input type="text" class="datepicker from_date form-control" name="from_date" required="">
                            <label for="from_date">From Time</label>
                            <input type="time" class="timepicker from_time form-control" name="from_time" placeholder="13:30 PM" required="">
                        </div>
                        <div class="form-group form-inline">

                            <label for="to_date">To Date</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="datepicker to_date form-control" name="to_date" required="">
                            <label for="from_date">To Time</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="time" class="timepicker to_time form-control" name="to_time" placeholder="13:30 PM" required="">
                        </div>
                        <div class="form-group"><label for="location">Enter Location</label>
                            <input type="text" class="form-control location" name="location" required="">
                        </div>
                        <div class="form-group">
                            <input type="button" name="fetch_lat_long" class="fetch_lat_long btn btn-primary" value="Fetch Coordinates">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="lat" id="resp_lat" placeholder="Lattitude" required="">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="longi" id="resp_lng"  placeholder="Longitude" required="">
                        </div>
                        <div class="form-group">
                            <label for="comments">Comments</label>
                            <input type="text" class="form-control" name="comments" >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">Create</button> &nbsp;
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- end add scheduled modal -->

    <!-- add scheduled modal -->
    <div id="updateScheduleModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form action="<?php echo base_url() . $redirect_key; ?>doUpdateSchedule" method="post">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update Schedule</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label for="scheduled_type">Not Availability Of</label>
                            <?php $departments = array('Electricity', 'Water'); ?>
                            <select name="scheduled_type" class="scheduled_type form-control">
                                <?php
                                if (!empty($departments)) {
                                    foreach ($departments as $record) {
                                        ?>
                                        <option value="<?php echo $record; ?>"> <?php echo $record; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group form-inline">
                            <label for="from_date col-md-3"> From: </label>&nbsp;&nbsp;&nbsp;
                            <label for="from_date">Date</label>
                            <input type="text" class="datepicker update_from_date form-control" name="from_date" required="">
                            <label for="from_date">Time</label>
                            <input type="time" class="timepicker update_from_time form-control" name="from_time" placeholder="13:30 PM" required="">
                        </div>
                        <div class="form-group form-inline">
                            <label for="from_date col-md-3"> To: </label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label for="to_date">Date</label>
                            <input type="text" class="datepicker update_to_date form-control" name="to_date" required="">
                            <label for="from_date">Time</label>
                            <input type="time" class="timepicker update_to_time form-control" name="to_time" placeholder="13:30 PM" required="">
                        </div>
                        <div class="form-group"><label for="location">Enter Location</label>
                            <input type="text" class="form-control update_location location" name="location" required="">
                        </div>
                        <div class="form-group">
                            <input type="button" name="fetch_lat_long" class="update_fetch_lat_long btn btn-primary" value="Fetch Coordinates">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control update_lat lat" name="lat" id="update_resp_lat" placeholder="Lattitude" required="">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control update_longi longi" name="longi" id="update_resp_lng"  placeholder="Longitude" required="">
                        </div>
                        <div class="form-group">
                            <label for="comments">Comments</label>
                            <input type="text" class="form-control update_comments" name="comments" >
                        </div>
                        <input type="hidden" id="shd_id" name="shd_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">Update</button> &nbsp;
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- end update scheduled modal -->


</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js" charset="utf-8"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />





<script type="text/javascript">
    jQuery(document).ready(function () {
//                                       if ($('.location-autocomplete').length) {
//                                           new TeleportAutocomplete({el: '.location-autocomplete', maxItems: 4});
//                                       }
//                                       $('.location-autocomplete').focus(function () {
//                                           $('.tp-ac__list').show();
//                                       });
//
//                                       $('.location-autocomplete').focusout(function () {
//                                           $('.tp-ac__list').hide();
//                                       });
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '0d',
            autoclose: true
        });

        $('.update_schedule').on('click', function () {
            var shd_id = $(this).data('shd_id');
            var location = $(this).data('location');
            var lat = $(this).data('lat');
            var longi = $(this).data('longi');
            var comments = $(this).data('comments');
            var from_date = $(this).data('from_date');
            var from_time = $(this).data('from_time');
            var to_time = $(this).data('to_time');
            var to_date = $(this).data('to_date');

            $('#updateScheduleModal .update_location').val(location);
            $('#updateScheduleModal .update_lat').val(lat);
            $('#updateScheduleModal .update_from_date').val(from_date);
            $('#updateScheduleModal .update_from_time').val(from_time);
            $('#updateScheduleModal .update_to_time').val(to_time);
            $('#updateScheduleModal .update_to_date').val(to_date);
            $('#updateScheduleModal .update_longi').val(longi);
            $('#updateScheduleModal .update_comments').val(comments);
            $('#updateScheduleModal #shd_id').val(shd_id);
            $('#updateScheduleModal').modal('show');
        });

        $('.fetch_lat_long').on('click', function () {
            var add = $('.location').val();
            $.ajax({
                type: 'GET',
                url: 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCawOL5FNXOZxJZmylb2O6fM_SWfSueLXQ&address=' + add,
                data: {},
                dataType: 'json',
                success: function (data)
                {
                    console.log(data);
                    console.log("Latitude: " + data.status);
                    console.log("Latitude: " + data.results[0].geometry.location.lat);
                    $('#resp_lat').val(data.results[0].geometry.location.lat);
                    console.log("Latitude: " + data.results[0].geometry.location.lng);
                    $('#resp_lng').val(data.results[0].geometry.location.lng);
                    $('.location').val(data.results[0].formatted_address);
                },
                error: function () {
                    alert('something bad happened');
                }
            });
        });
        $('.update_fetch_lat_long').on('click', function () {
            var add = $('.update_location').val();
            $.ajax({
                type: 'GET',
                url: 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCawOL5FNXOZxJZmylb2O6fM_SWfSueLXQ&address=' + add,
                data: {},
                dataType: 'json',
                success: function (data)
                {
                    console.log("Latitude: " + data.status);
                    console.log("Latitude: " + data.results[0].geometry.location.lat);
                    $('#update_resp_lat').val(data.results[0].geometry.location.lat);
                    console.log("Latitude: " + data.results[0].geometry.location.lng);
                    $('#update_resp_lng').val(data.results[0].geometry.location.lng);
                    $('.update_location').val(data.results[0].formatted_address);
                },
                error: function () {
                    alert('something bad happened');
                }
            });
        });

        var table = $('#example').DataTable({"order": [[0, 'desc']]});
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '-7d',
            autoclose: true
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


    });
</script>
