var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);

var users = {};

app.use(require('express').static(__dirname));

// app.get('/', function(req, res) {
// 	res.sendFile(__dirname + '/index.html');
// });

io.sockets.on('connection', function(client) {
	client.on('join', function(username) {
		// client.username = username;
		// users.username = username;
		users[client.id] = username;
		console.log(users);
		io.sockets.emit('update', users);
	});
	client.on('disconnect', function() {
		console.log('disconnected');
		console.log(delete users[client.username]);
		io.sockets.emit('update', users)
	});
	client.on('chat message', function(data) {
		io.sockets.emit('chat message', data);
	});
});

server.listen(3001, function() {
	console.log('The server is running ...');
});
