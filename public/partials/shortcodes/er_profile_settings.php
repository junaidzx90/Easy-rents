<?php
$page = 'settings';
/**
    * @link example.com
    * @since 1.0.0
    *
    * @package Easy_Rents
    * @subpackage Easy_Rents/public/partials/er_profile
    * */

wp_enqueue_style( 'er_profile_style' );
wp_enqueue_script( 'jquery.form.min' );
wp_enqueue_script( 'er_profile_script' );
wp_localize_script('er_profile_script', "er_profile_ajax", array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('ajax-nonce'),
));
?>
<?php $public_ins = new Easy_Rents_Public(); ?>
<?php
global $current_user;

$user = $current_user;

?>
<section>
    <div id="er_profileMain">

        <!-- Sidebar -->
        <?php require_once(ER_PATH.'public/partials/profile_sidebar.php') ?>

        <div class="profile__settingsDetails">
            <div class="update__form">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-contril username">
                        <div class="uname">
                            <label for="uname">User Name </label>
                            <input id="uname" type="text" required value="<?php echo __($user->display_name, 'easy-rents'); ?>" placeholder="Type your name">
                        </div>
                        <div class="avatardiv">
                            <label for="avatar">Avatar</label>
                            <input type="file" name="" id="avatar">
                        </div>
                    </div>
                    <div class="form-contril emailaddr">
                        <label for="uemail">User Email</label>
                        <input id="uemail" type="email" value="<?php echo __($user->user_email,'easy-rents'); ?>" required placeholder="Type your email">
                    </div>
                    <div class="form-contril phonenum">
                        <label for="uphone">User Phone</label>
                        <input id="uphone" type="text" disabled readonly placeholder="+880<?php echo __($user->user_login, 'easy-rents'); ?>" required autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10">
                    </div>
                    <div class="form-contril bkashnom">
                        <label for="ubkash">bKash Number</label>
                        <input id="ubkash" type="text" value="<?php echo __(get_user_meta(  $user->ID,'bkash_number', true),'easy-rents'); ?>" placeholder="Your personal bkash number" required autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10">
                    </div>

                    <?php
                    if (is_user_logged_in() && $public_ins->er_role_check(['driver'])) {
                    ?>
                        <div class="div nidcardadd">
                            <h5>Add NID Card</h5>
                            <div class="form-contril">
                                <label for="unidnum">NID Number</label>
                                <input id="unidnum" type="text" value="<?php echo __(get_user_meta(  $user->ID,'nid_number', true),'easy-rents'); ?>" placeholder="NID number" required autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="13">
                            </div>
                            <div class="form-contril filebtn">
                                <div class="nidphoto frontImgShow">
                                    <label for="nidfront">Front Side</label>
                                    <input type="file" required name="nidfront" id="nidfront">
                                </div>
                                <div class="nidphoto backImgShow">
                                    <label for="nidback">Back Side</label>
                                    <input type="file" required name="nidback" id="nidback">
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="add__adressess">
                        <div class="form-contril presentAddrs">
                            <label for="presentaddr">Present Address</label>
                            <?php
                            if(get_user_meta( $user->ID, 'present__addr', true )){
                                print_r('<style>.locationgroup{ display: none !important; }</style>');
                                echo '<input type="text" readonly id="present__addr" value="">';
                                print_r($public_ins->er_prelocation_input('presentaddr', 'স্থানের নাম', 'presentaddr', 'required'));
                            }else{
                                print_r($public_ins->er_prelocation_input('presentaddr', 'স্থানের নাম', 'presentaddr', 'required'));
                            }
                            ?>
                        </div>
                        <div class="form-contril permanentAddrs">
                            <label for="permanentadd">Permanent Address</label>

                            <?php
                            if(get_user_meta( $user->ID, 'present__addr', true )){
                                echo '<input type="text" readonly id="permanent__addr" value="">';
                                print_r($public_ins->er_prelocation_input('permanentadd', 'স্থানের নাম', 'permanentadd', 'required'));
                            }else{
                                print_r($public_ins->er_prelocation_input('permanentadd', 'স্থানের নাম', 'permanentadd', 'required'));
                            }
                            ?>
                        </div>
                        <div class="form-contril billingAddrs">
                            <label for="billingaddr">Billing Address</label>
                            <?php
                            if(get_user_meta( $user->ID, 'present__addr', true )){
                                echo '<input type="text" readonly id="billing__addr" value="">';
                                print_r($public_ins->er_prelocation_input('billingaddr', 'স্থানের নাম', 'billingaddr', 'required'));
                            }else{
                                print_r($public_ins->er_prelocation_input('billingaddr', 'স্থানের নাম', 'billingaddr', 'required'));
                            }
                            ?>
                        </div>
                    </div>

                    <div class="form-contril referCode">
                        <button class="hasrefercode">I have Refer Code</button>
                        <div class="refer_inp">
                            <span class="warn">Invalid Code</span>
                            <input id="reffer" type="text" value="" placeholder="Code">
                        </div>
                    </div>

                    <button class="submit-button">Submit</button>
                </form>
            </div>

            <div class="noticeboard">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id consequatur odit saepe illum? Voluptates, voluptatum, dolorum sunt id, culpa sequi inventore animi fugit perferendis neque exercitationem provident quis laboriosam sit!
            </div>
        </div>

    </div>
</section>

<!-- /.content-wrapper -->