<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> Breakdown Manager
            <small><?php echo $dashboardTitle; ?></small>
        </h1>
    </section>
    <style>
        .width50{

        }
        .modal-dialog{
            width: 900px;
            height: 810px;
        }
        .modal-content{
            height: 100%;
        }
        /*        .mandatory{
                    font-weight: bold;
                    color: red;
                }
                .mandatory::after{
                    content: '*';
                }*/
    </style>
    <section class="content">

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $dashboardTitle; ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <?php
                        $edit_bd = 0;
                        if (isset($edit_breakdown) && $edit_breakdown > 0) {
                            $edit_bd = $edit_form;
                        }

                        if (!empty($breakdown_data)) {
                            $prev_data = $breakdown_data;
                        }
                        ?>
                        <form action="<?php echo base_url() . $redirect_key; ?>doAddBreakDown" method="post">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-body">
                                    <input type="hidden" name="edit_form" value="<?php echo $edit_bd; ?>">
                                    <!--                                    <div class="form-group col-md-6">
                                                                            <label class="control-label">Call ID</label>
                                                                            <input type="text" name="call_id" value="<?php // echo $breakdown_data->call_id;                ?>" class="form-control">
                                                                        </div>-->
                                    <div class="form-group col-md-6">
                                        <label class="control-label mandatory">Division</label>
                                        <?php
                                        $divisions = array('Water', 'Electrical', 'Both');
                                        ?>
                                        <select name="division" class="form-control ">
                                            <?php
                                            foreach ($divisions as $val) {
                                                $selected = '';
                                                if ($breakdown_data->division == $val) {
                                                    $selected = ' Selected';
                                                }
                                                echo '<option value="' . $val . '" ' . $selected . '>' . $val . '</option>';
                                            }
                                            ?>
                                        </select> 
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Status</label> 
                                        <?php $status_types = array('Reported', 'Completed', 'On-Going', 'On Hold'); ?>
                                        <select name="status" class="form-control">
                                            <?php
                                            foreach ($status_types as $val) {
                                                $selected = '';
                                                if ($breakdown_data->status == $val) {
                                                    $selected = ' Selected';
                                                }
                                                echo '<option value="' . $val . '" ' . $selected . '>' . $val . '</option>';
                                            }
                                            ?>
                                        </select> 
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Connection Type</label> 
                                        <?php $connection_types = array('Business', 'Residential'); ?>
                                        <select name="connection_type" class="form-control">
                                            <?php
                                            foreach ($connection_types as $val) {
                                                $selected = '';
                                                if ($breakdown_data->connection_type == $val) {
                                                    $selected = ' Selected';
                                                }
                                                echo '<option value="' . $val . '" ' . $selected . '>' . $val . '</option>';
                                            }
                                            ?>
                                        </select> 
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label mandatory">Caller Region</label>
                                        <select name="region_id" class="form-control mandatory_input" data-vv-id="3" aria-required="true" aria-invalid="false" required="">
                                            <?php
                                            foreach ($regions as $k => $val) {
                                                $selected = '';
                                                if ($breakdown_data->region_id == $k) {
                                                    $selected = ' Selected';
                                                }
                                                echo '<option value="' . $k . '"' . $selected . '>' . $val . '</option>';
                                            }
                                            ?>
                                        </select> 
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Caller Name</label> 
                                        <input name="caller_name" type="text" value="<?php echo $breakdown_data->caller_name; ?>" class="form-control" data-vv-id="2" aria-required="false" aria-invalid="false">
                                        <!---->
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Emails ID</label> 
                                        <input name="email_address" type="text" value="<?php echo $breakdown_data->email_address; ?>" class="form-control" data-vv-id="2" aria-required="false" aria-invalid="false">
                                        <!---->
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label mandatory">Street Name</label> 

                                        <select name="street_id" class="form-control mandatory_input" data-vv-id="4" aria-required="true" aria-invalid="false" required="">
                                            <?php
                                            foreach ($street_names as $k => $val) {
                                                $selected = '';
                                                if ($breakdown_data->street_id == $k) {
                                                    $selected = ' Selected';
                                                }
                                                echo '<option value="' . $k . '" ' . $selected . '>' . $val . '</option>';
                                            }
                                            ?>

                                        </select>
                                    </div>


                                    <div class="form-group col-md-6">
                                        <label class="control-label">House Number</label> 
                                        <input name="house_number" type="text" data-vv-rules="max:40" value="<?php echo $breakdown_data->house_number; ?>" class="form-control" data-vv-id="5" aria-required="false" aria-invalid="false"> 

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Cellular/Telephone</label> 
                                        <input name="cellular" type="text" data-vv-rules="max:40" value="<?php echo $breakdown_data->cellular; ?>" class="form-control" data-vv-id="11" aria-required="false" aria-invalid="false">
                                        <!---->
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Direction Note</label> 
                                        <input name="direction_note" type="text" data-vv-rules="max:40" value="<?php echo $breakdown_data->direction_note; ?>" class="form-control" data-vv-id="9" aria-required="false" aria-invalid="false"> 
                                        <!---->
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Meter No</label> 
                                        <input name="meter_no" type="text" data-vv-rules="max:40" value="<?php echo $breakdown_data->meter_no; ?>" class="form-control" data-vv-id="10" aria-required="false" aria-invalid="false">
                                        <!---->
                                    </div>


                                    <div class="form-group col-md-6">
                                        <label class="control-label mandatory">Please Select Reason</label>
                                        <select name="reason_id"  class="form-control mandatory_input" data-vv-id="7" aria-required="true" aria-invalid="false" required="">
                                            <?php
                                            foreach ($reasons as $k => $val) {
                                                $selected = '';
                                                if ($breakdown_data->reason_id == $k) {
                                                    $selected = ' Selected';
                                                }
                                                echo '<option value="' . $k . '">' . $val . '</option>';
                                            }
                                            ?>
                                        </select> 
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label mandatory">Technician</label> 
                                        <select name="technician_id" class="form-control mandatory_input" data-vv-id="6" aria-required="true" aria-invalid="false" required="">
                                            <?php
                                            foreach ($technicians as $k => $val) {
                                                $selected = '';
                                                if ($breakdown_data->technician_id == $k) {
                                                    $selected = ' Selected';
                                                }
                                                echo '<option value="' . $k . '" ' . $selected . '>' . $val . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Reported Date To Technician</label>
                                        <input name="reported_date_to_technician" value="<?php echo $breakdown_data->reported_date_to_technician; ?>" placeholder="Reported Date to Technician" type="text" class="form-control datepicker" autocomplete="off"> 
                                        <!---->
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Reported Time To Technician</label> 
                                        <input name="reported_time_to_technician" value="<?php echo date('H:i', strtotime($breakdown_data->reported_time_to_technician)); ?>" value="<?php echo $breakdown_data->reported_time_to_technician; ?>" type="time" placeholder="e.g. 15:30" placeholder="Reported Time to Technician" class="form-control timepicker"  autocomplete="off"> 
                                        <!---->
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Problem</label>
                                        <input name="problem" type="text" data-vv-rules="max:100" value="<?php echo $breakdown_data->problem; ?>" class="form-control" data-vv-id="13" aria-required="false" aria-invalid="false">
                                        <!---->
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Job Completed Date</label>
                                        <?php if (isset($breakdown_data->job_completed_date) && $breakdown_data->job_completed_date > '0000-00-00') {
                                            ?>
                                            <input name="job_completed_date" value="<?php echo date('Y-m-d', strtotime($breakdown_data->job_completed_date)); ?>" placeholder="Job Completed Date" type="text" class="form-control datepicker" autocomplete="off"> 
                                        <?php } else {
                                            ?>
                                            <input name="job_completed_date" placeholder="Job Completed Date" type="text" class="form-control datepicker" autocomplete="off"> 
                                        <?php } ?>
                                        <!---->
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Job Completed Time</label> 
                                        <?php if (isset($breakdown_data->job_completed_time) && $breakdown_data->job_completed_time > '00:00:00') {
                                            ?>
                                            <input name="job_completed_time" value="<?php echo date('h:i', strtotime($breakdown_data->job_completed_time)); ?>" placeholder="Job Completed Time" type="text" class="form-control timepicker" autocomplete="off"> 
                                        <?php } else {
                                            ?>
                                            <input name="job_completed_time" placeholder="Job Completed Time" type="time" placeholder="e.g. 15:30" class="form-control timepicker"  autocomplete="off"> 
                                        <?php } ?>


                                    </div>

                                    <div class="col-sm-12 col-md-12 modal-footer">
                                        <button type="submit" class="btn btn-primary submit_btn">
                                            <?php
                                            if ($edit_bd) {
                                                echo 'Update';
                                            } else {
                                                echo 'Create';
                                            }
                                            ?>
                                        </button> 
                                        <button type="button" onclick="window.history.go(-1); return false;"  class="btn btn-secondary">Close</button>
                                    </div>

                                </div>
                            </div>
                        </form>
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



<script type="text/javascript">
                                            jQuery(document).ready(function () {
                                                var table = $('#example').DataTable();
                                                $('.datepicker').datepicker({
                                                    format: 'yyyy-mm-dd',
                                                    startDate: '-7d',
                                                    autoclose: true
                                                });
//        $('.timepicker').timepicker();
//        $('#timepicker1').timepicker();
//
//        $('#timepicker1').on('changeTime.timepicker', function (e) {
//           // $('#timeDisplay').text(e.time.value);
//        });

//        $('.mandatory_input').on('change', function () {
//            var found = 0;
//            // console.log('change');
//            $.each($('.mandatory_input'), function () {
//                var x = $(this).val();
//                console.log(x);
//                if (x == '' || x === undefined || x == -1) {
//                    $('.submit_btn').attr('disabled', 'true');
//                    found = 1;
//
//                }
//            });
//            if (found == 0) {
//                $('.submit_btn').removeAttr('disabled');
//            }
//        });
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
                                            });
</script>
