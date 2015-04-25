var Messages = {
    shouldReload: true,

    init: function() {
        $('#messages').click(function(e) {
            var that = this;

            $('.popup[id!="messagesPopup"]').slideUp(500);
            if ($('#messagesPopup').is(':visible')) {
                $('#messagesPopup').slideUp(500);
            } else {
                $('#messagesPopup').slideDown(500, function() {
                    //if ($('#messagesPopup div ul li[class=message]').length > 0) {
                    //    $.ajax({
                    //        type: "POST",
                    //        url: "/settings/notifications/markAsRead",
                    //        success: function(data) {},
                    //        error: function(data) {}
                    //    });
                    //}
                });
            }
            e.preventDefault();
        });

        //Notifications.updateTitle($("#notifications").html());

        //setInterval(Notifications.checkNotifications, (12000));
    },
    updateTitle: function(number) {
        var re = /\(\d+\)/;
        var count = number > 0 ? "("+number+") " : "";
        if (document.title.search(re) != -1) document.title = document.title.replace(re, count);
        else document.title = count + document.title;
    }
};

$(function() {
    Messages.init();
});

$(document).mouseup(function (e)
{
    var container = $("#notificationsPopup");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.slideUp("slow");
    }
});