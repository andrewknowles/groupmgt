<?php
// Initialize the session
session_start();
require_once 'config.php';
//if(isset($_SESSION['Group_Id']) && $_SESSION['Group_Id']<>0){
//    unset($_SESSION['Group_Id']);
//}

// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
} else {
 $sql = "SELECT groups.Group_id, groups.Group_Name FROM group_officers, groups WHERE group_officers.Group_Id = groups.Group_Id and  group_officers.User_Id = ?";
 if($stmt = mysqli_prepare($link, $sql)){
                  // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_username);
            
            // Set parameters
            $param_username = $_SESSION['username'];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
//            $result = mysqli_stmt_store_result($stmt);
                $result = mysqli_stmt_get_result($stmt);
                }
            }
 $grouplist = array();

 while ($row = mysqli_fetch_assoc($result))
        {
            $grouplist[] = $row;
        }
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>. Welcome to our site.</h1>
    </div>
    <div class="page-body">
<p>You are authorised to manage the following groups - please select from the list</p>
    </div>

      <div class="wrapper">
            <form id="postForm">
            <div class="form-group">
                <label>Select Group</label>
                    <?php
                    $select = '<select name="groupSelect" id="groupSelect">';
                    $select.='<option value=0>Please select ...</option>';
                    foreach($grouplist as $data) {
                        $select.='<option value="'.$data['Group_id'].'">'.$data['Group_Name'].'</option>';
                    }
                    $select.='</select>';
                    echo $select;
                        ?>                  
            </div>
        </form>
    </div>
<?php

?>

    <p><a href="post.php" class="btn btn-primary">Post entries to group acconnt</a></p>
    <p><a href="excel.php" class="btn btn-primary">List entries on group account</a></p>
    <p><a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a></p>

    <script>
document.getElementById('groupSelect').addEventListener("change", getGroup);

    function getGroup(e){
      e.preventDefault();

      var groupId = document.getElementById('groupSelect').value

      var params = "groupid="+groupId;

      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'getGroupDetails.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.send(params);
    }
</script>
</body>
</html>