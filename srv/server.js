var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);

var users = {};

app.use(require('express').static(__dirname));

io.sockets.on('connection', function (client) {
    client.on('join', function (data) {
        console.log('connected      ' + data.user.email);
        users[client.id] = data;
        io.sockets.emit('update', users);
    });
    client.on('disconnect', function () {
        if(client.id in users) {
            console.log('disconnected   ' + users[client.id].user.email);
            delete users[client.id];
            io.sockets.emit('update', users);
        }
    });
    client.on('chat message', function (data) {
        console.log('message        ' + data.user.email + ': ' + data.message);
        io.sockets.emit('chat message', data);
    });
});

server.listen(3002, function () {
    console.log('The server is running ...');
});
