<?php
// Initialize the session
session_start();
require_once 'config.php';


	if(!isset($_SESSION['groupname'])){
  		header("location: welcome.php");
	} else {
 		$sql = "SELECT trans_id, trans_date, income, expenditure, category, note,reverse_id FROM transaction WHERE group_id = ?";
				if($stmt = mysqli_prepare($link, $sql)){
            		mysqli_stmt_bind_param($stmt, "i", $group);
            		$group = $_SESSION['Group_Id'];
            			if(mysqli_stmt_execute($stmt)){
 //               			$result = mysqli_stmt_get_result($stmt);

                			$numrows = mysqli_stmt_num_rows($stmt);
            mysqli_stmt_bind_result($stmt, $transid, $transdate, $income, $expenditure, $category, $note, $reverse);
            $data = [];

            	while (mysqli_stmt_fetch($stmt)){
            		$postings[] = ['Trans_Id' => $transid, 'Trans_Date' => $transdate, 'Income' => $income, 'Expenditure' =>$expenditure, 'Category' =>$category, 'Note' => $note, 'Reverse' => $reverse]; 
                }

                } else {
                		echo "Database update failed - contact support";
            	}
    }
}

if(isset($_POST["export_data"])) {
$filename = "export_".date('Ymd') . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
$show_coloumn = false;
if(!empty($postings)) {
foreach($postings as $record) {
if(!$show_coloumn) {
// display field/column names in first row
echo implode("\t", array_keys($record)) . "\n";
$show_coloumn = true;
}
echo implode("\t", array_values($record)) . "\n";
}
}
exit;
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
		<h3>Display postings for :<?php echo " ". $_SESSION['groupname'] ?></h3>
    	
			<div class="well well-sm col-sm-12">
				<div class="btn-group pull-right">
					<p><a href="welcome.php" class="btn btn-danger">Back to main menu</a></p>
					<p><a href="reverse.php" class="btn btn-danger">Reverse transaction</a></p>
					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
						<button type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-info">Export to excel</button>

					</form>
				</div>
			</div>
		<table id="" class="table table-striped table-bordered">
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
				<?php foreach($postings as $post) { 
					if($post['Reverse'] !==0){
						echo "<tr class='danger'>";
					} else {
						echo "<tr>";
					}
					?>
					<td><?php echo $post ['Trans_Id']; ?></td>
					<td><?php echo $post ['Trans_Date']; ?></td> 
					<td><?php echo $post ['Income']; ?></td>
					<td><?php echo $post ['Expenditure']; ?></td>
					<td><?php echo $post ['Category']; ?></td>
					<td><?php echo $post ['Note']; ?></td>
					<td><?php echo $post ['Reverse']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</body>


