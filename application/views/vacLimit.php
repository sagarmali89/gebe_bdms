<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user"></i> Vacation Limit Yearly
            <small>Add /Update</small>
        </h1>
    </section>
    <style>
        .absent_entry{
            width: 50px !important;
            padding: 0px !important;
            font-size: 26px;
            font-weight: bolder;
            text-align: center;
        }
        .absent_entry{
            border: none;
        }
        .filters{
            font-size: 28px;
            font-weight: bolder;
            height: 50px !important;
            border: none;
        }
        .filter_row{
            padding: 1%;
        }
        #example_wrapper{
            padding: 1%;
        }
        th{
            text-align: center;
        }
    </style>
    <section class="content">

        <div class="row">
            <div class="col-xs-12">


                <div class="box">
                    <div class="row filter_row">
                        <div class="col-xs-3">
                            <select class="form-control year_dp filters">
                                <?php
                                $cur_year = date('Y');

                                if (isset($_GET['y'])) {
                                    $cur_year = $_GET['y'];
                                }
                                $sel = '';
                                for ($i = 2015; $i < 2030; $i++) {
                                    $sel = '';
                                    if ($i == $cur_year) {
                                        $sel = ' Selected';
                                    }
                                    ?>
                                    <option <?php echo $i; ?> <?php echo $sel; ?> ><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>


                    </div>
                    <div class="box-header">
                        <h3 class="box-title"></h3>
                        <div class="box-tools">

                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-striped table-bordered table-hover" style="width:100%" id="example">

                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>No of Vacations(days)</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($employees)) {
                                    foreach ($employees as $emp) {
                                        ?>
                                        <tr>
                                            <td><?php echo $emp->name; ?></td>
                                            <?php
                                            $whereInfo = array('emp_id' => $emp->id, 'year' => $cur_year);
                                            $this->db->select('*');
                                            $this->db->from('vac_empvac_limit');
                                            $this->db->where($whereInfo);
                                            $emp_vac_row = $this->db->get();
                                            $vacation_assigned = 0;
                                            //  echo $this->db->last_query(); die();
                                            if ($emp_vac_row && $emp_vac_row->num_rows()) {
                                                $ab_row = $emp_vac_row->row();
                                                if ($ab_row->id > 0) {
                                                    $vacation_assigned = $ab_row->vacation_assigned;
                                                }
                                                ?>
                                                <?php
                                            }
                                            ?>
                                            <td data-search="<?php echo $vacation_assigned; ?>" data-order="<?php echo $vacation_assigned; ?>">
                                                <input type="number" class="form-control vacation_entry" value="<?php echo $vacation_assigned; ?>" data-curr_val="<?php echo $vacation_assigned; ?>" data-emp_id="<?php echo $emp->id; ?>" data-year="<?php echo $cur_year; ?>" min="0" max="365" >
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
<script>
    $(document).ready(function () {
//        $('#example').DataTable();

        // DataTable
        var table = $('#example').DataTable();

        $('.year_dp').on('change', function () {
            var y = $('.year_dp').val();
            window.location.href = '?y=' + y;
        });
        $('.vacation_entry').on('blur', function () {
            // console.log('called');
            var prev_val = $.trim($(this).data('curr_val'));
            var new_val = $.trim($(this).val());
            var day = $(this).data('day');
            var year = $(this).data('year');
            var emp_id = $(this).data('emp_id');
            var this_ele = $(this);

            if (prev_val !== new_val) {
                $.ajax({
                    url: "<?php echo base_url() . $redirect_key . 'updateEmpVacLimit'; ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        day: day,
                        new_val: new_val,
                        year: year,
                        emp_id: emp_id
                    },
                    success: function (data) {
                        if (data.count > 0) {
                            this_ele.data('curr_val', new_val);
                            //  alert('Vacation Count Updated!');
                            window.location.reload();
                        } else {
                            alert('Unable to update, please try again later!');
                        }
                    }
                });
            } else {

            }
        });


        $("input").keydown(function () {
            // Save old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 365 && parseInt($(this).val()) >= 0))
                $(this).data("old", $(this).val());
        });
        $("input").keyup(function () {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 365 && parseInt($(this).val()) >= 0))
                ;
            else
                $(this).val($(this).data("old"));
        });

    });
</script>
