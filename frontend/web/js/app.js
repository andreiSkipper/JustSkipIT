/**
 * Created by andrei on 16/08/2017.
 */

$.ajax({
    url: window.location.origin + '/index.php/site/notifications',
    type: "POST",
    success: function (data) {
        var messages = jQuery.parseJSON(data);
        if (messages.length !== 0) {
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
// setInterval(function () {
//     $.ajax({
//         url: window.location.origin + '/index.php/site/notifications',
//         type: "POST",
//         success: function (data) {
//             var messages = jQuery.parseJSON(data);
//             if (messages.length !== 0) {
//                 var notification_count = $(".notification-count");
//                 notification_count = parseInt(notification_count[0].innerHTML);
//                 notification_count[0].innerHTML = notification_count + messages.length;
//                 notification_count[1].innerHTML = notification_count + messages.length;
//                 if (notification_count.hasClass('hidden')) {
//                     notification_count.removeClass('hidden');
//                 }
//                 $.each(messages, function (index, message) {
//                     var notification = new Notification(message.title, {
//                         body: message.body,
//                         icon: message.icon,
//                         requireInteraction: true
//                     });
//                     notification.onclick = function (event) {
//                         window.open(message.url, '_blank');
//                     }
//                 });
//             }
//         }
//     });
// }, 10000);

$(document).on('mouseover', '.action-tooltip', function () {
    var tooltip = new PNotify({
        text: $(this).find('.action-tooltip-content').html().trim().replace(/(?:\r\n|\r|\n)/g, ''),
        hide: false,
        width: '30%',
        addclass: 'custom-action-user',
        buttons: {
            closer: false,
            sticker: false
        },
        history: {
            history: false
        },
        animate_speed: 'fast',
        icon: '',
        // Setting stack to false causes PNotify to ignore this notice when positioning.
        stack: false,
        auto_display: false
    });
    tooltip.open();

    $(document).on('mousemove', '.action-tooltip', function (event) {
        tooltip.get().css({'top': event.clientY + 12, 'left': event.clientX + 12});
    });

    $(document).on('mouseout', '.action-tooltip', function () {
        tooltip.remove();
    });
});
$(document).ready(function () {
    // $('.add-comment-form').on('beforeSubmit', function (e) {
    //     e.preventDefault();
    //     if ($(this).find('.has-error').length === 0) {
    //         var commentText = $(this).find('#comments-content')[0].value;
    //         var commentActionID = $(this).find('#comments-action_id')[0].value;
    //         if (commentText.length !== 0) {
    //             $.ajax({
    //                 url: $(this).attr('data-url'),
    //                 type: 'POST',
    //                 data: {
    //                     'Comments': {
    //                         'content': commentText,
    //                         'action_id': commentActionID
    //                     }
    //                 },
    //                 success: function (response) {
    //                     result = JSON.parse(response);
    //                     if (result.html.length !== 0) {
    //                         $('#comments-' + commentActionID).append(result.html).show('slow');
    //                         $('#modal-comments-' + commentActionID).append(result.html).show('slow');
    //                         document.getElementById('add-comment-form-' + commentActionID).reset();
    //                     }
    //                 },
    //                 error: function (request, status, error) {
    //                     window.alert(error);
    //                 }
    //             });
    //         }
    //     }
    //     return false;
    // }).submit(function (e) {
    //     e.preventDefault();
    // });
});

function addCommentAJAX(_this) {
    if ($(_this).find('.has-error').length === 0) {
        var commentText = $(_this).find('#comments-content')[0].value;
        var commentActionID = $(_this).find('#comments-action_id')[0].value;
        if (commentText.length !== 0) {
            console.log(_this);
            //todo: execute twice ?!?! add loading overlay and check if active
            $.ajax({
                url: $(_this).attr('data-url'),
                type: 'POST',
                data: {
                    'Comments': {
                        'content': commentText,
                        'action_id': commentActionID
                    }
                },
                success: function (response) {
                    result = JSON.parse(response);
                    if (result.html.length !== 0) {
                        $('#comments-' + commentActionID).append(result.html).show('slow');
                        $('#modal-comments-' + commentActionID).append(result.html).show('slow');
                        document.getElementById('add-comment-form-' + commentActionID).reset();
                    }
                },
                error: function (request, status, error) {
                    window.alert(error);
                }
            });
        }
    }
    return false;
}

function addReplyAJAX(_this) {
    // if ($(_this).find('.has-error').length === 0) {
    //     var commentText = $('#add-comment-reply-form-" . $modalClass . $comment->id . " #comments-content');
    //     var commentActionID = $('#add-comment-reply-form-" . $modalClass . $comment->id . " #comments-action_id');
    //     var commentReplyID = " . $comment->id . ";
    //     if (commentText[0].value.length != 0) {
    //         $.ajax({
    //             url: '" . Url::to([' / add - comment']) . "',
    //             type: 'POST',
    //             data: {
    //                 'Comments': {
    //                     'content': commentText[0].value,
    //                     'action_id': commentActionID[0].value,
    //                     'reply_id': commentReplyID
    //                 }
    //             },
    //             success: function (response) {
    //                 result = JSON.parse(response);
    //                 if (result.html.length != 0) {
    //                     $('#modal-comments-reply-" . $comment->id . "').append(result.html).show('slow');
    //                     $('#comments-reply-" . $comment->id . "').append(result.html).show('slow');
    //                     document.getElementById('add-comment-reply-form-" . $modalClass . $comment->id . "').reset();
    //                 }
    //             },
    //             error: function (request, status, error) {
    //                 window.alert(error);
    //             }
    //         });
    //     }
    // }
    // return false;
}