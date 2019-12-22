<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-dashboard"></i> DWS Dashboard

        </h1>
    </section>

    <?php
  //  print_r($statuses);
   // die();
    error_reporting(0);
//calculate total absent on each month of current year
    $monthTotEmp = array();
    $currYear = date('Y');
    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $cur_year = $currYear;
    $running_mnt = date('M');

    ?>

    <section class="content">
      
        <div class="row">
        
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Reported Breakdowns</span>
                        <span class="info-box-number"><?php echo $total_reported_breakdowns; ?><small></small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-play-circle-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">On Going Breakdowns</span>
                        <span class="info-box-number"><?php echo $total_on_going_breakdowns; ?><small></small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>


            <!-- /.col -->
           
            <div class="col-md-3 col-sm-6 col-xs-12">
            
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-stop"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">On Hold Breakdowns</span>
                            <span class="info-box-number"><?php echo $total_on_hold_breakdowns; ?><small></small></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
            
                <!-- /.info-box -->
            </div>
            
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="computers">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-check-square-o"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Completed Breakdowns</span>
                            <span class="info-box-number"><?php echo $total_completed_breakdowns; ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="map_area" width="1200px" style="overflow: scroll;">
                    <canvas id="bar-chart-grouped" width="1200" height="400"></canvas>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <canvas id="pie-chart" width="400" height="400"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="bar-chart-grouped2" width="400" height="400"></canvas>

            </div>
        </div>
    </section>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script>
var DataSet = [
    <?php  foreach ($status_names as $status) { ?>
                            {
                            label: "<?php echo $status['name']; ?>",
                            backgroundColor: "<?php echo $status['color_code']; ?>",
                            data: [
                                <?php  foreach ($months as $month) { ?>
                                <?php echo $total_breakdowns[$month][$status['name']]; ?>,
                                <?php } ?>
                                ]
                            },
    <?php } ?>
];
    new Chart(document.getElementById("bar-chart-grouped"), {
    type: 'bar',
            data: {
            labels: ["Januray", "February", "March", "April", "May", "June", "July", "August", "September", "Octomber", "November", "December"],
            datasets: DataSet
            },
            options: {
            title: {
                display: true,
                text: 'Monthly Breakdwns (days)'
                }
            }
    });
</script>