jQuery(window).on(
    'load',
    function()
    {
        window['lastMessageId'] = 0;

        $('#message-window > div').each(
            function(index, el) {
                var id = parseInt($(el).data('id'));

                if (id > window['lastMessageId']) {
                    window['lastMessageId'] = id;
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
                            $('#message-window').append(
                                '<div>[' + data.messages[i].date + '] ' + data.messages[i].username + ': ' + data.messages[i].message + '</div>'
                            );

                            var id = parseInt(data.messages[i].id);

                            if (id > window['lastMessageId']) {
                                window['lastMessageId'] = id;
                            }
                        }
                    },
                    'json'
                );
            },
            1000
        );
    }
);