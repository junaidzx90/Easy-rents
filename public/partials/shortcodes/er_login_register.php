<?php
ob_start();
wp_enqueue_style( 'er_login_register' );
wp_enqueue_script( 'er_login_register' );
wp_localize_script( "er_login_register", "er_login_register", array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'security' => wp_create_nonce( 'er_login_register' )
));

$firebase = <<<EOT
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyB-5oaQhZ6NbExcYe65m5BNdaJhPLOSV3w",
        authDomain: "react-todo-67ad5.firebaseapp.com",
        databaseURL: "https://react-todo-67ad5-default-rtdb.firebaseio.com",
        projectId: "react-todo-67ad5",
        storageBucket: "react-todo-67ad5.appspot.com",
        messagingSenderId: "62633775580",
        appId: "1:62633775580:web:ec6af04e3d378440fa3f19",
        measurementId: "G-WZ2BHE7KKV"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
  EOT;
?>
<?php
function log_the_user_in() {
    if ( ! empty( $_POST['user_login'] ) && ! empty( $_POST['user_password'] ) ) {
 
        if ( is_numeric( $_POST['user_login'] ) ) {
            // check user by phone number
            global $wpdb;
            $tbl_usermeta = $wpdb->prefix.'usermeta';
            $user_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM $tbl_usermeta WHERE meta_key=%s AND meta_value=%s", 'user_phone', $_POST['user_login'] ) );

            $user = get_user_by( 'ID', $user_id );
        } else {
            return new WP_Error('wrong_credentials', 'Invalid credentials.');
        }

        if ( ! $user ) {
            return new WP_Error('wrong_credentials', 'Invalid credentials.');
        }

        // check the user's login with their password.
        if ( ! wp_check_password( $_POST['user_password'], $user->user_pass, $user->ID ) ) {
            return new WP_Error('wrong_credentials', 'Invalid credentials.');
        }

        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);

        wp_redirect(get_bloginfo('url'));
        exit;
    } else {
        return new WP_Error('empty', 'Both fields are required.');
    }
}
?>

<div class="signupform" id="signupform">
    
    <div class="form">
        <ul class="tab-group">
            <li class="tab active"><a href="#login">Log In</a></li>
            <li class="tab"><a href="#signup">Sign Up</a></li>
        </ul>

        <div class="tab-content">
            
            <div id="login">
                <h3 class="logintitle">
                    <?php if(isset($_GET['forgot']) && $_GET['forgot'] == 'true') echo __('Reset Password','easy-rents'); else echo __('Welcome Back!','easy-rents')?></h3>
                <?php
                    if(isset($_GET['forgot']) && $_GET['forgot'] == 'true'){
                        ?>
                            <form action="/" method="post" class="forgot-form">
                                <div class="field-wrap">
                                    <label>
                                        New Password<span class="req">*</span>
                                    </label>
                                    <input class="newpass" type="password" required autocomplete="off" />
                                </div>
                                <div class="field-wrap">
                                    <label>
                                        Confirm Password<span class="req">*</span>
                                    </label>
                                    <input class="cpass" type="text" required autocomplete="off" />
                                </div>
                                <button type="submit" class="button button-block resetpass">Create</button>
                            </form>
                        <?php
                    }else{
                        ?>
                            <form action="/" method="post" class="login-form">
                                <div class="field-wrap">
                                    <label>
                                        Phone Number<span class="req">*</span>
                                    </label>
                                    <input class="login-number" type="text" required autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10" />
                                </div>

                                <div class="field-wrap last">
                                    <label>
                                        Password<span class="req">*</span>
                                    </label>
                                    <input class="login-pass" type="password" required autocomplete="off" />
                                    <span class="showpass">👁</span>
                                </div>

                                <p class="forgot"><a href="?forgot=true">Forgot Password?</a></p>

                                <button class="button button-block login">Log In</button>
                            </form>
                        <?php
                    }
                ?>

            </div>

            <div id="signup">
                <h3 class="signuptitle">Sign Up for Free</h3>

                <form action="/" method="post" class="signup-form">
                    <div class="field-wrap">
                        <label>
                            Phone Number<span class="req">*</span>
                        </label>
                        <input class="signup-number" type="text" required autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10" />
                    </div>

                    <div class="field-wrap last2">
                        <label>
                            Set A Password<span class="req">*</span>
                        </label>
                        <input id="number" class="signup-pass" type="password" required autocomplete="off" />
                        <span class="showpass">👁</span>
                    </div>
                    
                    <div id="recaptcha-container" style="transform:scale(0.80);transform-origin:0 0"></div>
                    <button type="submit" class="button button-block signup" >Get Started</button>
                </form>

                <!-- OTP CONFIRMATION FORM -->
                <form action="/" method="post" class="otp-code-form">
                    <div class="field-wrap">
                        <label>
                            Confirmation Code<span class="req">*</span>
                        </label>
                        <input id="verificationCode" class="otp-code" type="text" required autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="6" />
                    </div>
                    <button type="submit" class="button button-block confirm">Submit</button>
                </form>

            </div>

        </div><!-- tab-content -->

    </div> <!-- /form -->
</div>
  <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
  <script src="https://www.gstatic.com/firebasejs/8.3.3/firebase-app.js"></script>
  <!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
  <script src="https://www.gstatic.com/firebasejs/8.3.3/firebase-analytics.js"></script>
  <!-- Add Firebase products that you want to use -->
  <script src="https://www.gstatic.com/firebasejs/8.3.3/firebase-auth.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.3.3/firebase-firestore.js"></script>
<script>
<?php  print_r($firebase) ?>
</script>