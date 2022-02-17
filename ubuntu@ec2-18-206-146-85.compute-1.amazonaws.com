<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Humidistat Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" language="javascript">
            var mqtt;
            var reconnectTimeout = 2000;
            var host = "52.90.65.39"; 
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
                    document.getElementById("tempSection").innerHTML = temp + "C";
                    document.getElementById("humSection").innerHTML = humidity + "%";
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
        <div class="container text-center">
            <h1>Humidistat IoT Project</h1>
            <div class="row">
                <div class="col"><h2>Temperature</h2></div>
                <div class="col"><h2>Humidity</h2></div>
            </div>
            <div class="row">
                <div class="col" id="tempSection"></div>
                <div class="col" id="humSection"></div>
            </div>
        </div>
            <script>
                mqttConnect();
            </script>
    </body>
</html>