$(document).ready(function () {
    // get locations for spots

    $('.register-row').hide();



    $("#regconfirmpass").focus(function () {
        $('#regconfirmpass').prop('pattern', $('#regpass').val());
        $('#regconfirmpass').attr('oninvalid', 'this.setCustomValidity("Passwords must Match")');
    });


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

    $('#auth-modal').on('show.bs.modal', function () {
        $('#menu-wrapper *, #content').not('#auth-modal-container, #auth-modal-container *').addClass('blur');
        $('body').removeClass('wsactive');
    });

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
                $('#menu-container').load(w_root + 'core/pages/parts/nav.php').fadeIn('slow', function(){
                    $('.blur').removeClass('blur');
                    page = $('#content').attr('data-page');
                    if (page == 'map') {
                        $.getScript("/core/js/leaflet.js");
                    }
                    loadurl = w_root + 'core/pages/' + page + '.php';
                    $('#content').load(loadurl);
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
                $('#menu-container').load(w_root + 'core/pages/parts/nav.php').fadeIn('slow', function(){
                    $('.blur').removeClass('blur');
                    page = $('#content').data('page');
                    loadurl = w_root + 'core/pages/' + page + '.php';
                    $('#content').load(loadurl);
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

    $('#content').on('click', '#pickInstinct', function(event) {
        event.preventDefault;
        var gym = $(this).attr('data-gym');
        $.post(w_root + "core/pages/gymteam.php", {gname:gym, tname:2}, function(data) {
            result = data.replace(/\r\n/g, "");
            if (result == "Inserted") {
                var id = '#' + gym,
                    mId = '.marker' + gym;
                $(id).find('img').first().fadeTo(1000,0.30, function() {
                    $(this).attr('src', '/core/assets/gyms/2.png');
                    $(id).find('p').first().text('Team: Instinct');
                    $(mId).attr('src', '/core/assets/gyms/2.png');
            }).fadeTo(500,1);
        }
        });
    });

    $('#content').on('click', '#pickMystic', function(event) {
        event.preventDefault;
        var gym = $(this).attr('data-gym');
        $.post(w_root + "core/pages/gymteam.php", {gname:gym, tname:4}, function(data) {
            result = data.replace(/\r\n/g, "");
            if (result == "Inserted") {
                var id = '#' + gym,
                    mId = '.marker' + gym;
                $(id).find('img').first().fadeTo(1000,0.30, function() {
                    $(this).attr('src', '/core/assets/gyms/4.png');
                    $(id).find('p').first().text('Team: Valor');
                    $(mId).attr('src', '/core/assets/gyms/4.png');
            }).fadeTo(500,1);
        }
        });
    });
    $('#content').on('click', '#pickValor', function(event) {
        event.preventDefault;
        var gym = $(this).attr('data-gym');
        $.post(w_root + "core/pages/gymteam.php", {gname:gym, tname:3}, function(data) {
            result = data.replace(/\r\n/g, "");
            if (result == "Inserted") {
                var id = '#' + gym,
                    mId = '.marker' + gym;
                $(id).find('img').first().fadeTo(1000,0.30, function() {
                    $(this).attr('src', '/core/assets/gyms/3.png');
                    $(id).find('p').first().text('Team: Mystic');
                    $(mId).attr('src', '/core/assets/gyms/3.png');
            }).fadeTo(500,1);
        }
        });
    });
});
$(window).on('load', function() {
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '<?=$analytics?>');
});




