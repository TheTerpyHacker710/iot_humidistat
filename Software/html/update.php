<?php include "../inc/dbinfo.inc"; ?>
<?php 

    //start a session
    session_start();

    if(!isset($_SESSION['id'])){
        echo 'Sorry Please login!';
        header('Location: login.html');
    }
    
    $current_password = $_POST['current_password'];
    $new_password = $_POST['password'];
    $repassword = $_POST['repassword'];

    $con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    if (mysqli_connect_errno()) {
        //If there is an error with the connection, then fail
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    if (!isset($new_password, $repassword, $current_password)) {
        // Could not get the data that should have been sent.
        exit('Please complete the form!');
    }

    // Make sure the submitted registration values are not empty.
    if (empty($new_password) || empty($repassword) || empty($current_password)) {
        // One or more values are empty.
        exit('Please completeeee thee form');
    }

    if (strlen($new_password) > 32 || strlen($new_password) < 5) {
        exit('Password must be between 5 and 32 characters long!');
    }

    if ($new_password !== $repassword) {
        exit('Passwords do not match!');
    }

    if ($new_password === $current_password) {
        exit('Password cannot be the same as last time!');
    }

    // We don't have the password or email info stored in sessions so instead we can get the results from the database.
    $stmt = $con->prepare('SELECT password FROM accounts WHERE id = ?');
    // In this case we can use the account ID to get the account info.
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($password);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($current_password, $password)) {
        //Verification successful
        //Change the password
        $stmt = $con->prepare('UPDATE accounts SET password = ? WHERE id = ?');
        $stmt->bind_param('si', password_hash($new_password, PASSWORD_DEFAULT), $_SESSION['id']);
        $stmt->execute();

        header('Location: account.php');
    }
    else {
        exit('Incorrect Password!');
    }
$con->close();
?>