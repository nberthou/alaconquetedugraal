var express = require('express');
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);

http.listen(3000, function() {
    console.log('Listening on *:3000')
});

io.on('connection', function(socket) {
    console.log('Coucou');
    socket.on('message', function (message) {
        io.emit('message', message);
        console.log('message : ' + message.text);
    })
})
