<?php
// We need to use sessions, so you should always start sessions using the below code.
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

        <script type="text/javascript" language="javascript">
            var mqtt;
            var reconnectTimeout = 2000;
            var host = "54.224.242.1"; 
            var port = 9001;

            var humidity = 0;
            var temperature = 0;

            function onConnect(){
                //Once a connection has been made, make a subscription and send a message
                console.log("Connected");
                mqtt.subscribe("enviro");
                message = new Paho.MQTT.Message("Connected!");
                message.destinationName = "enviro";
                mqtt.send(message);
            }

            function onFailure(){
                console.log("Connection to " + host + ":" + port + " failed!");
                setTimeout(mqttConnect, reconnectTimeout);
            }

            function onMessage(msg){
                console.log(msg.payloadString);
                var data = extractValues(msg.payloadString);
                console.log(data);
                if(data.length > 1){

                    var tempArr = data[0];
                    var tempTemp = tempArr[0];
                    var temp = tempTemp[1];

                    var humArr = data[2];
                    var humHum = humArr[0];
                    var humidity = humHum[1];

                    var luxArr = data[3];
                    var luxLux = luxArr[0];
                    var lux = luxLux[1];

                    var vpdArr = data[1];
                    var vpdVPD = vpdArr[0];
                    var vpd = vpdVPD[1];
                    

                    document.getElementById("tempSection").innerHTML = temp + "C"; 
                    document.getElementById("tempSection").style.color = 'green';
                            
                    document.getElementById("humSection").innerHTML = humidity + "%";
                    document.getElementById("humSection").style.color = 'green';
                            
                    document.getElementById("luxSection").innerHTML = lux + "lx";
                    document.getElementById("luxSection").style.color = 'green';
                            
                    document.getElementById("vpdSection").innerHTML = vpd + "KPa";
                    document.getElementById("vpdSection").style.color = 'green';
                            
                    setTimeout(() => { 
                        document.getElementById("tempSection").style.color = 'black';
                        document.getElementById("humSection").style.color = 'black';
                        document.getElementById("luxSection").style.color = 'black';
                        document.getElementById("vpdSection").style.color = 'black';  
                    }, 500);
                            
                    
                }
                

            }

            function mqttConnect(){
                console.log("Connecting to " + host + ":" + port);
                mqtt = new Paho.MQTT.Client(host, port, "clientJS");
                //document.write("connection to " + host + ":" + port);
                var options = {
                    timeout: 3,
                    onSuccess: onConnect,
                    onFailure: onFailure,
                };

                mqtt.onMessageArrived = onMessage

                mqtt.connect(options); //connect
            }

            function extractValues(data){
                console.log("Extract: " + data);
                const KV_SEP = ": ";
                const ENTITY_SEP = ", ";
                var obj = {};

                obj = data.split(ENTITY_SEP).map(function(val){
                    return [val.split(KV_SEP)];
                });

                return obj;
            }

        </script> 
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
            <script>
                mqttConnect();
            </script>
    </body>
</html>