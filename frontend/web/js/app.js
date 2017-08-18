/**
 * Created by andrei on 16/08/2017.
 */

$.ajax({
    url: window.location.origin + '/index.php/site/notifications',
    type: "POST",
    success: function (data) {
        var messages = jQuery.parseJSON(data);
        if (messages.length != 0) {
            $.each(messages, function (index, message) {
                var notification = new Notification(message.title, {
                    body: message.body,
                    icon: message.icon,
                    requireInteraction: true
                });
                notification.onclick = function (event) {
                    window.open(message.url, '_blank');
                }
            });
        }
    }
});
setInterval(function () {
    $.ajax({
        url: window.location.origin + '/index.php/site/notifications',
        type: "POST",
        success: function (data) {
            var messages = jQuery.parseJSON(data);
            if (messages.length != 0) {
                notification_count = parseInt($(".notification-count")[0].innerHTML);
                $(".notification-count")[0].innerHTML = notification_count + messages.length;
                $(".notification-count")[1].innerHTML = notification_count + messages.length;
                if ($(".notification-count").hasClass('hidden')) {
                    $(".notification-count").removeClass('hidden');
                }
                $.each(messages, function (index, message) {
                    var notification = new Notification(message.title, {
                        body: message.body,
                        icon: message.icon,
                        requireInteraction: true
                    });
                    notification.onclick = function (event) {
                        window.open(message.url, '_blank');
                    }
                });
            }
        }
    });
}, 10000);