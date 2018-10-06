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
        $('#menu-container ~ div').addClass('blur');
        $('body').removeClass('wsactive');
    });

    $('#auth-modal').on('hide.bs.modal', function () {
        $('#auth-modal-container ~ div').removeClass('blur');
    });

    $('#loginform').submit(function (event) {
        event.preventDefault();
        $('#discordloginbody').toggleClass('flex-hide');
        $('.email-modal').toggleClass('flex-expand');
        $('.email-modal > .modal-title').text('Spotamon is securely logging you in');
        $('.discord-modal > .modal-title').hide();
        $('#loginform').hide();
        $("body").removeClass('wsactive');
        $('.login-fields').hide();
        $('#register-switch').hide();
        var data = $('#loginform').serialize();
        var auth = $('#loginform').attr('action');
        $.post(auth, data, function (data) {
            if (data == 'true') {
                $('#menu-container').load(w_root + 'core/pages/parts/nav.php', function () {
                    $.getScript(w_root + 'core/js/menu.js');
                }).fadeIn('slow').delay('1000', function () {
                    $('#auth-modal').modal('toggle');
                    $('.blur').removeClass('blur');
                });
            } else {
                $('#login-error').html(data);
                $('#menu-container').load(w_root + 'core/pages/parts/nav.php', function () {
                    $.getScript(w_root + 'core/js/menu.js');
                }).fadeIn('slow').delay('1000', function () {
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
        $('#register-switch').hide();
        $('.login-fields').hide();

        var data = $('#registerform').serialize();
        var auth = $('#registerform').attr('action');
        $.post(auth, data, function (data) {
            if (data == 'true') {
                $('#menu-container').load(w_root + 'core/pages/parts/nav.php').fadeIn('slow', function () {
                    $('#auth-modal').modal('toggle');
                    $('.blur').removeClass('blur');
                });
            } else {
                $('#register-error').html(data + '<br><a href="/index.php" class="btn m-auto">Retry</a>');
                $('.blur').removeClass('blur');
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


    $('#registerform').submit(function (event) {
        event.preventDefault();
        var data = $('#registerform').serialize();
        var auth = $('#registerform').attr('action');
        $.post(auth, data, function (data) {
            if (data == 'true') {
                $('#menu-container').load('core/pages/parts/nav.php').fadeIn('slow');
            } else {
                $('#register-error').html(data);
            }
        });
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


    $('a.Instinct').click(function (e) {
        e.preventDefault();
        $("form[name='postInstinct']").submits();
    });
    $('a.Valor').click(function (e) {
        e.preventDefault();
        $("form[name='postValor']").submit();
    });

    $('a.Mystic').click(function (e) {
        e.preventDefault();
        $("form[name='postMystic']").submit();
    });

});

