<?php
// Initialize the session
session_start();
require_once 'config.php';
echo "Enter postings for :";
echo $_SESSION['Group_Id'] ."  ".$_SESSION['groupname'];
?>