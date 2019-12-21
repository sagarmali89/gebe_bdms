<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user"></i> Vacation Reports
            <small>Yearly/Monthly</small>
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

                        <div class="col-xs-3">
                            <select class="form-control month_dp filters">
                                <option value="1">All Months</option>
                                <?php
                                $cur_month = date('M');
                                if (isset($_GET['m'])) {
                                    $cur_month = $_GET['m'];
                                }


                                $months = array(
                                    'January',
                                    'February',
                                    'March',
                                    'April',
                                    'May',
                                    'June',
                                    'July ',
                                    'August',
                                    'September',
                                    'October',
                                    'November',
                                    'December',
                                );
                                $sel = '';
                                foreach ($months as $month) {
                                    $month = date('M', strtotime($month));
                                    $sel = '';
                                    if ($cur_month == $month) {
                                        $sel = ' Selected';
                                    }
                                    ?>
                                    <option value="<?php echo $month; ?>"  <?php echo $sel; ?>><?php echo $month; ?></option>
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
                                    <th>No of Vacations Assigned(days)</th>
                                    <th>No of Vacations Used(days)</th>
                                    <th>Remaining Vacations(days)</th>

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
                                            $this->db->select('id,vacation_assigned');
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
                                                <?php echo $vacation_assigned; ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($cur_month == 1) {
                                                    $whereInfo = array('emp_id' => $emp->id, 'year' => $cur_year);
                                                } else {
                                                    $whereInfo = array('emp_id' => $emp->id, 'year' => $cur_year, 'month' => $cur_month);
                                                }
                                                $this->db->select('absence_type,absence_count');
                                                $this->db->from('vac_emp_vacation');
                                                $this->db->where($whereInfo);
                                                $emp_vac_row = $this->db->get();
                                                $vacation_used = 0;
                                                //  echo $this->db->last_query(); die();
                                                if ($emp_vac_row && $emp_vac_row->num_rows()) {
                                                    $rows = $emp_vac_row->result();
                                                    foreach ($rows as $ro) {
                                                        if ($ro->absence_type == 'v' || $ro->absence_type == 'V') {
                                                            $vacation_used = $vacation_used + 1;
                                                        }
                                                        if ($ro->absence_type > -1) {
                                                            $vacation_used = $vacation_used + $ro->absence_count;
                                                        }
                                                    }
                                                    ?>
                                                    <?php
                                                }
                                                echo $vacation_used;
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $vacation_assigned - $vacation_used; ?>
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

        $('.month_dp').on('change', function () {
            var m = $('.month_dp').val();
            var y = $('.year_dp').val();
            window.location.href = '?m=' + m + '&y=' + y;
        });
        $('.year_dp').on('change', function () {
            var m = $('.month_dp').val();
            var y = $('.year_dp').val();
            window.location.href = '?m=' + m + '&y=' + y;
        });


    });
</script>
