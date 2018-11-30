  //====================//
  // helper functions   //
  //====================//
  function urlParam(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null) {
       return null;
    }
    return decodeURI(results[1]) || 0;
}
function showTab(tab) {
    var atab = 'a[href="#' + tab + '"]';
    $(atab).tab('show');
}

            //===========================================//
            //          Page Control                     //
            //===========================================//
            $(document).ready( function() {
                initPage();
            });
            function initPage() {
                var fn = $('#content').data('page');
                var pg = w_root + 'core/pages/' + fn + '.php';
                $('#content').load(pg, function() {
                    if (fn == 'map') {
                        loadMap();
                    } else if (fn == 'mail') {
                        loadMail();
                    } else if (fn == 'trading') {
                        loadTrading();
                    }
                });
            }
            $(document).on('content-change', function () {
                initPage();
            });
            //===========================================//
            //            Auth Modal                     //
            //===========================================//

$(document).ready(function () {
            // structuring auth-modal
            $('.register-row').hide();
            // password verification userside
            $('form').each(function () {
                var pass = $(this).find('input[name="password"]'),
                    confirmpass = $(this).find('input[name="confirmpassword"]'),
                    pattern = "[A-Za-z\d!$%@#£€*?&]";
                pass.focusout(function () {
                    val = pass.val();
                    err = '';
                    if (!val.match(/[A-Z]/g)) {
                        err += 'Password must contain a capital letter\n';
                    } else
                    if (!val.match(/[a-z]/g)) {
                        err += 'Password must contain a lowercase letter\n';
                    }
                    if (!val.match(/\d/g)) {
                        err += 'Password must contain a number';
                    }
                    remainder = val.replace(/[A-Za-z\d!$%@#£€*?&]+/g, "");
                    if (remainder.length >= 1) {
                        err += remainder + " are invalid characters.";
                    }
                    if (err != '') {
                        pass.get(0).setCustomValidity(err);
                        pass.closest('i').css('color', 'red');

                        pass.parent().removeClass('valid');
                        pass.parent().addClass('invalid');
                    } else {
                        pass.get(0).setCustomValidity('');
                        pass.closest('i').css('color', 'green');

                        pass.parent().removeClass('invalid');

                        pass.parent().addClass('valid');
                    }
                });
                pass.keyup(function() {
                    val = pass.val();
                    err = '';
                    confirmpass.attr('pattern', val);
                });
                confirmpass.focusout(function() {
                    if ($(this).val() != pass.val()) {
                        $(this).get(0).setCustomValidity('Passwords must match');
                        $(this).closest('i').css('color', 'red');
                        $(this).parent().removeClass('valid');
                        $(this).parent().addClass('invalid');
                    } else {
                        $(this).get(0).setCustomValidity('');
                        $(this).closest('i').css('color', 'green');
                        $(this).parent().removeClass('invalid');

                        $(this).parent().addClass('valid');
                    }
                });
                });
            // register/login switch
            $('#registerswitch').on('click', function () {
                $('.login-row').fadeOut("fast", function () {
                    $('.register-row').fadeIn("fast");
                    return false;
                });
            });

            $('#loginswitch').on('click', function () {
                $('.register-row').fadeOut("fast", function () {
                    $('.login-row').fadeIn("fast");
                    return false;
                });
            });
            // create blur effect
            $('#auth-modal').on('show.bs.modal', function () {
                $('#menu-wrapper *, #content').not('#auth-modal-container, #auth-modal-container *').addClass('blur');
                $('body').removeClass('wsactive');
            });
            //login submit
            $('#loginform').submit(function (event) {
                event.preventDefault();
                $('#discordloginbody').toggleClass('flex-hide');
                $('.email-modal').toggleClass('flex-expand');
                $('.email-modal > .modal-title').text('Spotamon is securely logging you in');
                $("body").removeClass('wsactive');
                $('#emailloginbody *, #discordloginbody *').not('.email-modal > .modal-title').hide();
                var data = $('#loginform').serialize();
                var auth = $('#loginform').attr('action');
                $.post(auth, data, function (data) {
                    if (data == 'true') {
                        $('#menu-container').load(w_root + 'core/pages/parts/nav.php').fadeIn('slow', function () {
                            $('.blur').removeClass('blur');
                            page = $('#content').attr('data-page');
                            loadurl = w_root + 'core/pages/' + page + '.php';
                            $('#content').load(loadurl, function() {
                                $(document).trigger('content-change');
                            });
                            $('#auth-modal').modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        });
                    } else {
                        $('#login-error').show().html(data);
                        $('#menu-container').load(w_root + 'core/pages/parts/nav.php').fadeIn('slow', function () {
                            $('.blur').removeClass('blur');
                        });
                    }
                });
            });
            // register submit
            $('#registerform').submit(function (event) {
                event.preventDefault();
                $('#discordregisterbody').toggleClass('flex-hide');
                $('.email-modal').toggleClass('flex-expand');
                $('.email-modal > .modal-title').text('Securely Registering with Spotamon');
                $('.discord-modal > .modal-title').hide();
                $('#registerform').hide();
                $("body").removeClass('wsactive');
                $('.register-switch').hide();
                $('.login-fields').hide();

                var data = $('#registerform').serialize();
                var auth = $('#registerform').attr('action');
                $.post(auth, data, function (data) {
                    if (data == 'true') {
                        $('#menu-container').load(w_root + 'core/pages/parts/nav.php').fadeIn('slow', function () {
                            $('.blur').removeClass('blur');
                            page = $('#content').data('page');
                            loadurl = w_root + 'core/pages/' + page + '.php';
                            $('#content').load(loadurl, function() {
                                $(document).trigger('content-change');
                            });
                            $('#auth-modal').modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        });
                    } else {
                        $('#login-error').show().html(data);
                        $('#menu-container').load(w_root + 'core/pages/parts/nav.php').fadeIn('slow', function () {
                            $('.blur').removeClass('blur');
                        });
                    }
                });
            });
            //discord login/register
            $('.discord-link').click(function (event) {
                event.preventDefault();
                $('.discord-modal').toggleClass('flex-expand');
                $('.email-modal').toggleClass('flex-hide');
                $('.email-modal').find('*').hide();
                var path = $(this).attr('href');
                var options = {
                    path: path
                }
                $.oauth2popup(options);
            });
            //oauth popup
            (function ($) {
                //  inspired by DISQUS
                $.oauth2popup = function (options) {
                    if (!options || !options.path) {
                        throw new Error("options.path must not be empty");
                    }
                    options = $.extend({
                        windowName: 'ConnectWithOAuth' // should not include space for IE
                            ,
                        windowOptions: 'location=0,menubar=no,titlebar=no,toolbar=no,channelmode=yes,directories=no,status=0,width=500,height=600',
                        callback: function () {
                            var page = $('#content').attr('data-page');
                            if (page == 'home') {
                                $('#menu-container').load('core/pages/parts/nav.php').fadeIn('slow');
                            } else {
                                window.location.reload();
                            }
                        }
                    }, options);

                    var oauthWindow = window.open(options.path, options.windowName, options.windowOptions);
                    var oauthInterval = window.setInterval(function () {
                        if (oauthWindow.closed) {
                            window.clearInterval(oauthInterval);
                            options.callback();
                        }
                    }, 1000);
                };
                //bind to element and pop oauth when clicked
                $.fn.oauth2popup = function (options) {
                    $this = $(this);
                    $this.click($.oauth2popup.bind(this, options));
                };
            })(jQuery);

            //feedback form
            $('#feedback-form').submit(function(e) {
                e.preventDefault();
                var form = $('#feedback-form'),
                    email = form.find('input[name="email"]').val(),
                    subject = form.find('input[name="subject"]').val(),
                    message = form.find('input[name="message"]').val();
                var newMessage = message + '\nContact Email: ' + email;
                $.post(w_root + 'core/functions/mail.php', {action:'send', subject:subject, message:newMessage, to_user:'admin', CSRF:csrf}, function(data) {
                    if (data == 'true') {
                        form.hide();
                        $('#feedback-title').text('Feedback Sent');
                    } else {
                        alert('error in sending feedback, please try again.   If problems persist please contact the website administrator');
                    }
                });
            });


        });
            //========================================//
            //               Map                      //
            //========================================//
            function loadMap() {
                initMap();
            //gym selects
            $('#content').on('click', '#pickInstinct', function (event) {
                event.preventDefault();
                var gym = $(this).attr('data-gym');
                $.post(w_root + "core/pages/gymteam.php", {
                    gname: gym,
                    tname: 2
                }, function (data) {
                    result = data.replace(/\r\n/g, "");
                    if (result == "Inserted") {
                        var id = '#' + gym,
                            mId = '.marker' + gym;
                        $(id).find('img').first().fadeTo(1000, 0.30, function () {
                            $(this).attr('src', '/core/assets/gyms/2.png');
                            $(id).find('p').first().text('Team: Instinct');
                            $(mId).attr('src', '/core/assets/gyms/2.png');
                        }).fadeTo(500, 1);
                        $(".gympop").removeClass("valortrace mystictrace").delay(500).queue(function () {
                            $(".gympop").addClass("instincttrace");
                            $('.leaflet-popup-tip').css({
                                'background-color': '#ffff00',
                                'transition': 'background-color 1.5s ease-out 2s'
                            }).dequeue();
                        });
                    }
                });
            });

                $('#content').on('click', '#pickMystic', function (event) {
                    event.preventDefault();
                    var gym = $(this).attr('data-gym');
                    $.post(w_root + "core/pages/gymteam.php", {
                        gname: gym,
                        tname: 4
                    }, function (data) {
                        result = data.replace(/\r\n/g, "");
                        if (result == "Inserted") {
                            var id = '#' + gym,
                                mId = '.marker' + gym;
                            $(id).find('img').first().fadeTo(1000, 0.30, function () {
                                $(this).attr('src', '/core/assets/gyms/4.png');
                                $(id).find('p').first().text('Team: Mystic');
                                $(mId).attr('src', '/core/assets/gyms/4.png');
                            }).fadeTo(500, 1);
                            $('.gympop').removeClass("instincttrace valortrace").delay(500).queue(function () {
                                $(".gympop").addClass("mystictrace");
                                $('.leaflet-popup-tip').css({
                                    'background-color': '#0011ff',
                                    'transition': 'background-color 1.5s ease-out 2s'
                                }).dequeue();
                            });
                        }
                    });
                });
                $('#content').on('click', '#pickValor', function (event) {
                    event.preventDefault();
                    var gym = $(this).attr('data-gym');
                    $.post(w_root + "core/pages/gymteam.php", {
                        gname: gym,
                        tname: 3
                    }, function (data) {
                        result = data.replace(/\r\n/g, "");
                        if (result == "Inserted") {
                            var id = '#' + gym,
                                mId = '.marker' + gym;
                            $(id).find('img').first().fadeTo(1000, 0.30, function () {
                                $(this).attr('src', '/core/assets/gyms/3.png');
                                $(id).find('p').first().text('Team: Valor');
                                $(mId).attr('src', '/core/assets/gyms/3.png');
                            }).fadeTo(500, 1);
                            $(".leaflet-popup").removeClass("instincttrace mystictrace").delay(500).queue(function () {
                                $(".gympop").addClass("valortrace");
                                $('.leaflet-popup-tip').css({
                                    'background-color': '#ff0000',
                                    'transition': 'background-color 1.5s ease-out 2s'
                                }).dequeue();
                            });
                        }
                    });
                });
            }

                //================================//
                //            Trading             //
                //================================//
            function loadTrading() {
                $('#content').on('submit', 'form#usersubmit', function (event) {
                    event.preventDefault();
                    var data = $('#usersubmit').serialize();
                    var auth = $('#usersubmit').attr('action');
                    $.post(auth, data, function (data) {
                        result = $.trim(data);
                        if (result == 'true') {
                            $('#content').attr('data-page', 'active-trades', function () {
                                $(document).trigger('content-change');
                            });
                        } else {
                            alert(result);
                        }
                    });
                });
                // end on document ready
        }

            //============================//
            //         Mail Pages         //
            //============================//

var boxes = ['inbox', 'outbox', 'trash'],
    list = {
        inbox:null,
        outbox:null,
        trash:null,
    },
    since = {
        outbox:null,
        inbox:null,
        trash:null,
    },
    startBox = null;
function loadMail() {
    $(document).ready(function() {
    var startBox = urlParam('box'),
        compose = urlParam('compose'),
        to = urlParam('to');
    if (startBox == null) {
        startbox = 'inbox';
        }
    $('a[href="#'+startBox+'"]').tab('show');
    if (compose == 'true') {
        $('#compose-modal').modal('show');
    }
    if (to != null) {
        $('#to_user').val(to);
    }
});


    $(document).on('show.bs.tab', function(e) {
        var mail = $(e.target).attr('href');
        $('#active-tab').attr('data-target', mail);
        $('#active-tab').attr('href', mail);
        var box = mail.replace('#', '');
        $('#active-tab').text(box);
        $(':checked').each(function() {
            $(this).prop('checked', false);
        });
        updateList(box);
});


    $('#active-tab').click(function(e) {
        e.preventDefault();
        $(this).parent().addClass('active');
        $('#conversation-tab').parent().removeClass('active show');
        tab = 'a[href="' + $(this).attr('href') + '"]';
        $(tab).tab('show');
    });


    function createLists(callback) {
        var inboxOptions = {
            valueNames : [
                { data:["id"]},
                { data:['unread']},
                { attr: "src", name: "avatar" },
                "from_user",
                "subject",
                "message",
                "sent"],
            item: `<li class="list-group-item id p-1 my-1" data-id="">
                        <div class="row m-0 p-0 d-flex">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" data-toggle="tooltip"  title="Select" class="form-check-input inbox-check rounded ml-1 my-auto" >
                            </div>
                                <img class="img avatar rounded-circle" alt="avatar" onerror="this.src='/core/assets/userpics/nopic.png';" src="" width="25px" height="25px">
                            <div class="from_user ml-2 my-0 mr-3 col-2 font-weight-bold"></div>
                            <div class="subject col-2 my-0 d-inline-block text-truncate mx-2"></div>
                                <div class="font-weight-light my-0 col-3 mess d-inline-block text-truncate text-secondary">
                                    <small class="message"></small>
                                </div>
                            <div class="ml-auto my-0 sent p-1 col-2 badge badge-pill badge-secondary"></div>
                            </div>
                            <div class="read-message row text-light m-0 p-1" style="display:none;">
                                <div class="col-10">
                                    <div class="read-message-text text-light m-0"></div>
                                </div>
                                <div class="col-2 ml-auto">
                                    <a href="#" class="message-delete"><i class="fas fa-trash-alt delete"></i></a>
                                    <a href="#" class="message-reply"><i class="fas fa-reply"></i></a>
                                </div>
                            </div>
                        </li>`,
        listClass: 'inbox-list',
        page: 7,
        pagination: {
            paginationClass: 'inbox-pagination'
        }
            };
            list.inbox = new List('inbox', inboxOptions);

            var outboxOptions = {
                valueNames : [
                    { data:["id"]},
                    "subject",
                    "to_user",
                    "message",
                    "sent",
                    { attr: "src", name: "avatar" }],
                item: `<li class="list-group-item id p-2 my-1 d-flex" data-id="">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input outbox-check rounded ml-1 my-auto"   value="selected">
                            </div>
                            <div class="col-1 ml-1 my-0">
                                <img class="img avatar mx-2 rounded-circle" alt="avatar"    onerror="this.src='/core/assets/userpics/nopic.png';" src="" width="25px" height="25px">
                            </div>
                            <div class="to_user ml-2 mr-3 col-2 font-weight-bold"></div>
                            <div class="subject col-2 d-inline-block text-truncate mx-2"></div>
                            <div class="font-weight-light col-3 mess d-inline-block text-truncate text-secondary">
                                <small class="message"></small>
                            </div>
                            <div class="ml-auto my-auto sent col-2 p-1 badge badge-pill badge-secondary"></div>
                        </li>`,
            listClass: 'outbox-list',
            page: 7,
            pagination: {
                paginationClass: 'outbox-pagination'
            }
                };
            list.outbox = new List('outbox', outboxOptions);

            var trashOptions = {
                valueNames : [
                    { data:["id"]},
                    "subject",
                    "tofrom",
                    "user",
                    "message",
                    "sent",
                    { attr: "src", name: "avatar" }],
                item: `<li data-toggle="list" class="list-group-item id p-2 my-1 d-flex message" data-id="">
                            <input type="checkbox" data-toggle="tooltip" title="Select"  class="form-check-input trash-check rounded ml-1" >
                            <div class="col-1 ml-1 my-0">
                                <div class="tofrom"></div>
                            </div>
                            <div class="col-1">
                                <img class="img avatar mx-2 rounded-circle" alt="avatar" onerror="this.src='/core/assets/userpics/nopic.png';" src="" width="25px" height="25px">
                            </div>
                            <div class="col-2">
                                <div class="user ml-2 mr-3 font-weight-bold"></div>
                            </div>
                            <div class="col-2">
                                <div class="subject d-inline-block text-truncate mx-2"></div>
                            </div>
                            <div class="font-weight-light col-2 mess d-inline-block text-truncate text-secondary">
                                <small class="message"></small>
                            </div>
                            <div class="col-2">
                            <div class="ml-auto my-auto sent p-1 badge badge-pill badge-secondary"></div>
                            </div>
                        </li>`,
            listClass: 'trash-list',
            page: 7,
            pagination: {
                paginationClass: 'trash-pagination'
            }
                };
                list.trash= new List('trash', trashOptions);
                callback();

    }
    createLists(function() {
        boxes.forEach(function(i) {
            updateList(i);
        });
    });

    function updateList(box) {
    if (boxes.includes(box) == false) {
        box = 'inbox';
    }
    $.get(w_root + 'core/functions/mail.php', {action:box, since:since[box]}, function(data) {
        var data = JSON.parse(data);
        list[box].add(data, function() {
            list[box].sort('sent', {order:'desc'});
    });
    var date = Math.round(+new Date()/1000);
    if (since[box] == null) {
        $('.'+box+'-pagination').prepend('<li class="page-item"><span class="page-link">Page: </span></li>');
        $('.page').addClass('page-link');
        $('.page').parent().addClass('page-item');
    }
    since[box] = date;
        });
        var unreadCount = $('li[data-unread="1"]').length.toString();
        $('.mcount').each(function() {
            $(this).text(unreadCount);
            });
        }
        $('#mailsearch').on('keyup', function() {
            var value = $(this).val();
            var box = $('#active-tab').text();
            list[box].search(value);
        });
$('.read-message').hide();
$('.tab-content').on('click', 'li.id', function(event) {
    if ($(event.target).is(':checkbox')) {
        return;
    } else {
    var box = $(this).closest('.tab-pane').prop('id');
    var itemId = $(this).data('id');
    var item = list[box].get('id', itemId)[0];
    var oldVal = item.values();
    if (oldVal.unread == 1) {
        item.values({
            unread: 0
        });
        $(this).data('unread', '0');
        $.post(w_root + 'core/functions/mail.php', {action:'read', id:itemId, CSRF:csrf});
    }
    var message = $(this).find('.message');
    message.toggle();
    var readMessage = $(this).find('.read-message');
    $(this).toggleClass('bg-secondary');
    $(this).find('.read-message-text').text(message.text());
    readMessage.toggle();
    }
});
$('#read-button').click(function() {
    var ids = []
    var box = $('#active-tab').text();
    var checked = $('#' + box).find(':checkbox:checked');
    checked.each(function() {
        var parent = $(this).closest('li'),
            id = parent.data('id');
        parent.data('unread', '0');
        ids.push(id);
    });
    readMail(ids);
});
$('#delete-button').click(function() {
    var ids = []
    var box = $('#active-tab').text();
    var checked = $('#' + box).find(':checkbox:checked');
    checked.each(function() {
        var parent = $(this).closest('li'),
            id = parent.data('id');
            parent.remove();
            ids.push(id);
    });
    deleteMail(ids);
});
$('#select-button').click(function(e) {
    e.preventDefault();
    var box = $('#active-tab').text();
    $('#' + box).find(':checkbox').each(function() {
        $(this).prop('checked', true);
    });
});
$('#send-button').click(function(e) {
    e.preventDefault();
    $('#compose-form').hide();
    var data = $('#compose-form').serialize(),
        action = $('#compose-form').attr('action');
    $('#send-header').text('Sending Message...');
    $.post(action, data, function(data) {
        if (data == 'true') {
            $('#send-header').text('Message Sent!');
            $(document).on('hide.bs.modal', function() {
                $('send-header').text('Send Message');
                $('#compose-form').show();
            });
        } else {
            alert('message send failure');
            $('compose-form').show();
        }
        updateList('inbox');
        updateList('outbox');
    });
});

function deleteMail(ids) {
    var box = $('#active-tab').text();
    $.each(ids, function(i, id) {
        if (box == 'inbox'){
            var item = list[box].get('id', id)[0];
            var values = item.values();
            list.inbox.remove('id', id);
            var trashItem = {
                id:id,
                subject:values.subject,
                tofrom:'from',
                user:values.from_user,
                message:values.message,
                sent:values.sent,
                avatar:values.avatar
            };
            list.trash.add(trashItem);
            } else if (box == 'outbox') {
                var item = list.outbox.get('id', id)[0];
                var values = item.values();
                list.outbox.remove('id', id);
                var trashItem = {
                    id:id,
                    subject:values.subject,
                    tofrom:'to',
                    user:values.to_user,
                    message:values.message,
                    sent:values.sent,
                    avatar:values.avatar
                };
                list.trash.add(trashItem);
            }
    });
    $.post(w_root + 'core/functions/mail.php', {action:'delete', id:ids, box:box, CSRF:csrf});
}

function readMail(ids) {
    $.each(ids, function(i, id) {
    var item = list[box].get('id', id)[0];
        item.values({
            unread: 0
        });
    });
    $.post(w_root + 'core/functions/mail.php', {action:'read', id:ids, CSRF:csrf});
}
$('.tab-content').on('click', '.message-delete', function() {
    var ids = [];
    var id = $(this).closest('li').data('id');
    $(this).closest('li').remove();
    ids.push(id);
    deleteMail(ids);
});
$('.tab-content').on('click', '.message-reply', function() {
    var message = $(this).closest('li');
    var to = message.find('.from_user').text();
    $('#compose-modal').modal('show');
    $('#to_user').val(to);
});
setInterval(function() {
    var box = $('#active-tab').text();
    updateList(box);
}, 5000);
}


