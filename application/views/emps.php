<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Employee Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url() . $redirect_key; ?>addNewEmp"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Enployee List</h3>
                        <div class="box-tools">

                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Emp ID</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Created On</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($empRecords)) {
                                    foreach ($empRecords as $record) {
                                        ?>
                                        <tr>
                                            <td><?php echo $record->name ?></td>
                                            <td><?php echo $record->emp_id ?></td>
                                            <td><?php echo $record->email ?></td>
                                            <td><?php echo $record->mobile ?></td>
                                            <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                            <td class="text-center">
                                                <!--<a class="btn btn-sm btn-primary" href="<?php // base_url() . 'login-history/' . $record->id;       ?>" title="Login history"><i class="fa fa-history"></i></a> |--> 
                                                <a class="btn btn-sm btn-info" href="<?php echo base_url() . $redirect_key . 'editOldEmployee/' . $record->id; ?>" title="Edit"><i class="fa fa-pencil"></i></a>
                                                <a class="btn btn-sm btn-danger delete_btn" href="#" data-del_tbl="vac_emp" data-del_id="<?php echo $record->id; ?>" title="Delete"><i class="fa fa-trash"></i></a>
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
<script type="text/javascript">
    jQuery(document).ready(function () {
        var table = $('#example').DataTable();
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
