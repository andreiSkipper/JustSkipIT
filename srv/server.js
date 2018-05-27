// var app = require('express')();
// var server = require('http').Server(app);
// var io = require('socket.io')(server);
//
// var users = {};
//
// app.use(require('express').static(__dirname));
//
// io.sockets.on('connection', function (client) {
//     client.on('join', function (request) {
//         if (!(client.id in users)) {
//             console.log('connected      ' + request.user.email);
//             users[client.id] = request;
//             var result = {
//                 user: request,
//                 users: users
//             };
//             io.sockets.emit('update', result);
//         }
//     });
//     client.on('disconnect', function () {
//         if (client.id in users) {
//             console.log('disconnected   ' + users[client.id].user.email);
//             var request = {
//                 user: users[client.id]
//             };
//             delete users[client.id];
//             request.users = users;
//             io.sockets.emit('update', request);
//         }
//     });
//     client.on('chat message', function (request) {
//         console.log('message        ' + request.user.email + ': ' + request.message);
//         io.sockets.emit('chat message', request);
//     });
//
//     client.on('typing', function (request) {
//         io.sockets.emit('typing', request);
//     });
//
//     client.on('not-typing', function (request) {
//         io.sockets.emit('not-typing', request);
//     });
//
//     client.on('notification', function (request) {
//         console.log(request);
//     });
// });
//
// server.listen(3002, function () {
//     console.log('The server is running ...');
// });


var socket = require('socket.io'),
    express = require('express'),
    http = require('http'),
    logger = require('winston');

logger.remove(logger.transports.Console);
logger.add(logger.transports.Console, {colorize: true, timestamp: true});
logger.info('SocketIO > listening on port 3002');

var app = express();
var http_server = http.createServer(app).listen(3002);

function emitNewOrder(http_server) {
    var io = socket.listen(http_server);

    var users = {};

    io.sockets.on('connection', function (socket) {
        socket.on('join', function (request) {
            socket.user_id = request.user.id;
            if (!(socket.user_id in users)) {
                logger.info('connected      ' + request.user.email);
                users[socket.user_id] = request;
                var result = {
                    user: request,
                    users: users
                };
                io.sockets.emit('update', result);
                io.sockets.emit('users', result);
            }
        });
        socket.on('disconnect', function () {
            if (socket.user_id in users) {
                logger.info('disconnected   ' + users[socket.user_id].user.email);
                var request = {
                    user: users[socket.user_id]
                };
                delete users[socket.user_id];
                request.users = users;
                io.sockets.emit('update', request);
                io.sockets.emit('users', request);
            }
        });
        socket.on('users', function () {
            io.sockets.emit('users', {users: users});
        });
        socket.on('chat message', function (request) {
            logger.info('message        ' + request.user.email + ': ' + request.message);
            io.sockets.emit('chat message', request);
        });

        socket.on('typing', function (request) {
            io.sockets.emit('typing', request);
        });

        socket.on('not-typing', function (request) {
            io.sockets.emit('not-typing', request);
        });

        socket.on('notification', function (request) {
            io.sockets.emit('notification', request);
        });

        socket.on('comment', function (request) {
            io.sockets.emit('comment', request);
        });

        socket.on('remove-comment', function (request) {
            io.sockets.emit('remove-comment', request);
        });

        socket.on('remove-action', function (request) {
            io.sockets.emit('remove-action', request);
        });
    });
}

emitNewOrder(http_server);
