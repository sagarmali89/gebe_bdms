<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Technicians
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
                        <h3 class="box-title">Technician List</h3>
                        <div class="box-tools">

                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Ability</th>
                                    <!--<th>Description</th>-->
                                    <th>Created On</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($technicians)) {
                                    foreach ($technicians as $record) {
                                        ?>
                                        <tr>
                                            <td><?php echo $record->name ?></td>
                                            <td><?php echo $record->ability ?></td>
                                            <!--<td><?php // echo $record->type_desc       ?></td>-->
                                            <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                            <td class="text-center">
                                                <span class="btn btn-sm btn-info edit_tech"  title="Edit" data-tech_id="<?php echo $record->id; ?>"><i class="fa fa-pencil"></i></span>
                                                <span class="btn btn-sm btn-danger delete_btn" href="#" data-del_tbl="ims_technicians" data-del_id="<?php echo $record->id; ?>" title="Delete"><i class="fa fa-trash"></i></span>
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


        <!-- add absence type modal -->
        <!-- Modal -->
        <div id="addAbsenceModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <form action="<?php echo base_url() . $redirect_key; ?>doAddTechnician" method="post">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Add New Technician</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group"><label>Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Technician Name" >
                            </div>

                            <div class="form-group"><label for="ability">Ability</label>
                                <input type="text" class="form-control ability" name="ability" placeholder="Ability" >
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
                <form action="<?php echo base_url() . $redirect_key; ?>doUpdateTechnician" method="post">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Update Absence Type</h4>
                        </div>
                        <div class="modal-body">
                            <div class="modal-body">
                                <div class="form-group"><label>Name</label>
                                    <input type="text" class="form-control name_update" name="name" placeholder="Technician Name" >
                                </div>

                                <div class="form-group"><label for="ability">Ability</label>
                                    <input type="text" class="form-control ability_update" name="ability" placeholder="Ability" >
                                </div>
                            </div>
                            <input type="hidden" name="tech_id" id="technician_id" value="">
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
