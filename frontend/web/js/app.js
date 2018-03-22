/**
 * Created by andrei on 16/08/2017.
 */

// $.ajax({
//     url: window.location.origin + '/index.php/site/notifications',
//     type: "POST",
//     success: function (data) {
//         var messages = jQuery.parseJSON(data);
//         if (messages.length !== 0) {
//             $.each(messages, function (index, message) {
//                 var notification = new Notification(message.title, {
//                     body: message.body,
//                     icon: message.icon,
//                     requireInteraction: true
//                 });
//                 notification.onclick = function (event) {
//                     window.open(message.url, '_blank');
//                 }
//             });
//         }
//     }
// });
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

setInterval(function () {
    $.ajax({
        url: 'notifications',
        type: 'GET',
        success: function (response) {
            result = JSON.parse(response);
            if (result.friend_requests.length !== 0) {
                var friend_requests_button = $('#friend-requests-dropdown').find('a.dropdown-toggle');
                if (result.friend_requests.count !== "0") {
                    friend_requests_button.find('.fa').addClass('faa-pulse animated');
                    friend_requests_button.find('.badge-notify')[0].innerHTML = result.friend_requests.count;
                    friend_requests_button.find('.badge-notify').removeClass('hidden');
                } else {
                    friend_requests_button.find('.fa').removeClass('faa-pulse animated');
                    friend_requests_button.find('.badge-notify')[0].innerHTML = result.friend_requests.count;
                    friend_requests_button.find('.badge-notify').addClass('hidden');
                }
                $('#friend-requests-dropdown').find('.dropdown-menu')[0].innerHTML = result.friend_requests.html;
            }
        },
        error: function (request, status, error) {
            console.log(error);
        }
    });
}, 10000);

