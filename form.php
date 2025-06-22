<?php 
/* Template Name: Form */
?>

<?php 
    global $wpdb;
    $table = $wpdb->prefix . 'user_form_data';
if(isset($_POST['submit'])) {
    $fname = trim($_POST['fname']);
    $wpdb->insert($table, [
        'name' => $fname,
    ]);

    if($fname != '') {
        die("Hello, " . htmlspecialchars($fname));
        
    } else {
        echo '<style>
            .button {
                color: #fff;
                background-color: #ff0000;
                border: 1px solid red;
            }
            .fname {
                border: 2px solid red;
            }
        </style>';
        echo "<p style='color:red;'>Please enter your name.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Validation</title>
</head>
<body>
   <form action="" method="POST">
    <label for="fname">Name</label>
    <div><input class="fname" type="text" name="fname" placeholder="Enter your name"></div>
    <input type="submit" name="submit" value="Submit" class="button">
   </form> 
</body>
</html>
