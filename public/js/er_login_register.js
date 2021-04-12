window.onload = function () {
    render();
};

function render() {
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
    recaptchaVerifier.render();
}
(function ($) {
    $(".form")
        .find("input, textarea")
        .on("keyup blur focus", function (e) {
            var $this = $(this),
                label = $this.prev("label");

            if (e.type === "keyup") {
                if ($this.val() === "") {
                    label.removeClass("active highlight");
                } else {
                    label.addClass("active highlight");
                }
            } else if (e.type === "blur") {
                if ($this.val() === "") {
                    label.removeClass("active highlight");
                } else {
                    label.removeClass("highlight");
                }
            } else if (e.type === "focus") {
                if ($this.val() === "") {
                    label.removeClass("highlight");
                } else if ($this.val() !== "") {
                    label.addClass("highlight");
                }
            }
        });

    $(".tab a").on("click", function (e) {
        e.preventDefault();

        $(this).parent().addClass("active");
        $(this).parent().siblings().removeClass("active");

        target = $(this).attr("href");

        $(".tab-content > div").not(target).hide();

        $(target).fadeIn(600);
    });

    // PASWORD HIDDEN? SHOW
    $('.showpass').on('click', function () {
        let data = $(this).prev('input').attr('type');
        if (data == 'password') {
            $(this).prev('input').attr('type', 'text');
        } else {
            $(this).prev('input').attr('type', 'password');
        }
    });

    $('.signup').on('click', function (e) {
        e.preventDefault();
        let phone = $('.signup-number').val();
        let password = $('.signup-pass').val();

        if ($.isNumeric(phone)) {
            if (phone.length == 10) {
                if (phone != "" && password != "") {

                    //get the number
                    var number = '+880'+phone;

                    //phone number authentication function of firebase
                    //it takes two parameter first one is number,,,second one is recaptcha
                    firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function (confirmationResult) {
                        //s is in lowercase
                        window.confirmationResult = confirmationResult;
                        coderesult = confirmationResult;

                        $('.signupform').append('<span class="alert">Message sent</span>');
                        $('.alert').animate({'right': '8px'});
                        setTimeout(() => {
                            $('.alert').remove();
                        }, 4000);

                        $('.signup-form').hide();
                        $('.otp-code-form').show();
                        
                    }).catch(function (error) {
                        $('.signupform').append('<span class="alert">'+error.message+'</span>');
                        $('.alert').animate({'right': '8px'});
                        setTimeout(() => {
                            $('.alert').remove();
                        }, 4000);
                    });


                    $('.confirm').on('click', function (e) {
                        e.preventDefault();
                        var code = $('#verificationCode').value;
                        coderesult.confirm(code).then(function (result) {
                            $('.signupform').append('<span class="alert">Successfully registered</span>');
                            $('.alert').animate({ 'right': '8px' });
                            setTimeout(() => {
                                $('.alert').remove();
                            }, 4000);
                            var user = result.user;

                            $.ajax({
                                type: "post",
                                url: er_login_register.ajax_url,
                                data: {
                                    action: "register_access_need",
                                    number: number,
                                    password: password,
                                    security: er_login_register.security
                                },
                                Cache: false,
                                success: function (response) {
                            
                                }
                            });
                        });

                    }).catch(function (error) {
                        $('.signupform').append('<span class="alert">'+error.message+'</span>');
                        $('.alert').animate({'right': '8px'});
                        setTimeout(() => {
                            $('.alert').remove();
                        }, 4000);;
                    });
                    
                } else {
                    $('.signupform').append('<span class="alert">Don\'t leave empty!</span>');
                    $('.alert').animate({'right': '8px'});
                    setTimeout(() => {
                        $('.alert').remove();
                    }, 4000);
                    e.preventDefault();
                }
                
            } else {
                $('.signupform').append('<span class="alert">Type 10 digits of your number!</span>');
                $('.alert').animate({'right': '8px'});
                setTimeout(() => {
                    $('.alert').remove();
                }, 4000);
                e.preventDefault();
            }
        } else {
            $('.signupform').append('<span class="alert">Only Number allowed!</span>');
            $('.alert').animate({'right': '8px'});
            setTimeout(() => {
                $('.alert').remove();
            }, 4000);
            e.preventDefault();
        }
    });

})( jQuery );