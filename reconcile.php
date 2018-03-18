<?php
// Initialize the session
session_start();
require_once 'config.php';


	if(!isset($_SESSION['Group_Id'])){
  		header("location: welcome.php");
	} else {
	$_SESSION['iprecid'] = NULL;
//Start Checking
//Check that there is atleast 1 reconciliation record for this group - if not(group has never used reconciliation) create a default record
		$sql = "SELECT rec_id FROM reconciliation WHERE group_id = ?";
		$stmt = mysqli_stmt_init($link);
		if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $group);
            $group = $_SESSION['Group_Id'];
            if(mysqli_stmt_execute($stmt)){
            	mysqli_stmt_store_result($stmt);
                $numrows = mysqli_stmt_num_rows($stmt);
                mysqli_stmt_close($stmt);
//if $numrows = 0 there are no reconciliation records for this group so create one
                if($numrows === 0){
                	$sql = "INSERT INTO reconciliation SET rec_id = 0, rec_status = 2, group_id = ?, end_balance = 0.00";
					$stmt = mysqli_stmt_init($link);
					if($stmt = mysqli_prepare($link, $sql)){
            			mysqli_stmt_bind_param($stmt, "i", $group);
            			$group = $_SESSION['Group_Id'];
            			if(mysqli_stmt_execute($stmt)){
            				mysqli_stmt_store_result($stmt);
                			$numrows = mysqli_stmt_num_rows($stmt);
                		}
                	}
                mysqli_stmt_close($stmt);
                }
            }
        }
        
//Check reconciliation table for status 1 records for this group

		$sql = "SELECT rec_id FROM reconciliation WHERE group_id = ? and rec_status = 1";
		$stmt = mysqli_stmt_init($link);
		if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $group);
            $group = $_SESSION['Group_Id'];
            if(mysqli_stmt_execute($stmt)){
            	mysqli_stmt_store_result($stmt);
                $numrows = mysqli_stmt_num_rows($stmt);
                if($numrows === 0){
                	$recmessage = "No reconciliation in progress - creating a new reconciliation";
                	$_SESSION['inprog'] = 0;
                } else if($numrows !==1) {
                	$recmessage = "An error has occured - contact support - Error Id = Reconcile_001";
//                	echo $recmessage;
//                	exit;
                } else {
                	mysqli_stmt_bind_result($stmt, $rec_id);
                	while (mysqli_stmt_fetch($stmt)){
            			$statement[] = ['Rec_Id' => $rec_id];
            		 	foreach($statement as $post) {
            		 			$lastrec = $post ['Rec_Id']; 
                		}
                	}
                	$_SESSION['inprog'] = 1;
                	$_SESSION['iprecid'] = $lastrec;
                	$recmessage = "Statement ".$lastrec." is in progress for this group - you need to complete the reconciliation of this statement before starting a new one";
                }
            }
        }
    mysqli_stmt_close($stmt);

//Check transaction for status 1 records for this group
//also check that all status 1 records belong to only one statement

		$sql = "SELECT rec_id FROM transaction WHERE group_id = ? and rec_status = 1 GROUP BY rec_id";
		$stmt = mysqli_stmt_init($link);
		if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $group);
            $group = $_SESSION['Group_Id'];
            if(mysqli_stmt_execute($stmt)){
            	mysqli_stmt_store_result($stmt);
                $numrows = mysqli_stmt_num_rows($stmt);
                if($numrows === 0){
//                	$_SESSION['inprog'] = 0;
                	$recmessage1 = "No reconciliation in progress - creating a new reconciliation";
                } else if($numrows !==1) {
                	$recmessage1 = "An error has occured - contact support - Error Id = Reconcile_002";
//                	echo $recmessage;
//                	exit;
                } else {
                	mysqli_stmt_bind_result($stmt, $rec_id);
                	while (mysqli_stmt_fetch($stmt)){
            			$statement[] = ['Rec_Id' => $rec_id];
            		 		foreach($statement as $post) {
            		 			$lastrec = $post ['Rec_Id']; 
                			}
                	}
                	$_SESSION['inprog'] = 1;
                	$recmessage1 = "Statement ".$recid." is in progress for this group - you need to complete the reconciliation of this statement before starting a new one";
                }
            }
        }
    mysqli_stmt_close($stmt);


//get summary of last closed reconciliation or current in progress reconciliation
 		$sql = "SELECT rec_id, rec_status, group_id, end_balance, statement_date, rec_date, rec_note FROM reconciliation WHERE rec_status IN(1,2) and group_id = ? and rec_id = (select max(rec_id) from reconciliation where group_id = ?)";
		$stmt = mysqli_stmt_init($link);

		if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ii", $group, $group);
            $group = $_SESSION['Group_Id'];

            if(mysqli_stmt_execute($stmt)){
            	mysqli_stmt_store_result($stmt);
                $numrows = mysqli_stmt_num_rows($stmt);
				mysqli_stmt_bind_result($stmt, $rec_id, $rec_status, $group_id, $end_balance, $statement_date, $rec_date, $rec_note);
            	$statement = [];

            	if($numrows == 1){            	
            		while (mysqli_stmt_fetch($stmt)){
            			$statement[] = ['Rec_Id' => $rec_id, 'Rec_Status' => $rec_status, 'Group_Id' => $group_id, 'End_Balance' => $end_balance, 'Statement_Date' =>$statement_date, 'Rec_Date' =>$rec_date, 'Rec_Note' => $rec_note];
            		 		foreach($statement as $post) {
            		 			$lastrec = $post['Rec_Id'] ;
            					$recstatus = $post['Rec_Status'];
            					if($recstatus == 2){
            						$nextrec = "Enter Statement No";
            					} else {
            						$nextrec = $post['Rec_Id'] ;
            					}
								$endbal = $post ['End_Balance'];
								$lastsdate = $post ['Statement_Date'];
								$lastrdate = $post ['Rec_Date'];
								$lastnote = $post ['Rec_Note'];
                			}
                	} 
            	} else if($numrows == 0) {
            		$recid = 0;
            		$lastrec = 0;
            		$recstatus = 2;
					$endbal = 0;
					$lastsdate = '2000-01-01';
					$lastrdate = '2000-01-01';
					$lastnote = '';
            	} else {
            		echo "Numrows <> 1";
            	}
    		}
		}
	}
