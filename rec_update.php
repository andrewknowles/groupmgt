<?php

session_start();
require_once 'config.php';

	if(!isset($_SESSION['Group_Id'])){
  		header("location: reconcile.php");
	} else {
//$_POST['ttype'] == 1 - user clicked the Save Reconciliation button - reconciliation not completed 
        if($_POST['ttype'] == 1){
//add a row to the reconciliation table
 	        $sql = "INSERT INTO reconciliation (rec_id, rec_status, group_id, end_balance, statement_date, rec_date, rec_note) values (?,?,?,?,?,?,?)";
            $stmt = mysqli_stmt_init($link);
            if (!$stmt){
//             error_log(strftime("Y-m-d H:M:S").'Bad statement',0); 
            }
 			if($stmt = mysqli_prepare($link, $sql)){
//                error_log(strftime("Y-m-d H:M:S").'In prepare',0);
           	mysqli_stmt_bind_param($stmt, "iiiisss", $recid, $recstatus, $group, $bal, $sdate, $rdate, $note);
                $recid = 5;
                $recstatus = $_POST['ttype'];
                $group = $_SESSION['Group_Id'];
            	$bal = $_POST['bal'];
//                $bal = 200.20;
                $sdate = '2018-03-03';
                $rdate = "2018-03-03";
            	$note = $_POST['transid'];
            		if(mysqli_stmt_execute($stmt)){
                		$result = mysqli_stmt_get_result($stmt);
//                        error_log(strftime("Y-m-d H:M:S") .'In'.$_SESSION['Group_Id'].' ttype= '.$_POST['ttype'].'Success', 0);
                		echo "Database updated";
                	} else {
//                        error_log(strftime("Y-m-d H:M:S") .' RecId '.$recid.' RecStatus '.$recstatus.' Group '.$group.' Bal '.$bal.' sdate '.$stdate.'rdate '.$rdate.' note '.$note.'Failure', 0);
                	echo "Database update failed - contact support";
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
        }
    } 

/*        else if($_POST['ttype'] === 2) {
            $sql = "INSERT INTO transaction (group_id, trans_date, income, expenditure, category, user_id, note, period_id, rev_date, reverse_id  ) values (?,?,?,?,?,?,?,?,?,?)";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "isddiisisi", $group, $date, $in, $out, $category, $user, $note, $period, $revstamp, $revid);
                $group = $_SESSION['Group_Id'];
                $date = $_POST['pdate'];
                $in = $_POST['inc'];
                $out = $_POST['exp'];
                $category = $_POST['cat'];
                $user = $_SESSION['username'];
                $note = $_POST['note'];
                $period = '2018';
                $revstamp = date('Y-m-d H:i:s');
                $revid = $_POST['oldtrans'];
                
                    if(mysqli_stmt_execute($stmt)){
                        $result = mysqli_stmt_get_result($stmt);
                      echo "Database updated reversal";
                    } else {
                    echo "Database update failed - contact support";
                    }
            }

            $sql = "SELECT trans_id from transaction where reverse_id = $revid";
            if($stmt = mysqli_prepare($link, $sql)){
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_bind_result($stmt, $newtransid);
                    if(mysqli_stmt_fetch($stmt)){
                            $andrew = $newtransid;
                        }
                        echo $andrew;
                    }

            }
            mysqli_stmt_close($stmt);

            $sql1 = "UPDATE transaction set reverse_id = ? where trans_id = ?";
            $stmt = mysqli_stmt_init($link);
            if(mysqli_stmt_prepare($stmt, $sql1)){
                mysqli_stmt_bind_param($stmt, "ii", $andrew, $revid);
                if(mysqli_stmt_execute($stmt)){
                    echo "executed";
                }
            } else {
                echo "prepare failed";
            }
        mysqli_stmt_close($stmt);

        }
    }*/
?>