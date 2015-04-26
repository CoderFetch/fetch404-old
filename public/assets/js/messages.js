var Messages = {
    init: function() {
        $('#messages').click(function(e) {
            $('.popup[id!="messagesPopup"]').slideUp(500);
            if ($('#messagesPopup').is(':visible')) {
                $('#messagesPopup').slideUp(500);
            } else {
                $('#messagesPopup').slideDown(500, function() {

                });
            }
            e.preventDefault();
        });
    }
};

$(function() {
    Messages.init();
});

$(document).mouseup(function (e)
{
    var container = $("#messagesPopup");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.slideUp("slow");
    }
});