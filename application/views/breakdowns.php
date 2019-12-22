<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-thumb-tack"></i> Breakdowns
            <small>View, Edit, Delete</small>
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

                    <div class="box-body">
                        <table class="table table-hover" id="breakdown_table">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Division</th>
                                    <th>Region</th>
                                    <th>Caller</th>
                                    <th>Street name</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                   <!-- <th>Street Name</th> -->
                                    <!-- house no, street_name, region -->
                                    <th>Technician</th>
                                    <th>Completed Date</th>
                                    <th>Created Date</th>
                                     <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                               /* if (!empty($breakdowns)) {
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
                                */

                                ?>
                            </tbody>
                        </table>

                    </div><!-- /.box-body -->

                </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
<link href='https://cdn.datatables.net/fixedcolumns/3.3.0/css/fixedColumns.dataTables.min.css' rel='stylesheet' type='text/css'>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js" charset="utf-8"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>



<script type="text/javascript">
    jQuery(document).ready(function () {
        var table = $('#example').DataTable({"order": [[0, 'desc']]});
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '-7d',
            autoclose: true
        });
        var technicians = <?php echo json_encode($technicians); ?>;
        var reasons = <?php echo json_encode($reasons); ?>;
        var regions = <?php echo json_encode($regions); ?>;
        var street_name = <?php echo json_encode($street_names); ?>;
//console.log(street_name);
//$.fn.dataTable.ext.errMode = 'none';
        $('#breakdown_table').DataTable({
            'processing': true,
            'serverSide': true,
            "searching": true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?php echo base_url('index.php/user/getBreakdownRecordsForDataTable') ?>'
            },
          // scrollY: "300px",
         //   scrollX: false,
            scrollCollapse: false,
            'columns': [
                {data: 'id'},
                {data: 'division'},
               // {data: 'region_id'},
                {"mRender": function ( data, type, row ) {
                return regions[row.region_id];
                        }},
                {data: 'caller_name'},
               // {data: 'reason_id'},
               {data: 'street_name'},
               {data: 'reason_name'},
               {data: 'status'},
             /*   {"mRender": function ( data, type, row ) {
                return reasons[row.reason_id];
                        }}, */
                     /*  {"mRender": function ( data, type, row ) {
                return street_name[row.street_id];
                        }},*/
                     /*   {"mRender": function ( data, type, row ) {
                return technicians[row.technician_id];
                        }}, */
                {data: 'technician_name'},   
              //  {data: 'technician_id'},
                {data: 'job_completed_date'},
                {data: 'createdDtm'},
                {"mRender": function ( data, type, row ) {
                        return '<a class="btn btn-sm btn-info edit_tech" href="breakDown/'+row.id+'"  title="Edit" ><i class="fa fa-pencil"></i></a><span class="btn btn-sm btn-danger delete_btn" href="#" data-del_tbl="ims_breakdowns" data-del_id="'+row.id+'" title="Delete"><i class="fa fa-trash"></i></span>';
                        }
                }
                        // {   data: ""    }
            ],
            // "columnDefs": [ {
            //     "targets": -1,
            //     "data": null,
            //     "sortable": false,
            //     "defaultContent": "<button>Click!</button>"
            // }]
        });
       // $.fn.dataTable.ext.errMode = 'none';

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
