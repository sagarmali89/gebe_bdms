<!-- <?php
//print_r($invoice);
//echo "<hr>";
//print_r($particular);
//echo "<hr>";
?> -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $invoice->caller_name; ?></title>
        <link href="<?php echo base_url(); ?>assets/css/invoice.css" rel="stylesheet" type="text/css" />
    </head> 
    <body>
        <!-- Invoice -->
        <div id="invoice">
            <table>
                <thead>
                    <tr>
                        <td style="">NV GEBE</td>
                        <td style="text-align: center; vertical-align: top;"><b  style="margin-right:-190px;">Breakdown  Work Order</b></td>
                        <td style=""><b> Toll Free:</b>   1 (844) 4323-213   </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: center; vertical-align: middle;">
                            <h2><b>NV GEBE</b></h2>
                            <p>
                                Walter J. Nisbeth Road #35<br>
                                Philipsburg, Sint Maarten<br>
                                Phone: 1 (721) 542-2213</p>
                        </td>
                    </tr>
                    <tr style="border: 1px solid grey;">
                        <td colspan="2" style="text-align: left; vertical-align: top; padding: 15px; border-right:1px solid grey;">
                            <p>
                                <b>Customer Details</b><br>
                                <b>Caller Name: </b><?php echo $invoice->caller_name; ?>,<br>
                                <b>Address: </b><?php echo $street_names[$invoice->street_id]; ?><br>
                                <?php if (trim($invoice->direction_note) !== '') { ?>
                                    <b>Direction Note: </b><?php echo $invoice->direction_note; ?><br>
                                <?php } ?>
                                <b>House Number: </b><?php echo $invoice->house_number; ?><br>
                                <b>Meter Number: </b><?php echo $invoice->meter_no; ?><br>
                                <b>Region: </b><?php echo $regions[$invoice->region_id]; ?><br>
                                <b>Phone Number: </b><?php echo $invoice->cellular; ?><br>
                                <b>Connection Type: </b><?php echo $invoice->connection_type; ?>
                            </p>
                        </td>
                        <td style="text-align: right; vertical-align: top; padding: 15px;     border-right: 1px solid black;">
                            <b>Breakdown ID:</b> <?php echo $invoice->id; ?><br>
                            <b>Date: </b><?php echo date('d/m/Y', strtotime($invoice->reported_date_to_technician)); ?><br>
                            <b>Technician: </b><?php echo $technicians[$invoice->technician_id]; ?><br>
                            <b>Created By: </b><?php echo $users[$invoice->createdBy]; ?><br>
                            <b>Reported Date Time: </b><?php echo date("Y-m-d", strtotime($record->reported_date_to_technician)) . ' ' . date("h:i", strtotime($record->reported_time_to_technician)); ?></td>

                        </td>
                    </tr>
                </thead>
                <tbody style="border: 1px solid grey;">
                    <tr>
                        <td colspan="3" style="padding:0px 5px;     border-right: 1px solid black;">
                            <table class="margin-top-20">
                                <tr>
                                    <th style="text-align: center;">SN</th>
                                    <th>Division</th>
                                    <th>Reason</th>
                                    <th>Problem Description</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>

                                <tr>
                                    <td style="text-align: center;">1<?php //echo $i;                 ?></td>
                                    <td><?php echo $invoice->division; ?></td>
                                    <td><?php echo $reasons[$invoice->reason_id]; ?></td>
                                    <td><?php echo $invoice->problem; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($invoice->reported_date_to_technician)); ?></td>
                                    <td><?php echo $invoice->status; ?></td>
                                </tr>


                            </table>
                        </td>
                    </tr>
                </tbody>
                <tfoot style="border: 1px solid grey;">
                    <tr>
                        <td colspan="6" style="text-align:left;padding:15px 10px; vertical-align: bottom;"> <b>Technician Note:</b></td>

                    </tr>
                    <tr> <td colspan="6"> </td></tr>
                    <tr> <td colspan="6" style="    border-top: 1px solid #eae6e6;"> </td></tr>
                    <tr> <td colspan="6" style="    border-top: 1px solid #eae6e6;"> </td></tr>
                    <tr> <td colspan="6" style="    border-top: 1px solid #eae6e6;"> </td></tr>
                    <tr> <td colspan="6" style="    border-top: 1px solid #eae6e6;"> </td></tr>
                    <tr> <td colspan="6"> </td></tr>
                    <tr> <td colspan="6" style="    border-top: 1px solid black;"> </td></tr>
                    <tr>
                        <td colspan="2" style="padding:15px 10px; vertical-align: bottom; text-align: left;">
                            <b>Customers Sign</b>
                        </td>
                        <td style="padding:15px 10px; vertical-align: bottom; text-align: right;     border-right: 1px solid black;">
                            <b>For - NV GEBE</b>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <!-- Print Button -->
            <div class="print-button-container">
                <a href="javascript:window.print()" class="print-button">Print</a>
            </div>	
        </div>
</html>