mysqli_stmt_close($stmt);

//get records to display
	if(!isset($_SESSION['Group_Id'])){
  		header("location: welcome.php");
	} else {
 		$sql = "SELECT trans_id, trans_date, income, expenditure, category, note, rec_id FROM transaction WHERE group_id = ?";
 		$stmt = mysqli_stmt_init($link);
				if($stmt = mysqli_prepare($link, $sql)){
            		mysqli_stmt_bind_param($stmt, "i", $group);
            		$group = $_SESSION['Group_Id'];
            			if(mysqli_stmt_execute($stmt)){
 //               			$result = mysqli_stmt_get_result($stmt);
mysqli_stmt_store_result($stmt);
                			$numrows = mysqli_stmt_num_rows($stmt);
            mysqli_stmt_bind_result($stmt, $transid, $transdate, $income, $expenditure, $category, $note, $rec);
            $postings = [];

            	while (mysqli_stmt_fetch($stmt)){
            		$postings[] = ['Trans_Id' => $transid, 'Trans_Date' => $transdate, 'Income' => $income, 'Expenditure' =>$expenditure, 'Category' =>$category, 'Note' => $note, 'Rec' => $rec]; 
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
		<p>Last Reconciled Statement :<?php echo " ". $lastrec."  " ?>&nbspMessage : <?php echo " ". $recmessage."  " ?><br>
			&nbspMessage : <?php echo " ". $recmessage1."  " ?><br>
			Closing Balance :<?php echo "  ". $endbal."  " ?><br>
		Last Statement Date :<?php echo " ". $lastsdate."  " ?><br>Last Reconciliation Date :<?php echo "  ". $lastrdate."  " ?><br>
		Last Reconciliation Note :<?php echo " ". $lastnote."  " ?><br>
		<form>
		<label>Statement No. to reconcile</label><input id="statno" type = "text" value ="<?php echo $nextrec ?>"/></p>
		<label>Statement Date</label><input id="statdate" type = "date" /></p>
		<input type="button" value="Save Reconciliation" onclick="saverec(1)"/>
		<input type="button" value="Finalise Reconciliation" onclick="saverec(2)"/>
	</form>
		<p>Running Total</p><p id = "newtotal"></p>


		

				<div class="btn-group pull-right">
<!--					<p><a href="welcome.php" class="btn btn-danger">Back to main menu</a></p>-->
					<button><a href="welcome.php">Back to main menu</a></button>
<!--					<p><a href="reverse.php" class="btn btn-danger">Reverse transaction</a></p>
					<button onclick="saverec(1)">Save Reconciliation</button>
					<button onclick="saverec(2)">Finalise Reconciliation</button>-->
<!--					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
//						<button type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-info">Export to excel</button>
//
//					</form>-->
				</div>
			</div>
<form>
<!--		<div id = "translist">-->
		<table id="rectable" class="table table-striped table-bordered">
			<tr>
				<th>Trans Id</th>
				<th>Date</th>
				<th>Income</th>
				<th>Expenditure</th>
				<th>Category</th>
				<th>Note</th>
				<th>Reconciliation Id</th>
				<th>Check</th>
			</tr>
			<tbody id="translist">
				<?php 
				$count = 1;
				foreach($postings as $post) {
				$val = 0; 
					$mycheck = "mycheck".$count;

						if($post['Rec'] !==0){
						echo "<tr class='danger'>";
					} else {
						echo "<tr>";
					}
					
					if($post ['Income'] > 0){
						$val = $post ['Trans_Id'].'|#|'.$post ['Income'];
					} else {
						$val1 = $post ['Expenditure'] - $post ['Expenditure'] - $post ['Expenditure'];
						$val = $post ['Trans_Id'].'|#|'.$val1;
					}
					?>
					<td><?php echo $post ['Trans_Id']; ?></td>
					<td><?php echo $post ['Trans_Date']; ?></td> 
					<td><?php echo $post ['Income']; ?></td>
					<td><?php echo $post ['Expenditure']; ?></td>
					<td><?php echo $post ['Category']; ?></td>
					<td><?php echo $post ['Note']; ?></td>
					<td><?php echo $post ['Rec']; ?></td>
					<td><label class="checkbox-inline" ><input type= "checkbox" value="<?php echo $val; ?>"  Reconcile ></label>
					</td>
				</tr>
				<?php ++$count; } ?>
			</tbody>
		</table>
<!--	</div>-->
	<label>Opening Balance <input type="text" name="obal" class="num" size="6" value=<?php echo "  ". $endbal."  " ?> readonly="readonly" /></label>
	<label>Income <input type="text" name="total" class="num" size="6" value="0.00" readonly="readonly" /></label>
    <label>Expenditure <input type="text" name="total1" class="num" size="6" value="0.00" readonly="readonly" /></label>
    <label>Closing Balance <input type="text" name="cbal" class="num" size="6" value=<?php echo "  ". $endbal."  " ?> readonly="readonly" /></label>
</form>
	</div>
	<script type="text/javascript" src="./js/reconcile_list2.js"></script>
</body>