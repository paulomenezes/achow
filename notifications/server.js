var express = require('express');
var app = express();

var bodyParser = require('body-parser');
app.use(bodyParser.json()); // support json encoded bodies
app.use(bodyParser.urlencoded({ extended: true })); // support encoded bodies

var apn = require('apn');

app.post('/n', function (req, res) {
	var tokens = req.body.id;

	var service = new apn.connection({ cert: 'cert.pem', 'key': 'cert.pem', passphrase: '95758213', production: false });

	service.on("connected", function() {
	    console.log("Connected");
	});

	service.on("transmitted", function(notification, device) {
	    console.log("Notification transmitted to:" + device.token.toString("hex"));
	});

	service.on("transmissionError", function(errCode, notification, device) {
	    console.error("Notification caused error: " + errCode + " for device ", device, notification);
	    if (errCode === 8) {
	        console.log("A error code of 8 indicates that the device token is invalid. This could be for a number of reasons - are you using the correct environment? i.e. Production vs. Sandbox");
	    }
	});

	service.on("timeout", function () {
	    console.log("Connection Timeout");
	});

	service.on("disconnected", function() {
	    console.log("Disconnected from APNS");
	});

	service.on("socketError", console.error);


	// If you plan on sending identical paylods to many devices you can do something like this.
	function pushNotificationToMany() {
	    console.log("Sending the same notification each of the devices with one call to pushNotification.");
	    var note = new apn.notification();
	    note.setAlertText(req.body.msg);
	    note.badge = 1;

	    service.pushNotification(note, tokens);
	}

	pushNotificationToMany();

	res.send('success');
});

app.listen(process.env.PORT);