
    $(document).ready(function() {
        $('.register-row').hide();
    });

        
    
    //     $('#loginform').submit(function(event) {
    //         event.preventDefault();
    //         $.post($('#loginform').prop('action'), $('#loginform').serialize(), function(data) {
    //         var newbody = '<center style="display:block; height:100%;"><h2 style="top:45%; bottom:45%; position:relative;">Securely Logging You In</h2></center><br><div class="loader"></div>';
    //         $('#emailmodalbody').html(newbody);
    //         $('html').load('/index.php', function() {
    //             $('#auth-modal').toggle();
    //         });
    //     });
    // });    
    $("#regconfirmpass").focus(function() {
        $('#regconfirmpass').prop('pattern', $('#regpass').val());
        $('#regconfirmpass').attr('oninvalid', 'this.setCustomValidity("Passwords must Match")');
    });

    
    $('#registerswitch').on('click', function() {
        $('.login-row').fadeOut("fast", function(){
            $('.register-row').fadeIn("fast");
            return false;
        });
    });


    $('#loginswitch').on('click', function() {
        $('.register-row').fadeOut("fast", function() {
            $('.login-row').fadeIn("fast");
            return false;
        });
    });

    $('#auth-modal').on('show.bs.modal', function () {
        $('#auth-modal-container ~ div').addClass('blur');
        $('#menu-container').removeClass('blur');
        $('nav').addClass('blur');
     })
     
     $('#auth-modal').on('hide.bs.modal', function () {
        $('#auth-modal-container ~ div').removeClass('blur');
        $('nav').removeClass('blur');
     })


