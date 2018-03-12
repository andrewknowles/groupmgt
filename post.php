<?php
// Initialize the session
session_start();
require_once 'config.php';

if(isset($_SESSION['Group_Id'])){

    $sql = "SELECT groupcategory.category_id, category.category_name FROM groupcategory, category WHERE groupcategory.category_id = category.category_id AND groupcategory.group_id = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_groupid);
            $param_groupid = $_SESSION['Group_Id'];

            if(mysqli_stmt_execute($stmt)){
           		$result = mysqli_stmt_get_result($stmt); 
           		$categorylist = array();

 				while ($row = mysqli_fetch_assoc($result))
        		{
            		$categorylist[] = $row;
        		}
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
<body id='mainpost>'
	<div class="container">
		<h3>Enter postings for the :<?php echo " ".$_SESSION['groupname']." " ?>Group</h3>
		    <p><a href="welcome.php" class="btn btn-danger">Back to main menu</a></p>
		<form class="form-inline" role="form" id="posting">
			<div class="form-group">
				<label for="date">Date:</label>
				<input type="date" class="form-control" name="date" id="postdate">
			</div>
			<div class="form-group">
				<label for="income">Income:</label>
				<input type="number" step="0.01" class="form-control" name="income" id="income">
			</div>
			<div class="form-group">
				<label for="expenditure">Expenditure:</label>
				<input type="number" step="0.01" class="form-control" name="expenditure" id="expenditure">
			</div>
			<div class="form-group">
				<label for="category">Category:</label>
                    <?php
                    $select = '<select class="form-control" name="categorySelect" id="category">';
                    $select.='<option value=0 selected>Please select ...</option>';
                    foreach($categorylist as $data) {
                        $select.='<option value="'.$data['category_id'].'">'.$data['category_name'].'</option>';
                    }
                    $select.='</select>';
                    echo $select;
                        ?>                  
            </div>
			<br><br>
			<div class="form-group">
				<label for="note">Note:</label>
				<textarea class="form-control" rows="1" name="note" id = "note"></textarea>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Save" id="postsave">
			</div>
		</form>
	</div>
	<hr>
	<h3>Last 10 transactions</h3>
	<div class="container">
	<div id = "postings"></div>
	</div>
	<script type="text/javascript" src="./js/post_list.js"></script>
	</body>