$(document).on('mouseover', '.action-tooltip', function () {
    var tooltip = new PNotify({
        text: $(this).find('.action-tooltip-content').html().trim().replace(/(?:\r\n|\r|\n)/g, ''),
        hide: false,
        width: '30%',
        addclass: 'custom-action-user action-tooltip-layout',
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

$(document).mousemove(function (e) {
    if ($(e.target).closest('.action-tooltip').length === 0) {
        $('.ui-pnotify.action-tooltip-layout').remove();
    }
});

$(document).ready(function () {
    $('.navbar-nav ul.dropdown-menu').on('click', function (event) {
        event.stopPropagation();
    });
    $('.navbar-nav ul.dropdown-menu li.friend-request img').on('click', function (event) {
        $('body .loading').addClass('active');
        location.replace($(this).attr('data-url'));
    });
});

function addFriendAJAX(_this) {
    $('body .loading').addClass('active');
    $.ajax({
        url: 'add-friend',
        type: 'POST',
        data: {
            'Friendship': {
                'user_to': $(_this).attr('data-user_id')
            }
        },
        success: function (response) {
            result = JSON.parse(response);
            if (result.html.length !== 0) {
                $('.profile-button').replaceWith(result.html);
                $('nav.navbar#w0').replaceWith(result.navbar);
                var parent = $(".navbar-nav span[data-user_id='" + $(_this).attr('data-user_id') + "']").parent();
                if (parent.length) {
                    parent.find('span').remove();
                    parent.append('<span class="fa fa-times-circle-o fa-2x" data-user_id="' + $(_this).attr('data-user_id') + '" onclick="return removeFriendAJAX(this);"></span>');
                }
            }
            $('body .loading').removeClass('active');
        },
        error: function (request, status, error) {
            window.alert(error);
            $('body .loading').removeClass('active');
        }
    });
};

function removeFriendAJAX(_this) {
    if ($(_this).closest('.navbar-nav').length) {
        // requested from navbar dropdown
        $.ajax({
            url: 'delete-friend',
            type: 'GET',
            data: {
                'user_id': $(_this).attr('data-user_id')
            },
            success: function (response) {
                if (response) {
                    result = JSON.parse(response);
                    var parent = $(_this).parent();
                    parent.find('span.fa').remove();
                    parent.append('<span data-user_id="' + $(_this).attr('data-user_id') + '" style="margin-top: 0">Removed</span>');
                    if ($(".profile-button[data-user_id='" + $(_this).attr('data-user_id') + "']").length) {
                        $('.profile-button').replaceWith(result.html);
                    }
                }
            },
            error: function (request, status, error) {
                window.alert(error);
            }
        });
    } else {
        // requested from user profile
        $('body .loading').addClass('active');
        $.ajax({
            url: 'delete-friend',
            type: 'GET',
            data: {
                'user_id': $(_this).attr('data-user_id')
            },
            success: function (response) {
                result = JSON.parse(response);
                if (result.html.length !== 0) {
                    $('.profile-button').replaceWith(result.html);
                    var parent = $(".navbar-nav span[data-user_id='" + $(_this).attr('data-user_id') + "']").parent();
                    parent.find('span.fa').remove();
                    parent.append('<span data-user_id="' + $(_this).attr('data-user_id') + '" style="margin-top: 0">Removed</span>');
                }
                $('body .loading').removeClass('active');
            },
            error: function (request, status, error) {
                window.alert(error);
                $('body .loading').removeClass('active');
            }
        });
    }
};

function acceptFriendAJAX(_this) {
    $.ajax({
        url: 'accept-friend',
        type: 'GET',
        data: {
            'user_id': $(_this).attr('data-user_id')
        },
        success: function (response) {
            if (response) {
                var parent = $(_this).parent();
                parent.find('span.fa').remove();
                parent.append('<span data-user_id="' + $(_this).attr('data-user_id') + '" style="margin-top: 0">Accepted</span>');
            }
        },
        error: function (request, status, error) {
            window.alert(error);
        }
    });
};

function refuseFriendAJAX(_this) {
    $.ajax({
        url: 'refuse-friend',
        type: 'GET',
        data: {
            'user_id': $(_this).attr('data-user_id')
        },
        success: function (response) {
            if (response) {
                var parent = $(_this).parent();
                parent.find('span.fa').remove();
                parent.append('<span data-user_id="' + $(_this).attr('data-user_id') + '" style="margin-top: 0">Refused</span>');
            }
        },
        error: function (request, status, error) {
            window.alert(error);
        }
    });
};

function addCommentAJAX(_this) {
    if ($(_this).find('.has-error').length === 0 && $('body .loading.active').length === 0) {
        var commentText = $(_this).find('#comments-content')[0].value;
        var commentActionID = $(_this).find('#comments-action_id')[0].value;
        if (commentText.length !== 0) {
            $('body .loading').addClass('active');
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
                        $('#modal-comments-' + commentActionID).append(result.modalHtml).show('slow');
                        _this.reset();
                    }
                    $('body .loading').removeClass('active');
                },
                error: function (request, status, error) {
                    window.alert(error);
                    $('body .loading').removeClass('active');
                }
            });
        }
    }
    return false;
}

function addReplyAJAX(_this) {
    if ($(_this).find('.has-error').length === 0 && $('body .loading.active').length === 0) {
        var commentText = $(_this).find('#comments-content')[0].value;
        var commentActionID = $(_this).find('#comments-action_id')[0].value;
        var commentReplyID = $(_this).attr('data-comment_id');
        if (commentText.length !== 0) {
            $('body .loading').addClass('active');
            $.ajax({
                url: $(_this).attr('data-url'),
                type: 'POST',
                data: {
                    'Comments': {
                        'content': commentText,
                        'action_id': commentActionID,
                        'reply_id': commentReplyID
                    }
                },
                success: function (response) {
                    result = JSON.parse(response);
                    if (result.html.length !== 0) {
                        $('#modal-comments-reply-' + commentReplyID).append(result.html).show('slow');
                        $('#comments-reply-' + commentReplyID).append(result.modalHtml).show('slow');
                        _this.reset();
                        $('body .loading').removeClass('active');
                    }
                },
                error: function (request, status, error) {
                    window.alert(error);
                    $('body .loading').removeClass('active');
                }
            });
        }
    }
    return false;
}