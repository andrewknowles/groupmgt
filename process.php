<?php
session_start();
require_once 'config.php';
// Connect to a database
//$conn = mysqli_connect('localhost', 'root', '123456', 'ajaxtest');

//echo 'Processing...';
// Check for POST variable
//if(isset($_POST['pdate'])){
//	$group = $_SESSION['Group_Id'];
//  $date = mysqli_real_escape_string($link, $_POST['pdate']);
//  $inc = mysqli_real_escape_string($link, $_POST['inc']);
//  $exp = mysqli_real_escape_string($link, $_POST['exp']);
//  $cat = mysqli_real_escape_string($link, $_POST['cat']);
//  $user = $_SESSION['username'];
//  $note = mysqli_real_escape_string($link, $_POST['note']);
//  $period = '2018';
	
//	$query = "INSERT INTO transactions(Group_Id, Trans_Date, Income, Expenditure, Category, User_Id, Note, Period_Id) VALUES('".$group."', '".$date."','".$inc."', '".$exp."', '".$cat."', '".$user."', '". $note ."', '".$period."')";

//echo $query;
//  if(mysqli_query($link, $query)){
//    echo 'User Added...';
//  } else {
//    echo 'ERROR: '. mysqli_error($link);
//  }
if(!isset($_POST['date'])){
  header("location: post.php");
} else {
   $sql = "INSERT INTO transactions (Group_id, Trans_Date, Income, Expenditure, Category, User_id, Note, Period_Id) values (?,?,?,?,?,?,?,?)";
 if($stmt = mysqli_prepare($link, $sql)){
                  // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isddiisi", $group, $date, $in, $out, $category, $user, $note, $period);
            
            // Set parameters
            $group = $_SESSION['Group_Id'];
            $date = $_POST['pdate'];
            $in = $_POST['inc'];
            $out = $_POST['exp'];
            $category = $_POST['cat'];
            $user = $_SESSION['username'];
            $note = $_POST['note'];
            $period = '2018';
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
            	echo "Sucess";
                /* store result */
//            $result = mysqli_stmt_store_result($stmt);
                $result = mysqli_stmt_get_result($stmt);
                } else {
                	echo "failed";
                }
            }
        }


