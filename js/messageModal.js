
(function($) {
    $.fn.modalMessage = function() {
        var content = $(this);
        $('#sendMessage').remove();
        $('#message').remove();
        $($(this).data('target')).empty();
        $(content.data('hide')).removeClass('d-none');
        var id = $(this).data('id');
        var idConv = $(this).data('conv');
        $.get('../messageJSON.php', {
            'id' : id,
            'idConv' : idConv,
        }, function(data) {
            if (data !== false) {
                var messages = JSON.parse(data);
                var tableContent = '';
                for (let i = 0; i < messages.length; i++) {
                    let message = messages[i]['textContent'];
                    if (parseInt(messages[i]['idSender']) === id) {
                        tableContent += "<tr class='w-100'><td class='w-50'><p></p></td><td class=' rounded" +" w-50" +
                            " align-content-end'>" +
                            "<p class=' text-light bg-primary w-100 rounded'>" + message + "</p></td></tr>";
                    } else {
                        tableContent += "<tr class='w-100'><td class='rounded w-50'><p class='bg-light w-100 rounded'>"
                            + message + "</p></td><td class='w-50'><p></p></td></tr>";
                    }
                }
                $(content.data('target')).append(tableContent);
                $($(content.data('target'))).append("<textarea id=\"message\" data-id='" + id +
                    "' data-conv='" + idConv + "' class=\"w-100 rounded\"></textarea>" +
                    "<button class=\"btn btn-primary\" id=\"sendMessage\">envoyer</button>");
                console.log("hei", $("#tableMessage").height());
                $("#tableMessage").scrollTop($("#tableMessage").height() + 900000);

                $('#sendMessage').insertAfter($(content.data('target')));
                $('#message').insertAfter($(content.data('target')));
                $('#sendMessage').click(function () {
                    console.log("click");
                    var myMessage = $(this).parent();
                    console.log("parent", myMessage);
                    console.log("sender", $('#message').data('id'), 'id conv', $('#message').data('conv'));
                    $.get('../messageJSON.php', {
                        'message' : $('#message').val(),
                        'sender' : $('#message').data('id'),
                        'idConv' : $('#message').data('conv')
                    }, function(data) {
                        if (JSON.parse(data)) {
                            $("#currentModal").modalMessage();
                            $(window).scrollTop($(document).height() + 1000);
                        }
                    });
                });
            } else {
                $(content.data('hide').addClass('d-none'));
            }
        });

    };
})(jQuery);
$('.modalConv').click(function () {
    $('.modalConv').each(function () {
        $(this).removeAttr("id");
    });
    $(this).attr('id', 'currentModal');
    $(this).modalMessage();
});
console.log("mess", $('#sendMessage'));
