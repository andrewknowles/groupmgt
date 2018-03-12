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
		<form class="form-inline" method = "POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
			<div class="form-group">
				<label for="date">Transaction id to reverse:</label>
					<input type="input" class="form-control" name="toreverse" id="toreverse">
			</div>
			<div class="form-group">
				<input type="submit" name="submit" class="btn btn-primary" value="Submit" id="submitreverse">
			</div>
		</form>
</div>
<?php
if(isset($_POST['submit'])){
echo "xxxxxxxxxxxxxx".$_POST['toreverse'];
session_start();
require_once 'config.php';


	if(!isset($_SESSION['groupname'])){
  		header("location: welcome.php");
	} else {
 		$sql = "SELECT trans_id, trans_date, income, expenditure, category, note, period_id, reverse_id FROM transaction WHERE trans_id = ?";
				if($stmt = mysqli_prepare($link, $sql)){
            		mysqli_stmt_bind_param($stmt, "i", $trans);
            		$trans = $_POST['toreverse'];
            			if(mysqli_stmt_execute($stmt)){
 //               			$result = mysqli_stmt_get_result($stmt);
 //               			$numrows = mysqli_stmt_num_rows($stmt);
   //             			echo 'Rows  '.$numrows;
            mysqli_stmt_bind_result($stmt, $transid, $transdate, $income, $expenditure, $category, $note, $period, $reverse);
            $postings = [];

            	while (mysqli_stmt_fetch($stmt)){
            		$postings[] = ['Trans_Id' => $transid, 'Trans_Date' => $transdate, 'Income' => $income, 'Expenditure' =>$expenditure, 'Category' =>$category, 'Note' => $note, 'Period' => $period]; 
                }

                } else {
                		echo "Database update failed - contact support";
            	}

    		}
		}
//print_r($postings);
//		if($numrows == 1){
				foreach($postings as $post) {
					$transid1 = $post['Trans_Id'];
					$transdate1 = $post['Trans_Date'];
					$income1 = $post['Income'];
					$expenditure1 = $post['Expenditure'];
					$category1 = $post['Category'];
					$note1 = $post['Note'];
					$period1 = $post['Period'];
				}
			if ($income1 > 0){
				$income2 = 0;
				$expenditure2 = $income1;
			}

			if ($expenditure1 > 0){
				$expenditure2 = 0;
				$income2 = $expenditure1;
			}

			if(strlen($note1)==0){
				$note1 = "---";
			}

			$note2 = "Reversal_of_transaction_";
			$reverse2 = $transid1;
//			} else  
//				echo "Incorrect transaction id";
//			}
				

?>



<div class="container">
		<h3>Reverse transaction :<?php echo " ".$transid1 ?></h3>
		<form class="form-inline" role="form" id="reversing">
			<div class="form-group">
				<label for="date">Date:</label>
					<input type="date" class="form-control" name="date1" id="postdate1" value=<?php echo $transdate1 ?> readonly>
			</div>
			<div class="form-group"
				<label for="income1">Income:</label>
				<input type="number" step="0.01" class="form-control" name="income1" id="income1" value=<?php echo $income1 ?> readonly>
			</div>
			<div class="form-group"
				<label for="expenditure1">Expenditure:</label>
				<input type="number" step="0.01" class="form-control" name="expenditure1" id="expenditure1" value=<?php echo $expenditure1 ?> readonly>
			</div>
			<div class="form-group"
				<label for="category1">Category:</label>
				<input type="text" class="form-control" name="category1" id="category1" value=<?php echo $category1 ?> readonly>
			</div>
			<div class="form-group"
				<label for="note1">Note:</label>
				<input type="text" class="form-control" name="note1" id="note1" value=<?php echo $note1 ?> readonly>
			</div>
			<div class="form-group"
				<label for="period1">Period:</label>
				<input type="text" class="form-control" name="period1" id="period1" value=<?php echo $period1 ?> readonly>
			</div>
			<div class="form-group"
			<label for="transid1">Old Trans:</label>
				<input  class="form-control" name="transid1" id="transid1" value=<?php echo $transid1 ?> readonly>
			</div>
		
			<br><br>
			<div class="form-group">
				<label for="date">Rev. Date:</label>
					<input type="date" class="form-control" name="date2" id="postdate2">
			</div>
			<div class="form-group"
				<label for="income2">Income:</label>
				<input type="number" step="0.01" class="form-control" name="income2" id="income2" value=<?php echo $income2 ?> readonly>
			</div>
			<div class="form-group"
				<label for="expenditure2">Expenditure:</label>
				<input type="number" step="0.01" class="form-control" name="expenditure2" id="expenditure2" value=<?php echo $expenditure2 ?> readonly>
			</div>
			<div class="form-group"
				<label for="category2">Category:</label>
				<input type="text" class="form-control" name="category2" id="category2" value=<?php echo $category1 ?> readonly>
			</div>
			<div class="form-group"
				<label for="note2">Note:</label>
				<input type="text" class="form-control" name="note1" id="note2" value=<?php echo $note2.$transid1 ?> readonly>
			</div>
			<div class="form-group"
				<label for="period2">Period:</label>
				<input type="text" class="form-control" name="period2" id="period1" value=<?php echo $period1 ?> readonly>
			</div>
			<br><br>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Save" id="reversesave">
				<input type="button" class="btn btn-primary" value="Abandon"
				>
			</div>



		</form>	
	</div>
<script type="text/javascript" src="./js/post_reverse.js"></script>
<?php
}
?>
</body>
</html>

