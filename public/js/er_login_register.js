window.onload = function () {
    render();
};

function render() {
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
    recaptchaVerifier.render();
}

(function ($) {
    inputlabel();
    function inputlabel() {
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
    }
   

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

    // Check user exist for register
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

    // login-number, .mynumber validate
    $('.login-number, .mynumber').blur(function () {
        
        if ($(this).val().length == 10) {
            $(this).css('border','1px solid #e2e2e2');
        }else{
            $(this).css('border','1px solid red');
            $('.signupform').append('<span class="alert">Type 10 digits of your number!</span>');
            $('.alert').animate({'right': '8px'});
            setTimeout(() => {
                $('.alert').remove();
            }, 4000);
        }
    });

    // .pass, .newpass Check
    $('.pass, .newpass').blur(function () { 
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

    // Check confirm pass (.cpass, .newpass)
    $('.cpass, .newpass').on('input', function () {
        if ($('.newpass').val() !== $('.cpass').val()) {
            if ($('.cpass').val() != "") {
                $('.cpass').css('border','1px solid red');
            }
        } else {
            $('.cpass').css('border','1px solid #e2e2e2');
        }
    });

    // Check user exist for reset pass
    var validity = false;
    $('.mynumber').blur(function(){
        if ($(this).val().length == 10) {
            $(this).css('border','1px solid #e2e2e2');

            $.ajax({
                type: "post",
                url: er_login_register.ajax_url,
                data: {
                    action: "check_register_user_existing",
                    phone: $('.mynumber').val(),
                    security: er_login_register.security
                },
                dataType: 'json',
                success: function (response) {
                    if (response.exist) {
                        validity = true;
                        $('.mynumber').css('border', '1px solid #e2e2e2');
                        $('.continue').removeAttr('disabled');
                    }
                    if (response.approve) {
                        validity = false;
                        $('.mynumber').css('border','1px solid red');
                        $('.signupform').append('<span class="alert">User not exist!</span>');
                        $('.continue').prop('disabled', true);
                        $('.alert').animate({'right': '8px'});
                        setTimeout(() => {
                            $('.alert').remove();
                        }, 4000);
                    }
                }
            });
        }else{
            $('.mynumber').css('border','1px solid red');
            $('.signupform').append('<span class="alert">Type 10 digits of your number!</span>');
            $('.alert').animate({'right': '8px'});
            setTimeout(() => {
                $('.alert').remove();
            }, 4000);
        }
    });

    // Reset password
    resetpass();
    function resetpass() {
        // Turn off phone auth app verification.
        $('.continue').on('click', function (e) {
            e.preventDefault();
            $(this).text('Processing..');
            let phone = $('.mynumber').val();

            if (validity === true) {
                if (phone.length == 10) {
                    //get the number
                    var number = '+880' + phone;
                
                    //phone number authentication function of firebase
                    //it takes two parameter first one is number,,,second one is recaptcha
                    firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function (confirmationResult) {
                        //s is in lowercase
                        window.confirmationResult = confirmationResult;
                        coderesult = confirmationResult;

                        $('.continue').text('CONTINUE');
                        $('.signupform').append('<span class="alert">Message sent</span>');
                        $('.alert').animate({ 'right': '8px' });
                        $('.forgot-form').hide();
                    
                        $('.confirmationform').html('<form action="" method="post" class="forgot-change-form">' +
                            '<div class="field-wrap">' +
                            '<label>' +
                            'OTP Code<span class="req">*</span>' +
                            '</label>' +
                            '<input id="otpcode" type="text" required autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,\'\');" maxlength="6" />' +
                            '</div>' +

                            '<div class="field-wrap">' +
                            '<label>' +
                            'New Password<span class="req">*</span>' +
                            '</label>' +
                            '<input class="newpass" type="password" required autocomplete="off" />' +
                            '</div>' +
                            '<div class="field-wrap">' +
                            '<label>' +
                            'Confirm Password<span class="req">*</span>' +
                            '</label>' +
                            '<input class="cpass" type="text" required autocomplete="off" />' +
                            '</div>' +

                            '<button type="submit" class="button button-block resetpass">CHANGE</button>' +
                            '</form>');
                    
                        inputlabel();

                        // OTP CONFIRMATION
                        $('.resetpass').on('click', function (e) {
                            e.preventDefault();
                            $(this).prop('disabled', true);
                    
                            var code = document.getElementById('otpcode').value;
                    
                            coderesult.confirm(code).then(function (result) {
                    
                                if (result.operationType == 'signIn') {
                            
                                    let number = $('.mynumber').val();
                                    let newpass = $('.newpass').val();
                                    let cpass = $('.cpass').val();

                                    if (number != "" && newpass != "" && cpass != "") {
                                        if (newpass === cpass) {
            
                                            $.ajax({
                                                type: "post",
                                                url: er_login_register.ajax_url,
                                                data: {
                                                    action: 'er_reset_user_password',
                                                    number: number,
                                                    newpass: newpass,
                                                    cpass: cpass,
                                                    security: er_login_register.security
                                                },
                                                dataType: 'json',
                                                beforeSend: () => {
                                                    $('.resetpass').text('Processing..');
                                                },
                                                success: function (response) {
                                                    if (response.success) {
                                                        $('.signupform').append('<span class="alert">' + response.success + '</span>');
                                                        $('.alert').animate({ 'right': '8px' });
                                                        setTimeout(() => {
                                                            localStorage.removeItem("coderesult");
                                                            $('.resetpass').text('CHANGE');
                                                            $('.alert').remove();
                                                            location.href = window.location.origin + window.location.pathname;
                                                        }, 1000);
                                                    }
                                                }
                                            });

                                        } else {
                                            $('.signupform').append('<span class="alert">Confirm password doesn\'t match!</span>');
                                            $('.alert').animate({ 'right': '8px' });
                                            setTimeout(() => {
                                                $('.alert').remove();
                                            }, 4000);
                                        }
            
                                    } else {
                                        $('.signupform').append('<span class="alert">Require all fields!</span>');
                                        $('.alert').animate({ 'right': '8px' });
                                        setTimeout(() => {
                                            $('.alert').remove();
                                        }, 4000);
                                    }
                                } else {
                                    $('.signupform').append('<span class="alert">Invalid OTP</span>');
                                    $('.alert').animate({ 'right': '8px' });
                                    setTimeout(() => {
                                        $('.alert').remove();
                                    }, 3000);;
                                    $('.confirm').removeAttr('disabled');
                                }
                
                            }).catch(function (error) {
                                $('.signupform').append('<span class="alert">' + error.message + '</span>');
                                $('.alert').animate({ 'right': '8px' });
                                setTimeout(() => {
                                    $('.alert').remove();
                                    location.reload();
                                }, 3000);;
                                $('.confirm').removeAttr('disabled');
                            });
                        });


                    }).catch(function (error) {
                        e.preventDefault();
                        $('.continue').text('CONTINUE');
                        $('.signupform').append('<span class="alert">' + error.message + '</span>');
                        $('.alert').animate({ 'right': '8px' });
                        setTimeout(() => {
                            $('.alert').remove();
                        }, 4000);
                    });

                } else {
                    e.preventDefault();
                    $('.signupform').append('<span class="alert">Type 10 digits of your number!</span>');
                    $('.alert').animate({ 'right': '8px' });
                    setTimeout(() => {
                        $('.alert').remove();
                    }, 4000);
                }
            } else {
                e.preventDefault();
                $('.signupform').append('<span class="alert">User already exist!</span>');
                $('.alert').animate({ 'right': '8px' });
                setTimeout(() => {
                    $('.alert').remove();
                }, 2000);
            }
        });
    }
})( jQuery );