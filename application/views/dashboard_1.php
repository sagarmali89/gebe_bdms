<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user"></i> Employee Absence Schedule
            <small>Add/Update</small>
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
                                    <?php
                                    $maxDays = date('t');
                                    for ($i = 1; $i <= $maxDays; $i++) {
                                        ?> 
                                        <th>
                                            <?php
                                            //  echo $i;
                                            $time = mktime(12, 0, 0, date('m', strtotime($cur_month)), $i, $cur_year);
                                            if (date('m', $time) == date('m', strtotime($cur_month)))
                                                echo date('D', $time);
                                            echo '<br>';
                                            echo $i;
                                            ?>
                                        </th>
                                        <?php
                                    }
                                    ?>
                                    <th>Total Days</th>
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
                                            $emp_month_tot = 0;
                                            for ($i = 1; $i <= $maxDays; $i++) {

                                                $whereInfo = array('emp_id' => $emp->id, 'day' => $i, 'month' => $cur_month, 'year' => $cur_year);
                                                $this->db->select('id,absence_type,absence_count,absence_color');
                                                $this->db->from('vac_emp_vacation');
                                                $this->db->where($whereInfo);
                                                $emp_vac_row = $this->db->get();
                                                $absence_color = '';
                                                $ab_curr_val = '';
                                                //  echo $this->db->last_query(); die();
                                                if ($emp_vac_row && $emp_vac_row->num_rows()) {
                                                    $ab_row = $emp_vac_row->row();
                                                    if ($ab_row->id > 0) {
                                                        $absence_count = $ab_row->absence_count;
                                                        if ($absence_count && $absence_count > 0) {
                                                            $ab_curr_val = $absence_count;
                                                            $emp_month_tot = $emp_month_tot + $absence_count;
                                                        } else if ($ab_row->absence_type != '') {
                                                            $absence_type = $ab_curr_val = $ab_row->absence_type;

                                                            $this->db->select('color');
                                                            $this->db->from('vac_absence_types');
                                                            $this->db->where('type', $absence_type);
                                                            $test = $this->db->get();
                                                            if ($test && $test->num_rows() > 0) {
                                                                $ro = $test->row();
                                                                $absence_color = $ro->color;
                                                            }
                                                            //$absence_color = $ab_row->absence_color;
                                                            $emp_month_tot = $emp_month_tot + 1;
                                                        }
                                                    } else {
                                                        //   echo 'no data';
                                                    }
                                                }
                                                ?>
                                                <td data-search="<?php echo $ab_curr_val; ?>" data-order="<?php echo $ab_curr_val; ?>">
                                                    <input type="text" class="form-control absent_entry" value="<?php echo $ab_curr_val; ?>" data-curr_val="<?php echo $ab_curr_val; ?>" style="background-color: <?php echo $absence_color; ?>" data-emp_id="<?php echo $emp->id; ?>" data-day="<?php echo $i; ?>" data-month="<?php echo $cur_month; ?>" data-year="<?php echo $cur_year; ?>" maxlength="1" >
                                                </td>
                                                <?php
                                            }
                                            ?>
                                            <td class="absent_entry">
                                                <?php
                                                // total of row
                                                echo $emp_month_tot ? $emp_month_tot : '';
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            <tfoot>
                                <tr>
                                    <td>
                                        <b><?php
                                            echo date('M') . '</b> Total';
                                            ?></td>
                                        <?php
                                        for ($i = 1; $i <= $maxDays; $i++) {
                                            $day_total = 0;
                                            if (isset($employees)) {
                                                foreach ($employees as $emp) {

                                                    $absence_count = 0;
                                                    $absence_type = '';

                                                    $whereInfo = array('emp_id' => $emp->id, 'day' => $i, 'month' => $cur_month, 'year' => $cur_year);
                                                    $this->db->select('id,absence_type,absence_count');
                                                    $this->db->from('vac_emp_vacation');
                                                    $this->db->where($whereInfo);
                                                    $emp_vac_row = $this->db->get();

                                                    if ($emp_vac_row && $emp_vac_row->num_rows()) {
                                                        $rows = $emp_vac_row->result();
                                                        foreach ($rows as $ab_row) {
                                                            if ($ab_row->id > 0) {
                                                                $absence_count = $ab_row->absence_count;
                                                                if ($absence_count && $absence_count > 0) {
                                                                    $day_total = $absence_count + $day_total;
                                                                } else if ($ab_row->absence_type != '') {
                                                                    $day_total = $day_total + 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        <td class="absent_entry">
                                            <?php
                                            // total of column
                                            echo $day_total ? $day_total : '';
                                            ?>
                                        </td>
                                        <?php
                                    }
                                    ?>
                                    <td class="absent_entry">
                                        <?php
                                        // total of column
                                        $month_total = 0;
                                        if (isset($employees)) {
                                            foreach ($employees as $emp) {

                                                for ($i = 1; $i <= $maxDays; $i++) {
                                                    $absence_count = 0;
                                                    $absence_type = '';

                                                    $whereInfo = array('emp_id' => $emp->id, 'day' => $i, 'month' => $cur_month, 'year' => $cur_year);
                                                    $this->db->select('id,absence_count,absence_type');
                                                    $this->db->from('vac_emp_vacation');
                                                    $this->db->where($whereInfo);
                                                    $emp_vac_row = $this->db->get();

                                                    if ($emp_vac_row && $emp_vac_row->num_rows()) {
                                                        $rows = $emp_vac_row->result();
                                                        foreach ($rows as $ab_row) {
                                                            if ($ab_row->id > 0) {
                                                                $absence_count = $ab_row->absence_count;
                                                                if ($absence_count && $absence_count > 0) {
                                                                    $month_total = $absence_count + $month_total;
                                                                } else if ($ab_row->absence_type != '') {
                                                                    $month_total = $month_total + 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>



                                                <?php
                                            }
                                        }
                                        ?>
                                        <?php
                                        echo $month_total ? $month_total : '';
                                        ?>
                                    </td>
                                </tr>
                            </tfoot>
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
            window.location.href = '?m=' + m + 'y=' + y;
        });
        $('.year_dp').on('change', function () {
            var m = $('.month_dp').val();
            var y = $('.year_dp').val();
            window.location.href = '?m=' + m + '&y=' + y;
        });
        $('.absent_entry').on('blur', function () {
            // console.log('called');
            var prev_val = $.trim($(this).data('curr_val'));
            var new_val = $.trim($(this).val());
            var day = $(this).data('day');
            var month = $(this).data('month');
            var year = $(this).data('year');
            var emp_id = $(this).data('emp_id');
            var this_ele = $(this);

            if (prev_val !== new_val) {
                $.ajax({
                    url: "<?php echo base_url() . 'user/updateEmpVac'; ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        day: day,
                        new_val: new_val,
                        year: year,
                        month: month,
                        emp_id: emp_id
                    },
                    success: function (data) {
                        if (data.type_color == '') {
                            this_ele.data('curr_val', prev_val);
                            this_ele.val(prev_val);
                            alert('Given absence type not found, please chek absense type menu for all types.')
                        } else {
                            if (data.count > 0) {
                                this_ele.data('curr_val', new_val);
                                if (data.type_color !== '' && data.type_color !== 0) {
                                    this_ele.css('background-color', data.type_color);
                                    window.location.reload();
                                }

                            } else {

                                alert('Unable to update, please try again later!');
                            }
                        }

                    }
                });
            } else {

            }
        });
    });
</script>
