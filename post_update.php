<?php

session_start();
require_once 'config.php';

	if(!isset($_POST['pdate'])){
  		header("location: post.php");
	} else {
        if($_POST['newrev'] == 'new'){
 	        $sql = "INSERT INTO transaction (group_id, trans_date, income, expenditure, category, user_id, note, period_id) values (?,?,?,?,?,?,?,?)";
            $stmt = mysqli_stmt_init($link);
 			if($stmt = mysqli_prepare($link, $sql)){
            	mysqli_stmt_bind_param($stmt, "isddiisi", $group, $date, $in, $out, $category, $user, $note, $period);
                $group = $_SESSION['Group_Id'];
            	$date = $_POST['pdate'];
                $in = $_POST['inc'];
            	$out = $_POST['exp'];
            	$category = $_POST['cat'];
            	$user = $_SESSION['username'];
            	$note = $_POST['note'];
            	$period = '2018';
            		if(mysqli_stmt_execute($stmt)){
                		$result = mysqli_stmt_get_result($stmt);
                		echo "Database updated";
                	} else {
                	echo "Database update failed - contact support";
                	}
            }
        } else if($_POST['newrev'] == 'reverse') {
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
    }
?>