<?php
session_start();
require_once 'config.php';
// Check for POST variable
if(isset($_POST['groupid'])){
$_SESSION['Group_Id'] = $_POST['groupid'];
$groupid = $_POST['groupid'];
}

if(isset($_SESSION['Group_Id'])){
        // Prepare a select statement
        $sql = "SELECT Group_Name FROM groups WHERE Group_Id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_groupid);
            
            // Set parameters
            $param_groupid = $groupid;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if group exists
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $groupid);
                    if(mysqli_stmt_fetch($stmt)){
                            $_SESSION['groupname'] = $groupid;
                        }
                    }
                }
            }
        }                   
?>