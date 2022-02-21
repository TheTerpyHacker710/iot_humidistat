var mqtt;
var reconnectTimeout = 2000;
var host = "3.210.59.107"; 
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
