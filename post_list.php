<?php
session_start();
require_once 'config.php';

$sql = "SELECT trans_date, income, expenditure, category_name, note FROM transaction, category WHERE transaction.category = category.category_id AND group_id = ?  ORDER BY trans_id DESC LIMIT 10";

	if($stmt = mysqli_prepare($link, $sql)){
		mysqli_stmt_bind_param($stmt, "i", $param_username);         
        $param_username = $_SESSION['Group_Id'];

            if(mysqli_stmt_execute($stmt)){
			$numrows = mysqli_stmt_num_rows($stmt);
            mysqli_stmt_bind_result($stmt, $transdate, $income, $expenditure, $category, $note);
            $postings = [];

            	while (mysqli_stmt_fetch($stmt)){
            		$postings[] = ['Trans_Date' => $transdate, 'Income' => $income, 'Expenditure' =>$expenditure, 'Category' =>$category, 'Note' => $note]; 
           		}          
            }
    }
           echo json_encode($postings);
?>