<?php

session_start();
include 'config.php';

if(!isset($_SESSION['Group_Id'])){
    header("location: reconcile.php");
} else {
//    error_log(strftime("Y-m-d H:M:S").'tttype='.$_POST['ttype'].' inprog = '.$_SESSION['inprog'].' balance = '.$_POST['bal'],0);

//$_POST['ttype'] == 1 - user clicked the Save Reconciliation button - reconciliation saved in progress 
    if($_POST['ttype'] == 1){
        if($_POST['bal'] == 0){
//no transaction records selected - just update reconciliation
//            error_log(strftime("Y-m-d H:M:S").'[insertReconciliation]'.'tttype='.$_POST['ttype'].' inprog = '.$_SESSION['inprog'].' balance = '.$_POST['bal']. 'statement id '.$_POST['statno'].' group =  '.$_SESSION['Group_Id'].' date = '.$_POST['dstat']. 'note  '.$_POST['transid'],0);
    $sql = "INSERT INTO reconciliation (rec_id, rec_status, group_id, end_balance, statement_date, rec_note) values (?,?,?,?,?,?)";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "iiiiss", $sno, $recstatus, $group, $bal, $sdate, $note);
//        $recid = $_SESSION['iprecid'];
        $sno = $_POST['statno'];
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
        }
        if($_POST['bal'] <>0){
//records have been selected - need to create in progress records            
        }
//if this reconciliation is already in progress
        if($_SESSION['inprog'] = 0){
//create reconciliation and transaction records
        }
        if($_SESSION['inprog'] = 1){
//update reconciliation and transaction records
            
        }


//////////////////////////////////////////////////////////////////////////////////////////////////////
//$_POST['ttype'] == 2 - user clicked the Finalise Reconciliation button - reconciliation completed
////////////////////////////////////////////////////////////////////////////////////////////////////// 
    } else if ($_POST['ttype'] == 2){
//        error_log(strftime("Y-m-d H:M:S").'type2'.'tttype='.$_POST['ttype'].' inprog = '.$_SESSION['inprog'].' balance = '.$_POST['bal'],0);
/////////////////////////////////////////////////////////////////////////////////////////////////////
        if($_POST['bal'] == 0 && $_SESSION['inprog'] == 0){
//no transaction records selected - just update reconciliation
//            error_log(strftime("Y-m-d H:M:S").'[insertReconciliation]'.'tttype='.$_POST['ttype'].' inprog = '.$_SESSION['inprog'].' balance = '.$_POST['bal']. 'statement id '.$_POST['statno'].' group =  '.$_SESSION['Group_Id'].' date = '.$_POST['dstat']. 'note  '.$_POST['transid'],0);
    $sql = "INSERT INTO reconciliation (rec_id, rec_status, group_id, end_balance, statement_date, rec_note) values (?,?,?,?,?,?)";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "iiiiss", $sno, $recstatus, $group, $bal, $sdate, $note);
//        $recid = $_SESSION['iprecid'];
        $sno = $_POST['statno'];
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
///////////////////////////////////////////////////////////////////////////////////////////////////    
        } else if($_POST['bal'] == 1 && $_SESSION['inprog'] == 0){
    //create rec and update balance
/////////////////////////////////////////////////////////////////////////////////////////////////////
//update existing row in reconciliation no transaction records reconciled            
        } else if($_POST['bal'] == 0 && $_SESSION['inprog'] == 1){
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
    }
        } else if($_POST['bal'] == 1 && $_SESSION['inprog'] == 1){
    //update rec and balance
        } else {
        echo "Something went wrong - call support";
        }
        mysqli_stmt_close($stmt);
}
}

?>