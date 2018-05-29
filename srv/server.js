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

    var chat_users = {};
    var online_users = {};

    io.sockets.on('connection', function (socket) {
        socket.on('chat join', function (request) {
            socket.user_id = request.user.id;
            if (!(socket.user_id in chat_users)) {
                logger.info('connected      ' + request.user.email);
                chat_users[socket.user_id] = request;
                var result = {
                    user: request,
                    users: chat_users
                };
                io.sockets.emit('update', result);
                io.sockets.emit('chat users', result);
            }
        });
        socket.on('disconnect', function () {
            if (socket.user_id in chat_users) {
                logger.info('disconnected   ' + chat_users[socket.user_id].user.email);
                var request = {
                    user: chat_users[socket.user_id]
                };
                delete chat_users[socket.user_id];
                request.users = chat_users;
                io.sockets.emit('update', request);
                io.sockets.emit('users', request);
            }
            if (socket.online_user_id in online_users) {
                var request = {
                    user: online_users[socket.online_user_id]
                };
                delete online_users[socket.online_user_id];
                request.users = online_users;
                io.sockets.emit('online', request);
            }
        });
        socket.on('chat users', function () {
            io.sockets.emit('chat users', {users: chat_users});
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

        socket.on('online', function (request) {
            socket.online_user_id = request.user.id;
            if (!(socket.online_user_id in online_users)) {
                online_users[socket.online_user_id] = request;
                var result = {
                    user: request,
                    users: online_users
                };
                io.sockets.emit('online', result);
            }
        });
    });
}

emitNewOrder(http_server);
