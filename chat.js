jQuery(document).ready(function($) {
    var socket = io("http://localhost:4000"); // Connect to backend

    // Listen for messages
    socket.on('chatMessage', function(msg) {
        $('#chat-messages').append('<div>' + msg + '</div>');
    });

    // Send message
    $('#chat-send').on('click', function() {
        var message = $('#chat-input').val();
        if(message.trim() !== '') {
            socket.emit('chatMessage', message);
            $('#chat-input').val('');
        }
    });
});
