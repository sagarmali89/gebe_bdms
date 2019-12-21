<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Settings
            <small>Street Name, Region</small>
        </h1>
        <div class="col-md-3 pull-right right">
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
    </section>
    <section class="content">


        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#street_name_tab">Street Name</a></li>
            <li><a data-toggle="tab" href="#region_tab">Region</a></li>
            <li><a data-toggle="tab" href="#reason_tab">Reason</a></li>
        </ul>

        <div class="tab-content">
            <div id="street_name_tab" class="tab-pane fade in active">

                <div class="Street Name_Area"> 
                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <div class="form-group">
                                <span class="btn btn-primary add_street_name" data-toggle="modal" data-target="#addStreetNameModal"><i class="fa fa-plus"></i> Add New</span>
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
                                                <th>Street Name</th>
                                                <th>Region</th>
                                                <th>Created On</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($street_names)) {
                                                foreach ($street_names->result() as $record) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $record->street_name; ?></td>
                                                        <td><?php echo $record->region; ?></td>
                                                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                                        <td class="text-center">
                                                            <span class="btn btn-sm btn-info update_street_name"  title="Edit" data-street_name_id="<?php echo $record->id; ?>"><i class="fa fa-pencil"></i></span>
                                                            <span class="btn btn-sm btn-danger delete_btn" href="#" data-del_tbl="ims_street_name" data-del_id="<?php echo $record->id; ?>" title="Delete"><i class="fa fa-trash"></i></span>
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
                        <!-- add region modal -->
                        <!-- Modal -->
                        <div id="addStreetNameModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <form action="<?php echo base_url() . $redirect_key; ?>doAddStreetName" method="post">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Add New Street Name</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group"><label>Street Name</label>
                                                <input type="text" class="form-control" name="street_name" placeholder="Street Name" >
                                            </div>

                                            <div class="form-group"><label for="region">Region</label>
                                                <select name="region" class="region_dp form-control">
                                                    <?php
                                                    if (!empty($regions)) {
                                                        foreach ($regions->result() as $record) {
                                                            ?>
                                                            <option value="<?php echo $record->id; ?>"> <?php echo $record->region; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-default">Create</button> &nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- update region modal-->
                        <!-- Modal -->
                        <div id="updateStreet NameModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <form action="<?php echo base_url() . $redirect_key; ?>doUpdateStreet Name" method="post">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Add New Street Name</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group"><label>Street Name</label>
                                                <input type="text" class="form-control street_name_update" name="street_name" placeholder="New Street Name" >
                                            </div>

                                            <div class="form-group"><label for="region">Region</label>
                                                <select name="region" class="region_dp_update form-control">

                                                </select>
                                            </div>
                                            <input type="hidden" id="update_street_name_id" name="street_name_id" value="">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-info">Update</button> &nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div id="region_tab" class="tab-pane fade in">

                <div class="Region_Area"> 
                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <div class="form-group">
                                <span class="btn btn-primary" data-toggle="modal" data-target="#addRegionModal"><i class="fa fa-plus"></i> Add New</span>
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
                                                <th>Name</th>
                                                <th>Created On</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($regions)) {
                                                foreach ($regions->result() as $record) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $record->region; ?></td>
                                                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                                        <td class="text-center">
                                                            <span class="btn btn-sm btn-info update_region"  title="Edit" data-tech_id="<?php echo $record->id; ?>"><i class="fa fa-pencil"></i></span>
                                                            <span class="btn btn-sm btn-danger delete_btn" href="#" data-del_tbl="ims_region" data-del_id="<?php echo $record->id; ?>" title="Delete"><i class="fa fa-trash"></i></span>
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
                        <!-- add region modal -->
                        <!-- Modal -->
                        <div id="addRegionModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <form action="<?php echo base_url() . $redirect_key; ?>doAddRegion" method="post">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Add New Region</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group"><label>Region</label>
                                                <input type="text" class="form-control" name="region" placeholder="Region Name" >
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-default">Create</button> &nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- update region modal-->
                        <!-- Modal -->
                        <div id="updateRegionModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <form action="<?php echo base_url() . $redirect_key; ?>doUpdateRegion" method="post">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Update Region</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="modal-body">
                                                <div class="form-group"><label>Region</label>
                                                    <input type="text" class="form-control region_update" name="region" placeholder="Region Name" >
                                                </div>
                                            </div>
                                            <input type="hidden" name="resion_id" id="update_region_id" value="">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-default">Update</button> &nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div id="reason_tab" class="tab-pane fade in">

                <div class="Reason_Area"> 
                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <div class="form-group">
                                <span class="btn btn-primary" data-toggle="modal" data-target="#addReasonModal"><i class="fa fa-plus"></i>Add New</span>
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
                                                <th>Name</th>
                                                <th>Created On</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($reasons)) {
                                                foreach ($reasons->result() as $record) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $record->reason; ?></td>
                                                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                                        <td class="text-center">
                                                            <span class="btn btn-sm btn-info update_reason"  title="Edit" data-tech_id="<?php echo $record->id; ?>"><i class="fa fa-pencil"></i></span>
                                                            <span class="btn btn-sm btn-danger delete_btn" href="#" data-del_tbl="ims_reason" data-del_id="<?php echo $record->id; ?>" title="Delete"><i class="fa fa-trash"></i></span>
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
                        <!-- add region modal -->
                        <!-- Modal -->
                        <div id="addReasonModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <form action="<?php echo base_url() . $redirect_key; ?>doAddReason" method="post">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Add New Reasons</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group"><label>Reason</label>
                                                <input type="text" class="form-control" name="reason" placeholder="Reason" >
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-default">Create</button> &nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- update region modal-->
                        <!-- Modal -->
                        <div id="updateReasonModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <form action="<?php echo base_url() . $redirect_key; ?>doUpdateReason" method="post">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Update Reason</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="modal-body">
                                                <div class="form-group"><label>Reason</label>
                                                    <input type="text" class="form-control reason_update" name="reason" placeholder="Reason" >
                                                </div>
                                            </div>
                                            <input type="hidden" name="reason_id" id="update_reason_id" value="">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-info">Update</button> &nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        var table = $('#example').DataTable();


        $('.update_street_name').on('click', function () {
            var street_name_id = $(this).data('street_name_id');
            $.ajax({
                url: "<?php echo base_url() . $redirect_key . 'getRegionDp'; ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    street_name_id: street_name_id,
                },
                success: function (data) {
                    $('#updateStreet NameModal .region_dp_update').html(data.region_dp);
                }
            });
            $.ajax({
                url: "<?php echo base_url() . $redirect_key . 'getStreet NameDetails'; ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    street_name_id: street_name_id,
                },
                success: function (data) {
                    $('#updateStreet NameModal .street_name_update').val(data.street_name);
                    $('#updateStreet NameModal #update_street_name_id').val(data.id);
                    $('#updateStreet NameModal .region_dp_update').val(data.region_id);
                    $('#updateStreet NameModal').modal('show');
                }
            });
        });
        $('.update_region').on('click', function () {
            var region_id = $(this).data('region_id');
            $.ajax({
                url: "<?php echo base_url() . $redirect_key . 'getRegionDetails'; ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    region_id: region_id,
                },
                success: function (data) {
                    $('#updateRegionModal .region_update').val(data.region);
                    $('#updateRegionModal #update_region_id').val(data.id);
                    $('#updateRegionModal').modal('show');
                }
            });
        });
        $('.update_reason').on('click', function () {
            var region_id = $(this).data('region_id');
            $.ajax({
                url: "<?php echo base_url() . $redirect_key . 'getReasonDetails'; ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    region_id: region_id,
                },
                success: function (data) {
                    $('#updateReasonModal .reason_update').val(data.region);
                    $('#updateReasonModal #update_reason_id').val(data.id);
                    $('#updateReasonModal').modal('show');
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
