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
            <div class="row bg-dark text-white">
                <div class="col border"><h2>Temperature (C)</h2></div>
                <div class="col border"><h2>Humidity (%)</h2></div>
                <div class="col border"><h2>LUX (lx)</h2></div>
                <div class="col border"><h2>VPD (KPa)</h2></div>
            </div>
            <div class="row">
                <div class="col border py-4" id="tempSection"></div>
                <div class="col border py-4" id="humSection"></div>
                <div class="col border py-4" id="luxSection"></div>
                <div class="col border py-4" id="vpdSection"></div>
            </div>
            <div class="row bg-dark text-white">
                <div class="col border"><h2>Avg. Temp (C)</h2></div>
                <div class="col border"><h2>Avg. RH (%)</h2></div>
                <div class="col border"><h2>Avg. LUX (lx)</h2></div>
                <div class="col border"><h2>Avg. VPD (KPa)</h2></div>
            </div>
            <div class="row">
                <div class="col border py-4" id="avgTempSection"></div>
                <div class="col border py-4" id="avgHumSection"></div>
                <div class="col border py-4" id="avgLuxSection"></div>
                <div class="col border py-4" id="avgVPDSection"></div>
            </div>
        </div>
            <script type="text/javascript">
                mqttConnect();
            </script>
    </body>
</html>