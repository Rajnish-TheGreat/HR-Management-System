<?php 
require_once "include/header.php";
?>
<?php
        // database connection
        require_once "../connection.php";

        $i = 1;
        // applied leaves--------------------------------------------------------------------------------------------
        $total_accepted = $total_pending = $total_canceled = $total_applied = 0;
        $leave = "SELECT * FROM emp_leave WHERE email = '$_SESSION[email_emp]' ";
        $result = mysqli_query($conn , $leave);

        if( mysqli_num_rows($result) > 0 ){

            $total_applied = mysqli_num_rows($result);

            while( $leave_info = mysqli_fetch_assoc($result) ){
                $status = $leave_info["status"];

                if( $status == "pending" ){
                    $total_pending += 1;
                }elseif( $status == "Accepted" ){
                    $total_accepted += 1;
                }elseif( $status = "Canceled"){
                    $total_canceled += 1;
                }
            }
        }else{
            $total_accepted = $total_pending = $total_canceled = $total_applied = 0;
        }

        // leave status--------------------------------------------------------------------------------------------------------------
        $currentDay = date( 'Y-m-d', strtotime("today") );

        $last_leave_status = "No leave applied";
        $upcoming_leave_status = "";

        // for last leave status
        $check_leave = "SELECT * FROM emp_leave WHERE email = '$_SESSION[email_emp]' ";
        $s = mysqli_query($conn , $check_leave);
        if( mysqli_num_rows($s) > 0 ){
            while( $info = mysqli_fetch_assoc($s) ){
               $last_leave_status =  $info["status"] ;
            }
    }

    // for next leave date
    $check_ = "SELECT * FROM emp_leave WHERE email = '$_SESSION[email_emp]' ORDER BY start_date ASC ";
    $e = mysqli_query($conn , $check_); 
    if( mysqli_num_rows($e) > 0 ){
        while( $info = mysqli_fetch_assoc($e) ){
            $date = $info["start_date"] ;
            $last_leave =  $info["status"] ;
           if ( $date > $currentDay && $last_leave == "Accepted" ){
               $upcoming_leave_status = date('jS F', strtotime($date) ) ;
               break;
           }
        }
}
        // total employee--------------------------------------------------------------------------------------------
        $select_emp = "SELECT * FROM employee";
        $total_emp = mysqli_query($conn , $select_emp);

        // highest paid employee--------------------------------------------------------------------------
        $sql_highest_salary =  "SELECT * FROM employee ORDER BY salary DESC";
        $emp_ = mysqli_query($conn , $sql_highest_salary);
?>

<div class="container">
    <div class="row mt-5 " style=" display: flex,justify-content: center,align-items: center;">
        <div class="col-6">
            <div class="card shadow " style="width: 25rem;">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center" style="background:#7571f9;"> <b style="color:white;">Leave Status</b> </li>
                    <li class="list-group-item">Upcoming Leave on :  <?php echo  $upcoming_leave_status ; ?>  </li>
                    <li class="list-group-item">Last Leave's Status :  <?php echo ucwords($last_leave_status) ;  ?> </li>
                </ul>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow " style="width: 25rem;">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center"  style="background:#7571f9;"> <b style="color:white;">Applied leaves</b> </li>
                    <li class="list-group-item">Total Accepted  : <?php echo $total_accepted;  ?> </li>
                    <li class="list-group-item">Total Canceled  : <?php echo $total_canceled; ?> </li>
                    <li class="list-group-item">Total Pending  : <?php echo $total_pending; ?> </li>
                    <li class="list-group-item">Total Applied  : <?php echo $total_applied; ?> </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- <div class="row mt-5">
        <div class="col-4">       
        </div>

        <div class="col-4">
            <div class="card shadow " style="width: 18rem;">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center">Employees on Leave (Weekwise) </li>
                    <li class="list-group-item">This Week : </li>
                    <li class="list-group-item">Next Week : </li>
                </ul>
            </div>
        </div>
    </div> -->
</div>
<?php 
require_once "include/footer.php";
?>