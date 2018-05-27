/**
 * Created by andrei on 16/08/2017.
 */


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
    if (!guest) {
        socket.on('users', function (request) {
            var chat_users = Object.keys(request.users).length;
            if (chat_users) {
                if ($('nav.navbar ul.navbar-nav li#chat-button > a > span.badge-notify').length) {
                    $('nav.navbar ul.navbar-nav li#chat-button > a > span.badge-notify').html(Object.keys(request.users).length);
                } else {
                    $('nav.navbar ul.navbar-nav li#chat-button > a').append('<span class="badge badge-notify">' + Object.keys(request.users).length + '</span>');
                }
            } else {
                if ($('nav.navbar ul.navbar-nav li#chat-button > a > span.badge-notify').length) {
                    $('nav.navbar ul.navbar-nav li#chat-button > a > span.badge-notify').remove();
                }
            }
        });
        socket.emit('users');

        socket.on('notification', function (request) {
            if (request.from === model_user.id || request.to === model_user.id) {
                $.ajax({
                    url: 'notifications',
                    type: 'GET',
                    success: function (response) {
                        result = JSON.parse(response);
                        if (result.friend_requests.length !== 0 && $('#friend-requests-dropdown').length) {
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

                            $('.navbar-nav ul.dropdown-menu li.friend-request img').on('click', function (event) {
                                $('body .loading').addClass('active');
                                location.replace($(this).attr('data-url'));
                            });
                        }
                        if (result.notifications.length !== 0 && $('#notifications-dropdown').length) {
                            var notifications_button = $('#notifications-dropdown').find('a.dropdown-toggle');
                            if (result.notifications.count !== "0") {
                                notifications_button.find('.fa').addClass('faa-ring animated');
                                notifications_button.find('.badge-notify')[0].innerHTML = result.notifications.count;
                                notifications_button.find('.badge-notify').removeClass('hidden');
                            } else {
                                notifications_button.find('.fa').removeClass('faa-ring animated');
                                notifications_button.find('.badge-notify')[0].innerHTML = result.notifications.count;
                                notifications_button.find('.badge-notify').addClass('hidden');
                            }
                            $('#notifications-dropdown').find('.dropdown-menu')[0].innerHTML = result.notifications.html;

                            $('.navbar-nav ul.dropdown-menu li.notification img').on('click', function (event) {
                                $('body .loading').addClass('active');
                                location.replace($(this).attr('data-url'));
                            });
                        }
                    },
                    error: function (request, status, error) {
                        console.log(error);
                    }
                });
            }
        });
    }

    socket.on('comment', function (request) {
        if (request.html.length !== 0) {
            $(request.element).append(request.html).show('slow');
            $(request.modal_element).append(request.modalHtml).show('slow');
            if (guest) {
                $('form.add-comment-reply-form').remove();
                $(request.element).find('#comment-' + request.comment.id + ' .comment-buttons').remove();
                $(request.modal_element).find('#comment-' + request.comment.id + ' .comment-buttons').remove();
            } else if (model_user.id !== request.comment.user_id) {
                $(request.element).find('#comment-' + request.comment.id + ' .comment-buttons').remove();
                $(request.modal_element).find('#comment-' + request.comment.id + ' .comment-buttons').remove();
            }
        }
        checkTyping();
    });

    socket.on('remove-comment', function (request) {
        $(request.element).animate({height: 'toggle'});
        $(request.modal_element).animate({height: 'toggle'});
    });

    socket.on('remove-action', function (request) {
        $(request.element).animate({height: 'toggle'});
    });

    $('.navbar-nav ul.dropdown-menu').on('click', function (event) {
        event.stopPropagation();
    });
    $('.navbar-nav ul.dropdown-menu li.friend-request img').on('click', function (event) {
        $('body .loading').addClass('active');
        location.replace($(this).attr('data-url'));
    });
    $('.navbar-nav ul.dropdown-menu li.notification img').on('click', function (event) {
        $('body .loading').addClass('active');
        location.replace($(this).attr('data-url'));
    });
    $('.navbar-nav li#notifications-dropdown').on('click', function (event) {
        if ($(this).find('.badge-notify').html() !== '0') {
            $(this).find('.badge-notify').addClass('hidden').html(0);

            $.ajax({
                url: 'notifications/read',
                type: 'GET',
                success: function (response) {
                    result = JSON.parse(response);
                },
                error: function (request, status, error) {
                    console.log(error);
                }
            });
        }
    });

    var back = ["#22A7F0", "#8E44AD", "#AEA8D3", "#F62459", "#DB0A5B", "#D64541", "#D2527F", "#2C3E50", "#1E8BC3", "#87D37C", "#4ECDC4", "#3FC380", "#E87E04", "#F9690E", "#F9BF3B"];

    $('.profile-heading').each(function () {

        // First random color
        var rand1 = back[Math.floor(Math.random() * back.length)];
        // Second random color
        var rand2 = back[Math.floor(Math.random() * back.length)];

        var grad = $(this);

        // Convert Hex color to RGB
        function convertHex(hex, opacity) {
            hex = hex.replace('#', '');
            r = parseInt(hex.substring(0, 2), 16);
            g = parseInt(hex.substring(2, 4), 16);
            b = parseInt(hex.substring(4, 6), 16);

            // Add Opacity to RGB to obtain RGBA
            result = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity / 100 + ')';
            return result;
        }

        // Gradient rules
        grad.css('background-color', convertHex(rand1, 40));
        grad.css("background-image", "-webkit-gradient(linear, left top, left bottom, color-stop(0%," + convertHex(rand1, 40) + "), color-stop(100%," + convertHex(rand2, 40) + "))");
        grad.css("background-image", "-webkit-linear-gradient(top,  " + convertHex(rand1, 40) + " 0%," + convertHex(rand2, 40) + " 100%)");
        grad.css("background-image", "-o-linear-gradient(top, " + convertHex(rand1, 40) + " 0%," + convertHex(rand2, 40) + " 100%)");
        grad.css("background-image", "-ms-linear-gradient(top, " + convertHex(rand1, 40) + " 0%," + convertHex(rand2, 40) + " 100%)");
        grad.css("background-image", "linear-gradient(to bottom, " + convertHex(rand1, 40) + " 0%," + convertHex(rand2, 40) + " 100%)");
        grad.css("filter", "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + convertHex(rand1, 40) + "', endColorstr='" + convertHex(rand2, 40) + "',GradientType=0 )");
    });

    $(window).on('resize scroll load shown.bs.modal', function () {
        checkTyping();
    });


    $('.modal').on('scroll ', function () {
        checkTyping();
    });

    $.fn.isInViewport = function () {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();

        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();

        return elementBottom > viewportTop && elementTop < viewportBottom;
    };
});

