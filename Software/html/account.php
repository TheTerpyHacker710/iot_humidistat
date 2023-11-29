#TEST
<?php include "../inc/dbinfo.inc"; ?>
<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.html');
	exit;
}

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ( mysqli_connect_errno() ) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT email FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($email);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Humidistat Page</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

        </head>

        <body>

            <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/home.php">Humidistat</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mynavbar">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="/home.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/history.php">History</a>
                            </li>
                        </ul> 

                        <form class="d-flex">
                            <p class="text-white m-2">Welcome, <?php echo $_SESSION["name"] ?></p>
                            <a href="/logout.php"><button class="btn btn-danger mx-1" type="button">Sign Out</button></a>
                        </form>
                    </div>
                </div>
            </nav>

            <form action="/update.php" method="post" class="container">

                <h2 class="text-center my-4">Account</h2>

                <div class="my-3 container">
                    <label for="username" class="form-label">Username:</label>
                    <p><b><?php echo $_SESSION["name"] ?></b></p>
                </div>

                <div class="my-3 container">
                    <label for="email" class="form-label">Email:</label>
                    <p><b><?php echo $email ?></b></p>
                </div>

                <div class="mb-3 container">
                    <label for="current_password" class="form-label">Current Password:</label>
                    <input type="password" class="form-control" id="current_password" placeholder="Enter Current Password..." name="current_password">
                </div>

                <div class="mb-3 container">
                    <label for="password" class="form-label">New Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter password..." name="password">
                </div>

                <div class="mb-3 container">
                    <label for="repassword" class="form-label">Repeat Password:</label>
                    <input type="password" class="form-control" id="repassword" placeholder="Re-enter password..." name="repassword">
                </div>

                <div class="row container">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            
            </form> 
        </body>
</html>
