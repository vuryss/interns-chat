jQuery(window).on(
    'load',
    function()
    {
        window['lastMessageId'] = 0;

        for (var i in initialMessages) {
            addMessage(
                initialMessages[i].id,
                initialMessages[i].date,
                initialMessages[i].username,
                initialMessages[i].message
            );
        }

        $('#message').on(
            'keypress',
            function(e) {
                if (e.keyCode == 13) {
                    $('#send-message').trigger('click');
                    return false;
                }
            }
        );

        $('#send-message').on(
            'click',
            function() {
                $.post(
                    '/index.php?action=message',
                    JSON.stringify({message: $('#message').val()}),
                    function(data) {
                        if (data.status !== 'success') {
                            alert(data.message);
                            return;
                        }

                        $('#message').val('');
                    },
                    'json'
                );
            }
        );

        setInterval(
            function()
            {
                $.post(
                    '/index.php?action=update',
                    JSON.stringify({id: window['lastMessageId']}),
                    function(data) {
                        if (data.status !== 'success') {
                            alert(data.message);
                            return;
                        }

                        // Update users
                        $('#users-list').html('');

                        for (var i in data.users) {
                            $('#users-list').append('<div>' + data.users[i].username + '</div>');
                        }

                        // Update messages
                        for (var i in data.messages) {
                            addMessage(
                                data.messages[i].id,
                                data.messages[i].date,
                                data.messages[i].username,
                                data.messages[i].message
                            );
                        }
                    },
                    'json'
                );
            },
            1000
        );
    }
);

var usernameColor = {};

function addMessage(id, date, user, message)
{
    // Generate random color for each user
    if (!usernameColor[user]) {
        var colors = [
            parseInt(Math.random() * 100),
            parseInt(Math.random() * 100),
            parseInt(Math.random() * 55) + 200
        ];

        shuffleArray(colors);

        usernameColor[user] = colors.join(',');
    }

    user = '<span style="font-weight: bold; color: rgb(' + usernameColor[user] + ')">' + user + '</span>';

    // Add message to message list
    $('#message-window').append(
        '<div>[' + date + '] ' + user + ': ' + message + '</div>'
    );

    // Scroll to bottom
    $('#message-window').scrollTop($('#message-window')[0].scrollHeight);

    // Save last message ID
    var id = parseInt(id);

    if (id > window['lastMessageId']) {
        window['lastMessageId'] = id;
    }
}

function shuffleArray(array)
{
    var i = 0,
        j = 0,
        temp = null;

    for (i = array.length - 1; i > 0; i -= 1) {
        j = Math.floor(Math.random() * (i + 1));
        temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
}