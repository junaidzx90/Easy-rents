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

    // Checkbox checked
    $('.radiobtns').children('p').children('input').on('click',function(){
        $('.radiobtns').children('p').children('input').each(function(){
            $(this).removeAttr('checked');
        });
        $(this).prop('checked',true);
    });

    // Check user exist
    $('.signup-number').blur(function(){
        if ($(this).val().length == 10) {
            $(this).css('border','1px solid #e2e2e2');

            $.ajax({
                type: "post",
                url: er_login_register.ajax_url,
                data: {
                    action: "check_register_user_existing",
                    phone: $('.signup-number').val(),
                    security: er_login_register.security
                },
                dataType: 'json',
                success: function (response) {
                    if(response.exist){
                        $('.signup-number').css('border','1px solid red');
                        $('.signupform').append('<span class="alert">'+response.exist+'</span>');
                        $('.alert').animate({'right': '8px'});
                        setTimeout(() => {
                            $('.alert').remove();
                        }, 4000);
                    }
                    if(response.approve){
                        $('.signup-number').css('border','1px solid #e2e2e2');
                    }
                }
            });
        }else{
            $('.signup-number').css('border','1px solid red');
            $('.signupform').append('<span class="alert">Type 10 digits of your number!</span>');
            $('.alert').animate({'right': '8px'});
            setTimeout(() => {
                $('.alert').remove();
            }, 4000);
        }
    });

    // Login input validate
    $('.login-number').blur(function(){
        if ($(this).val().length == 10) {
            $(this).css('border','1px solid #e2e2e2');
        }else{
            $('.login-number').css('border','1px solid red');
            $('.signupform').append('<span class="alert">Type 10 digits of your number!</span>');
            $('.alert').animate({'right': '8px'});
            setTimeout(() => {
                $('.alert').remove();
            }, 4000);
        }
    });

    // Check password
    $('.pass').blur(function () { 
        if ($(this).val().length < 6) {
            $(this).css('border','1px solid red');
            $('.signupform').append('<span class="alert">Minimum 6 digit!</span>');
            $('.alert').animate({'right': '8px'});
            setTimeout(() => {
                $('.alert').remove();
            }, 4000);
        }else{
            $(this).css('border','1px solid #e2e2e2');
        }
    });

    // Signup form submission with otp
    // All dependency And resourcess in er_login_register.php file
    $('.signup').on('click', function (e) {
        e.preventDefault();
        let phone = $('.signup-number').val();
        let password = $('.signup-pass').val();
        let accountType = $('.radiobtns').children('p').children('input:checked').val();

        if (phone != "" && password != "" && accountType != "") {
            if ($.isNumeric(phone)) {

                if(password.length > 6){
                    if (phone.length == 10) {
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

                        // OTP CONFIRMATION
                        $('.confirm').on('click', function (e) {
                            e.preventDefault();
                            $(this).prop('disabled', true);
                            var code = document.getElementById('verificationCode').value;
                            coderesult.confirm(code).then(function (result) {
                                
                                if(result.operationType == 'signIn'){
                                    
                                    $.ajax({
                                        type: "post",
                                        url: er_login_register.ajax_url,
                                        data: {
                                            action: "register_access_need",
                                            phone: phone,
                                            password: password,
                                            accountType: accountType,
                                            security: er_login_register.security
                                        },
                                        beforeSend: ()=>{
                                            $('.confirm').text('Processing...');
                                        },
                                        Cache: false,
                                        success: function (response) {
                                            if(response){
                                                $('.confirm').text('GET STARTED');
                                                $('.signupform').append('<span class="alert">Successfully registered</span>');
                                                $('.alert').animate({ 'right': '8px' });
                                                setTimeout(() => {
                                                    $('.alert').remove();
                                                    location.reload();
                                                }, 1500);
                                            }
                                        }
                                    });
                                }
                            
                            }).catch(function (error) {
                                $('.signupform').append('<span class="alert">'+error.message+'</span>');
                                $('.alert').animate({'right': '8px'});
                                setTimeout(() => {
                                    $('.alert').remove();
                                }, 4000);;
                                $('.confirm').removeAttr('disabled');
                            });
                        });
                    } else {
                        $('.signupform').append('<span class="alert">Type 10 digits of your number!</span>');
                        $('.alert').animate({'right': '8px'});
                        setTimeout(() => {
                            $('.alert').remove();
                        }, 4000);
                        e.preventDefault();
                    }
                }else{
                    $('.signupform').append('<span class="alert">At least 6 charcter!</span>');
                    $('.alert').animate({'right': '8px'});
                    setTimeout(() => {
                        $('.alert').remove();
                    }, 4000);
                }

            } else {
                $('.signupform').append('<span class="alert">Only Number allowed!</span>');
                $('.alert').animate({'right': '8px'});
                setTimeout(() => {
                    $('.alert').remove();
                }, 4000);
                e.preventDefault();
            }      
        } else {
            $('.signupform').append('<span class="alert">Don\'t leave empty!</span>');
            $('.alert').animate({'right': '8px'});
            setTimeout(() => {
                $('.alert').remove();
            }, 4000);
            e.preventDefault();
        }
    });

    // Login process
    $('.login').on('click',function(e){
        e.preventDefault();
        let phone = $('.login-number').val();
        let pass = $('.login-pass').val();

        if(phone != "" && pass != ""){
            if(phone.length == 10 && pass.length >= 6){
                $.ajax({
                    type: "post",
                    url: er_login_register.ajax_url,
                    data: {
                        action: "er_user_login_process",
                        phone: phone,
                        pass: pass,
                        security: er_login_register.security
                    },
                    dataType: 'json',
                    beforeSend: () =>{
                        $('.login').text('Processing...');
                    },
                    success: function (response) {
                        if(response.success){
                            location.href = response.success;
                        }
                        if(response.error){
                            $('.signupform').append('<span class="alert">'+response.error+'</span>');
                            $('.login').text('LOG IN');
                            $('.alert').animate({'right': '8px'});
                            setTimeout(() => {
                                $('.alert').remove();
                            }, 4000);
                        }
                    }
                });
            }
        } else {
            $('.signupform').append('<span class="alert">Require all fields!</span>');
            $('.alert').animate({'right': '8px'});
            setTimeout(() => {
                $('.alert').remove();
            }, 4000);
        }
    });

})( jQuery );