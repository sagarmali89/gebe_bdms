<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Break Down
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
        .modal-content{
            height: 100%;
        }
    </style>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="col-md-3 pull-left left">
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
                    <a class="btn btn-primary" href="<?php echo base_url() . $redirect_key; ?>breakDown"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Breakdowns</h3>
                        <div class="box-tools">

                        </div>
                    </div><!-- /.box-header -->

                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Division</th>
                                    <th>Region</th>
                                    <th>Caller</th>
                                    <th>Reason</th>
                                    <th>Street Name</th> <!-- house no, street_name, region -->
                                    <th>Technician</th>
                                    <!--<th>Status</th>-->
                                    <th>Completed Date</th>
                                    <th>Created Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($breakdowns)) {
                                    foreach ($breakdowns->result() as $record) {
                                        ?>
                                        <tr>
                                            <td><?php echo $record->id; ?></td>
                                            <td><?php echo $record->division; ?></td>
                                            <td><?php echo $regions[$record->region_id]; ?></td>
                                            <td><?php echo $record->caller_name; ?></td>
                                            <td><?php echo $reasons[$record->reason_id]; ?></td>
                                            <td><?php echo $street_names[$record->street_id]; ?></td>
                                            <td><?php echo $technicians[$record->technician_id]; ?></td>
                                            <!--<td><?php // echo $record->status_id;       ?></td>-->
                                            <td><?php
                                                $jb_dt = $record->job_completed_date;
                                                if ($jb_dt != '' && $jb_dt > '0000-00-00') {
                                                    echo date("Y-m-d", strtotime($jb_dt));
                                                } else {
                                                    echo '';
                                                }
                                                ?></td>
                                            <td><?php echo date("Y-m-d", strtotime($record->createdDtm)) ?></td>
                                            <td class="text-center">
                                                <a class="btn btn-sm btn-info edit_tech" href="<?php echo base_url() . $redirect_key; ?>breakDown/<?php echo $record->id; ?>"  title="Edit" ><i class="fa fa-pencil"></i></a>
                                                <span class="btn btn-sm btn-danger delete_btn" href="#" data-del_tbl="ims_breakdowns" data-del_id="<?php echo $record->id; ?>" title="Delete"><i class="fa fa-trash"></i></span>
                                            </td>
                                        </tr>
                                        <?php
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
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js" charset="utf-8"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />



<script type="text/javascript">
    jQuery(document).ready(function () {
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
