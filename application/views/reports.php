<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="<?php echo base_url(); ?>assets/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<!--<link href="../assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />-->
<!-- END PAGE LEVEL PLUGINS -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> Reports
            <small>View, Download</small>
        </h1>
        <div class="col-md-3 pull-right right">
            <?php
            $error = $this->session->flashdata('error');
            if ($error) {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
            <?php } ?>
            <?php
            $success = $this->session->flashdata('success');
            if ($success) {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php } ?>
        </div>
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
    </style>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <form action="" method="post">

                    <div class="form-group col-md-2">
                        <label>Dispatcher</label>
                        <select class="form-control" name="report_dispatcher">
                            <option value="0">Any</option>
                            <?php
                            foreach ($dispatchers as $id => $dispatcher) {
                                $selected = ' ';
                                if ($id == $report_dispatcher) {
                                    $selected = ' Selected';
                                }
                                echo '<option value="' . $id . '" ' . $selected . '>' . $dispatcher . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Problem</label>
                        <select class="form-control" name="report_problem">
                            <option>Any</option>
                            <?php
                            foreach ($problems as $problem) {
                                $selected = ' ';
                                if ($problem == $report_problem) {
                                    $selected = ' Selected';
                                }
                                echo '<option value="' . $problem . '" ' . $selected . '>' . $problem . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                         <label>Report Type</label>
                        <select class="form-control report_type" name="report_type">
                            <?php
                            foreach ($report_types as $report) {
                                $selected = ' ';
                                if ($report == $report_type) {
                                    $selected = ' Selected';
                                }
                                echo '<option value="' . $report . '" ' . $selected . '>' . $report . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2 second_filter">
                        <?php if (isset($report_technician) && $report_technician > 0) { ?>
                            <select class="form-control" name="report_technician">
                                <?php
                                foreach ($technicians as $id => $technician) {
                                    $selected = ' ';
                                    if ($id == $report_technician) {
                                        $selected = ' Selected';
                                    }
                                    echo '<option value="' . $id . '" ' . $selected . '>' . $technician . '</option>';
                                }
                                ?>
                            </select>
                            <?php
                        } else if (isset($report_division) && $report_division != '') {
                            ?>
                            <select class="form-control" name="report_division">
                                <?php
                                foreach ($divisions as $division) {
                                    $selected = ' ';
                                    if ($division == $report_division) {
                                        $selected = ' Selected';
                                    }
                                    echo '<option value="' . $division . '" ' . $selected . '>' . $division . '</option>';
                                }
                                ?>
                            </select>
                            <?php
                        } else if (isset($report_region) && $report_region > 0) {
                            ?>
                            <select class="form-control" name="report_region">
                                <?php
                                foreach ($regions as $id => $region) {
                                    $selected = ' ';
                                    if ($id == $report_region) {
                                        $selected = ' Selected';
                                    }
                                    echo '<option value="' . $id . '" ' . $selected . '>' . $region . '</option>';
                                }
                                ?>
                            </select>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="date_range">Date Range:</label>
                    </div>
                    <div class="form-group col-md-1">
                        <input name="from_date" value="<?php echo $from_date; ?>" placeholder="From Date" type="text" class="form-control datepicker" autocomplete="off"> 
                    </div>
                    <div class="form-group col-md-1">
                        <input name="to_date" value="<?php echo $to_date; ?>" placeholder="To Date" type="text" class="form-control datepicker" autocomplete="off"> 
                    </div>
                    <div class="form-group col-md-1">
                        <input type="submit" value="Generate Report" class="btn btn-success" name="generate_report">
                    </div>
                </form>
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
                    <div class="portlet light bordered">
                        <div class="box-body table-responsive no-padding">
                            <div class="portlet-title">
                                <div class="caption font-dark">
                                    <i class="icon-settings font-dark"></i>
                                    <span class="caption-subject bold uppercase"></span>
                                </div>
                                <div class="tools"> </div>
                            </div>

                            <div class="portlet-body">
                                <?php if (isset($calls_by_region) && count($calls_by_region) > 0) { ?>
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                <th>Region</th>
                                                <?php
                                                foreach ($divisions as $divi) {
                                                    echo '<th>' . $divi . '</th>';
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($regions as $id => $region) { ?>
                                                <tr>
                                                    <td><?php echo $region; ?></td>
                                                    <td><?php echo $calls_by_region[$id][$divisions[0]]; ?></td>
                                                    <td><?php echo $calls_by_region[$id][$divisions[1]]; ?></td>
                                                    <td><?php echo $calls_by_region[$id][$divisions[2]]; ?></td>
                                                </tr> 
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                <?php } else { ?>
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                <th>Division</th>
                                                <th>Region</th>
                                                <th>Caller</th>
                                                <th>Reason</th>
                                                <th>Street Name</th> <!-- house no, street_name, region -->
                                                <th>Technician</th>
                                                <th>Entry Created By</th>
                                                <th>Reported Date</th>
                                                <th>Reported Time</th>
                                                <th>Problem</th>
                                                <th>Status</th>
                                                <th>Created On</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($reportData)) {
                                                foreach ($reportData->result() as $record) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $record->division; ?></td>
                                                        <td><?php echo $regions[$record->region_id]; ?></td>
                                                        <td><?php echo $record->caller_name; ?></td>
                                                        <td><?php echo $reasons[$record->reason_id]; ?></td>
                                                        <td><?php echo $street_names[$record->street_id]; ?></td>
                                                        <td><?php echo $technicians[$record->technician_id]; ?></td>
                                                        <td><?php echo $users[$record->createdBy]; ?></td>
                                                        <td><?php echo date("Y-m-d", strtotime($record->reported_date_to_technician)) ?></td>
                                                        <td><?php echo date("h:i", strtotime($record->reported_time_to_technician)) ?></td>
                                                        <td><?php echo $record->problem; ?></td>
                                                        <td><?php echo $record->status; ?></td>
                                                        <td><?php echo date("Y-m-d", strtotime($record->createdDtm)); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                            </div>
                        </div><!-- /.box-body -->
                    </div>
                </div><!-- /.box -->
            </div>
        </div>

    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js" charset="utf-8"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />



<script type="text/javascript">
    jQuery(document).ready(function () {
        var table = $('#example').DataTable();
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
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

        $('.report_type').on('change', function () {
            var key = $(this).val();
            $.ajax({
                url: "<?php echo base_url() . $redirect_key . 'getReportsDropDown'; ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    key: key,
                },
                success: function (data) {
                    $('.second_filter').html();
                    $('.second_filter').html(data.drop_down);
                }
            });
        });


    });
</script>
<!--<script src="../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>-->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url(); ?>assets/datatables/datatable.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!--<script src="../assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>-->
<!-- END PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url(); ?>assets/datatables/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/datatables/table-datatables-buttons.min.js" type="text/javascript"></script>
