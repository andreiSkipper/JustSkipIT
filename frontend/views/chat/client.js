var socket = io('http://localhost:3001');
var user;

do {
    user = prompt('Username:');
    user = jQuery.trim(user);
} while(user.length == 0);

$('#username').val(user);
socket.emit('join', $('#username').val());
socket.on('update', function(user) {
    $('#users-list').empty();
    $.each(user, function(index, value) {
        $('#users-list').append($('<li>').text(value));
    });
});

$('#send-message').click(function() {
    var $message = $('#message').val();
    var $username = $('#username').val();
    if(jQuery.trim($username).length > 0) {
        if(jQuery.trim($message).length > 0) {
            socket.emit('chat message', {username: $('#username').val(), message: $('#message').val()});
            $('#message').val('');
            return false;
        } else {
            alert('The message can not be empty');
        }
    }
    else {
        prompt('The username can not be empty, please choose a username: ');
    }
});

socket.on('chat message', function(data) {
    $('#messages').append($('<li>').text(data.username + ': ' + data.message));
});