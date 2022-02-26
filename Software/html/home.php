<?php

session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.html');
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Humidistat Page</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>

        <script src="mqtt.js"></script>
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
                        <a href="/account.php"><button class="btn btn-primary mx-1" type="button">Account</button></a>
                        <a href="/logout.php"><button class="btn btn-danger mx-1" type="button">Sign Out</button></a>
                    </form>
                </div>
            </div>
        </nav>

        <div class="container text-center">
            <h1 class="mt-4">Humidistat IoT Project</h1>
            <p class="text-sm">By Gordon Deacon (1803716@uad.ac.uk)</p>
            <p>Please allow 30 seconds for updates</p>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Temperature (C)</th>
                        <th>Humidity</th>
                        <th>LUX</th>
                        <th>VPD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th id="tempSection"></th>
                        <th id="humSection"></th>
                        <th id="luxSection"></th>
                        <th id="vpdSection"></th>
                    </tr>
                </tbody>
            </table>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Avg. Temp (C)</th>
                        <th>Avg. RH</th>
                        <th>Avg. LUX</th>
                        <th>Avg. VPD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th id="avgTempSection"></th>
                        <th id="avgHumSection"></th>
                        <th id="avgLUXSection"></th>
                        <th id="avgVPDSection"></th>
                    </tr>
                </tbody>
            </table>
        </div>
            <script type="text/javascript">
                mqttConnect();
            </script>
    </body>
</html>