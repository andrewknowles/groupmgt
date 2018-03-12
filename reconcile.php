<?php
// Initialize the session
session_start();
require_once 'config.php';


	if(!isset($_SESSION['groupname'])){
  		header("location: welcome.php");
	} else {
 		$sql = "SELECT MAX(rec_id), group_id, end_balance, statement_date, rec_date, rec_note FROM reconciliation WHERE group_id = ? group by group_id, end_balance, statement_date, rec_date, rec_note";
		$stmt = mysqli_stmt_init($link);

		if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $group);
            $group = $_SESSION['Group_Id'];

            if(mysqli_stmt_execute($stmt)){
            	mysqli_stmt_store_result($stmt);
                $numrows = mysqli_stmt_num_rows($stmt);
				mysqli_stmt_bind_result($stmt, $rec_id, $group_id, $end_balance, $statement_date, $rec_date, $rec_note);
            	$statement = [];

            	if($numrows === 1){            	
            		while (mysqli_stmt_fetch($stmt)){
            			$statement[] = ['Rec_Id' => $rec_id, 'Group_Id' => $group_id, 'End_Balance' => $end_balance, 'Statement_Date' =>$statement_date, 'Rec_Date' =>$rec_date, 'Rec_Note' => $rec_note];
            		 		foreach($statement as $post) { 
            					$lastrec = $post ['Rec_Id'];
								$endbal = $post ['End_Balance'];
								$lastsdate = $post ['Statement_Date'];
								$lastrdate = $post ['Rec_Date'];
								$lastnote = $post ['Rec_Note'];
                			}
                	} 
            	} else {
            		echo "Numrows <> 1";
            	}
    		}
		}
	}
mysqli_stmt_close($stmt);


	if(!isset($_SESSION['groupname'])){
  		header("location: welcome.php");
	} else {
 		$sql = "SELECT trans_id, trans_date, income, expenditure, category, note,reverse_id FROM transaction WHERE group_id = ?";
 		$stmt = mysqli_stmt_init($link);
				if($stmt = mysqli_prepare($link, $sql)){
            		mysqli_stmt_bind_param($stmt, "i", $group);
            		$group = $_SESSION['Group_Id'];
            			if(mysqli_stmt_execute($stmt)){
 //               			$result = mysqli_stmt_get_result($stmt);
mysqli_stmt_store_result($stmt);
                			$numrows = mysqli_stmt_num_rows($stmt);
            mysqli_stmt_bind_result($stmt, $transid, $transdate, $income, $expenditure, $category, $note, $reverse);
            $postings = [];

            	while (mysqli_stmt_fetch($stmt)){
            		$postings[] = ['Trans_Id' => $transid, 'Trans_Date' => $transdate, 'Income' => $income, 'Expenditure' =>$expenditure, 'Category' =>$category, 'Note' => $note, 'Reverse' => $reverse]; 
                }

                } else {
                		echo "Database update failed - contact support";
            	}
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
	<style type="text/css">
	body{ font: 14px sans-serif; }
	.wrapper{ width: 350px; padding: 20px; }
	</style>
</head>
<body>
	<div class="container">
 
			<div class="well well-sm col-sm-12">
		<p>Last Reconciled Statement :<?php echo " ". $lastrec."  " ?><br>Closing Balance :<?php echo "  ". $endbal."  " ?><br>
		Last Statement Date :<?php echo " ". $lastsdate."  " ?><br>Last Reconciliation Date :<?php echo "  ". $lastrdate."  " ?><br>
		Last Reconciliation Note :<?php echo " ". $lastnote."  " ?></p>

		

				<div class="btn-group pull-right">
					<p><a href="welcome.php" class="btn btn-danger">Back to main menu</a></p>
					<p><a href="reverse.php" class="btn btn-danger">Reverse transaction</a></p>
					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
						<button type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-info">Export to excel</button>

					</form>
				</div>
			</div>
<form>
		<div id = "translist">
		<table id="rectable" class="table table-striped table-bordered">
			<tr>
				<th>Trans Id</th>
				<th>Date</th>
				<th>Income</th>
				<th>Expenditure</th>
				<th>Category</th>
				<th>Note</th>
				<th>Reverse Id</th>
			</tr>
			<tbody>
				<?php 
				$count = 1;
				foreach($postings as $post) { 
					$mycheck = "mycheck".$count;
					if(!is_null($post['Reverse'])){
						echo "<tr class='danger'>";
					} else {
						echo "<tr>";
					}
					if($post ['Income'] > 0){
						$val = $post ['Income'];
					} else {
						$val = $post ['Expenditure'] - $post ['Expenditure'] - $post ['Expenditure'];
					}
					?>
					<td><?php echo $post ['Trans_Id']; ?></td>
					<td><?php echo $post ['Trans_Date']; ?></td> 
					<td><?php echo $post ['Income']; ?></td>
					<td><?php echo $post ['Expenditure']; ?></td>
					<td><?php echo $post ['Category']; ?></td>
					<td><?php echo $post ['Note']; ?></td>
					<td><?php echo $post ['Reverse']; ?></td>
					<td><label class="checkbox-inline" >
						<input type= "checkbox" value="<?php echo $val; ?>">Reconcile
						</label>
					</td>
				</tr>
				<?php ++$count; } ?>
			</tbody>
		</table>
	</div>
	<label>Opening Balance <input type="text" name="obal" class="num" size="6" value=<?php echo "  ". $endbal."  " ?> readonly="readonly" /></label>
	<label>Income <input type="text" name="total" class="num" size="6" value="0.00" readonly="readonly" /></label>
    <label>Expenditure <input type="text" name="total1" class="num" size="6" value="0.00" readonly="readonly" /></label>
    <label>Closing Balance <input type="text" name="cbal" class="num" size="6" value=<?php echo "  ". $endbal."  " ?> readonly="readonly" /></label>
</form>
	</div>
	<script type="text/javascript" src="./js/reconcile_list2.js"></script>
</body>