function checkTyping() {
    $.each($('.type-wrap'), function (key, type_wrap) {
        $(type_wrap).find('.typed-strings')[0].id = 'typed_strings_' + key;
        $(type_wrap).find('.typed')[0].id = 'typed_' + key;
        if ($(type_wrap).isInViewport() && $(type_wrap).find('.typed-cursor').length === 0 && $(type_wrap).find('.typed')[0].innerHTML.length === 0) {
            new Typed('#typed_' + key, {
                stringsElement: '#typed_strings_' + key,
                typeSpeed: 40,
                onComplete: function (self) {
                    $(type_wrap).find('.typed-cursor').remove()
                }
            });
        }
    });
}

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

            socket.emit('notification', {
                from: model_user.id,
                to: parseInt($(_this).attr('data-user_id'))
            });
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

                    socket.emit('notification', {
                        from: model_user.id,
                        to: parseInt($(_this).attr('data-user_id'))
                    });
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

                socket.emit('notification', {
                    from: model_user.id,
                    to: parseInt($(_this).attr('data-user_id'))
                });
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

                socket.emit('notification', {
                    from: model_user.id,
                    to: parseInt($(_this).attr('data-user_id'))
                });
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

                socket.emit('notification', {
                    from: model_user.id,
                    to: parseInt($(_this).attr('data-user_id'))
                });
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
                        // $('#comments-' + commentActionID).append(result.html).show('slow');
                        // $('#modal-comments-' + commentActionID).append(result.modalHtml).show('slow');
                        _this.reset();
                    }
                    $('body .loading').removeClass('active');
                    // checkTyping();

                    result.element = '#comments-' + commentActionID;
                    result.modal_element = '#modal-comments-' + commentActionID;
                    socket.emit('comment', result);

                    socket.emit('notification', {
                        from: model_user.id,
                        to: parseInt(result.action.user_id)
                    });
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
                        // $('#modal-comments-reply-' + commentReplyID).append(result.html).show('slow');
                        // $('#comments-reply-' + commentReplyID).append(result.modalHtml).show('slow');
                        _this.reset();
                        $('body .loading').removeClass('active');
                        // checkTyping();

                        result.element = '#comments-reply-' + commentReplyID;
                        result.modal_element = '#modal-comments-reply-' + commentReplyID;
                        socket.emit('comment', result);

                        socket.emit('notification', {
                            from: model_user.id,
                            to: parseInt(result.action.user_id)
                        });
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