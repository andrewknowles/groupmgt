<?php

session_start();
require_once 'config.php';

if(!isset($_SESSION['Group_Id'])){
    header("location: reconcile.php");
} else {
    error_log(strftime("Y-m-d H:M:S").'tttype='.$_POST['ttype'].'inprog = '.$_SESSION['inprog'].'statement no = '.$_SESSION['iprecid'],0);
    exit; 
//$_POST['ttype'] == 1 - user clicked the Save Reconciliation button - reconciliation not completed 
    if($_POST['ttype'] == 1){
//if there is an inprogress reconciliation
        if($_SESSION['inprog'] == 1){
//update existing row in reconciliation
            $sql = "UPDATE reconciliation SET rec_id = ?, rec_status = ?, group_id = ?, end_balance = ?, statement_date = ?, rec_note = ? where rec_id = ?";
            $stmt = mysqli_stmt_init($link);
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "iiiissi", $recid, $recstatus, $group, $bal, $sdate, $note, $recid);
                $recid = $_SESSION['iprecid'];
                $recstatus = $_POST['ttype'];
                $group = $_SESSION['Group_Id'];
                $bal = $_POST['bal'];
                $sdate = $_POST['dstat'];
                $note = $_POST['transid'];
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                echo "Reconciliation updated";
            } else {
                echo "Database update failed - contact support";
            }
        } else {
//add a row to the reconciliation table
            $sql = "INSERT INTO reconciliation (rec_id, rec_status, group_id, end_balance, statement_date, rec_note) values (?,?,?,?,?,?)";
               if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "iiiiss", $recid, $recstatus, $group, $bal, $sdate, $note);
                    $recid = $_SESSION['iprecid'];
                    $recstatus = $_POST['ttype'];
                    $group = $_SESSION['Group_Id'];
                    $bal = $_POST['bal'];
                    $sdate = $_POST['dstat'];
                    $note = $_POST['transid'];
                    if(mysqli_stmt_execute($stmt)){
                        $result = mysqli_stmt_get_result($stmt);
                        echo "Database updated";
                    } else {
                        echo "Insert into reconciliation failed";
                    }
                }
    mysqli_stmt_close($stmt);
//update rows in transaction setting rec_status = 1
//update transaction where group = $_SESSION['Group_Id'] and trans_id in $_POST['transid']
    $trans = mysqli_real_escape_string($link, $_POST['transid']);
    $sql = "UPDATE transaction SET rec_status = 1 WHERE group_id = ? AND rec_status = 0 AND trans_id IN (" .$trans.")";
    $stmt = mysqli_stmt_init($link);
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $group);
        $group = $_SESSION['Group_Id'];

        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
        } else {
            echo "Database update failed - contact support";
        }
    }
    mysqli_stmt_close($stmt);
} else if($_POST['ttype'] == 2) {
    error_log(strftime("Y-m-d H:M:S").'ttype='.$_POST['ttype'].'inprog = '.$_SESSION['inprog'],0); 
    if($_SESSION['inprog'] == 1){
        $trans = mysqli_real_escape_string($link, $_POST['transid']);
        $sql = "UPDATE transaction SET rec_id = ".$_SESSION['iprecid'].", rec_status = 1, rec_date = NOW() WHERE trans_id IN (" .$trans.")";
        error_log(strftime("Y-m-d H:M:S").$sql,0); 
    } else {
        $trans = mysqli_real_escape_string($link, $_POST['transid']);
        $sql = "UPDATE transaction SET rec_id = ".$_SESSION['iprecid'].", rec_status = 2, rec_date = NOW() WHERE trans_id IN (" .$trans.")";
    }
    if($stmt = mysqli_prepare($link, $sql)){

        if(mysqli_stmt_execute($stmt)){
//            $result = mysqli_stmt_get_result($stmt);
            echo "Database updated reconciliation";
        } else {
            echo "Database update failed - contact support";
        }
    }         
    mysqli_stmt_close($stmt);

}
}

?>