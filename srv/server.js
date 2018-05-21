var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);

var users = {};

app.use(require('express').static(__dirname));

io.sockets.on('connection', function (client) {
    client.on('join', function (request) {
        console.log('connected      ' + request.user.email);
        users[client.id] = request;
        var result = {
            user: request,
            users: users
        };
        io.sockets.emit('update', result);
    });
    client.on('disconnect', function () {
        if(client.id in users) {
            console.log('disconnected   ' + users[client.id].user.email);
            var request = {
                user: users[client.id]
            };
            delete users[client.id];
            request.users = users;
            io.sockets.emit('update', request);
        }
    });
    client.on('chat message', function (request) {
        console.log('message        ' + request.user.email + ': ' + request.message);
        io.sockets.emit('chat message', request);
    });

    client.on('typing', function (request) {
        io.sockets.emit('typing', request);
    });

    client.on('not-typing', function (request) {
        io.sockets.emit('not-typing', request);
    });
});

server.listen(3002, function () {
    console.log('The server is running ...');
});
