<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Absence Types Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <span class="btn btn-primary" data-toggle="modal" data-target="#addAbsenceModal"><i class="fa fa-plus"></i> Add New</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Absence Types List</h3>
                        <div class="box-tools">

                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Color</th>
                                    <th>Description</th>
                                    <th>Created On</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($absence_types)) {
                                    foreach ($absence_types as $record) {
                                        ?>
                                        <tr>
                                            <td><?php echo $record->type ?></td>
                                            <td><?php echo $record->color ?></td>
                                            <td><?php echo $record->type_desc ?></td>
                                            <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                            <td class="text-center">
                                                <!--<a class="btn btn-sm btn-primary" href="<?php // base_url() . 'login-history/' . $record->id;                                  ?>" title="Login history"><i class="fa fa-history"></i></a> |--> 
                                                <span class="btn btn-sm btn-info edit_type"  title="Edit" data-type_id="<?php echo $record->id; ?>"><i class="fa fa-pencil"></i></span>
                                                <span class="btn btn-sm btn-danger delete_btn" href="#" data-del_tbl="vac_absence_types" data-del_id="<?php echo $record->id; ?>" title="Delete"><i class="fa fa-trash"></i></span>
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

        <?php
        $colorArray = ['#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6',
            '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
            '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A',
            '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC',
            '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC',
            '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399',
            '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680',
            '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933',
            '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3',
            '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF'];
        ?>
        <!-- add absence type modal -->
        <!-- Modal -->
        <div id="addAbsenceModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <form action="<?php echo base_url() . $redirect_key; ?>doAddAbsenceType" method="post">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Add New Absence Types</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group"><label>Absence Type</label>
                                <input type="text" class="form-control" name="absence_type" placeholder="Display Name" >
                            </div>
                            <div class="form-group"><label>Color</label>
                                <select class="form-control" name="color">
                                    <?php foreach ($colorArray as $color) { ?>
                                        <option value="<?php echo $color; ?>" style="background-color: <?php echo $color; ?>"><?php echo $color; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group"><label>Description</label>
                                <input type="text" class="form-control description" name="type_desc" placeholder="Description" >
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
        <!-- update absence type modal-->
        <!-- Modal -->
        <div id="updateAbsenceModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <form action="<?php echo base_url() . $redirect_key; ?>doUpdateAbsenceType" method="post">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Update Absence Type</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group"><label>Absence Type</label>
                                <input type="text" class="form-control absence_type" name="absence_type" placeholder="Display Name" >
                            </div>

                            <div class="form-group"><label>Color</label>
                                <select class="form-control color" name="color">
                                    <?php foreach ($colorArray as $color) { ?>
                                        <option value="<?php echo $color; ?>" style="background-color: <?php echo $color; ?>"><?php echo $color; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group"><label>Description</label>
                                <input type="text" class="form-control type_desc" name="type_desc" placeholder="Description" >
                            </div>
                            <input type="hidden" name="type_id" id="absence_type_id" value="">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-default">Update</button> &nbsp;
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        var table = $('#example').DataTable();
        $('.edit_type').on('click', function () {
            var type_id = $(this).data('type_id');

            $.ajax({
                url: "<?php echo base_url() . $redirect_key . 'getTypeDetails'; ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    type_id: type_id,
                },
                success: function (data) {
                    $('#updateAbsenceModal .absence_type').val(data.type);
                    $('#updateAbsenceModal .color').val(data.color);
                    $('#updateAbsenceModal .type_desc').val(data.type_desc);
                    $('#updateAbsenceModal #absence_type_id').val(data.id);

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
