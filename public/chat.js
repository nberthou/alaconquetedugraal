var express = require('express');
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var messages = [];

http.listen(3000, function() {
    console.log('Listening on *:3000')
});

io.on('connection', function(socket) {

    var loggedUser;

    socket.on('disconnect', function() {
        if(loggedUser !== undefined) {
            console.log('Un joueur s\'est déconnecté : ' + loggedUser.pseudo);
            var serviceMessage = {
                text: 'Le joueur ' + loggedUser.pseudo + ' s\'est déconnecté.',
                type: 'logout'
            };
            socket.broadcast.emit('service-message', serviceMessage);

        }
    });

    socket.on('user-login' , function(joueur) {
        loggedUser = joueur;

        if(loggedUser !== undefined) {
            var serviceMessage = {
                text: 'Le joueur ' + loggedUser.pseudo + ' s\'est connecté.',
                type: 'login'
            };

            socket.broadcast.emit('service-message', serviceMessage);
        }
    });


    socket.on('message', function (message) {
        message.pseudo = loggedUser.pseudo;
        io.emit('message', message);
        messages.push(message);
        if(messages.length > 150) {
            message.splice(0,1);
        }
        console.log('Message de : ' + loggedUser.pseudo)
    });

    for(i = 0; i < messages.length; i++) {
        if(messages[i].pseudo !== undefined) {
            socket.emit('message', messages[i]);
        } else {
            socket.emit('service-message', messages[i]);
        }
    }
});
