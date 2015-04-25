var Notifications = {
    shouldReload: true,

    init: function() {
        $('#notifications').click(function(e) {
            var that = this;
            $('.popup[id!="notificationsPopup"]').slideUp(500);
            if ($('#notificationsPopup').is(':visible')) {
                $('#notificationsPopup').slideUp(500);
            } else {
                $('#notificationsPopup').slideDown(500, function() {
                    if ($('#notificationsPopup div ul li[class=notification]').length > 0) {
                        $.ajax({
                            type: "POST",
                            url: "/settings/notifications/markAsRead",
                            success: function(data) {},
                            error: function(data) {}
                        });
                    }
                });
            }
            e.preventDefault();
        });

        Notifications.updateTitle($("#notifications").html());

        //setInterval(Notifications.checkNotifications, (12000));
    },
    updateTitle: function(number) {
        var re = /\(\d+\)/;
        var count = number > 0 ? "("+number+") " : "";
        if (document.title.search(re) != -1) document.title = document.title.replace(re, count);
        else document.title = count + document.title;
    },
    checkNotifications: function()
    {
        $.ajax({
            type: "GET",
            url: "settings/notifications/check",
            success: function(data) {
                // New notification messages are handled like normal messages!
                // Set the contents of the notifications button.
                $("#notificationsCount").text(data.count);
                Notifications.updateTitle(data.count);

                // If there are new notifications, mark the notifications popup as dirty.
                if (data.count > 0) Notifications.shouldReload = true;
            }
        })
    }
};

$(function() {
    Notifications.init();
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