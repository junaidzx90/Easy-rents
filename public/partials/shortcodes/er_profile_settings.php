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
                <form id="profile__update_form" method="post" enctype="multipart/form-data">
                    <div class="form-contril username">
                        <div class="uname">
                            <label for="uname">User Name </label>
                            <input id="uname" name="uname" type="text"  value="<?php echo __($user->display_name, 'easy-rents'); ?>" placeholder="Type your name">
                        </div>
                        <div class="avatardiv">
                            <label for="avatar">Avatar</label>
                            <input type="file" name="avatar" name="" id="avatar">
                        </div>
                    </div>
                    <div class="form-contril emailaddr">
                        <label for="uemail">User Email</label>
                        <input id="uemail" name="email" type="email" value="<?php echo __($user->user_email,'easy-rents'); ?>"  placeholder="Type your email">
                    </div>
                    <div class="form-contril phonenum">
                        <label for="uphone">User Phone</label>
                        <input id="uphone" name="uphone" type="text" disabled readonly placeholder="+880<?php echo __($user->user_login, 'easy-rents'); ?>"  autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10">
                    </div>
                    <div class="form-contril bkashnom">
                        <label for="ubkash">bKash Number</label>
                        <input id="ubkash" name="ubkash" type="text" value="<?php echo __(get_user_meta(  $user->ID,'bkash_number', true),'easy-rents'); ?>" placeholder="Your personal bkash number"  autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10">
                    </div>

                    <?php
                    // {NID CARD}
                    if (is_user_logged_in() && $public_ins->er_role_check(['driver'])) {

                        if(get_user_meta($current_user->ID, 'user_nid_front', true) && empty(get_user_meta('id_verified'))){
                            ?>
                                <style>.nidcardadd{ display: none !important; }</style>
                                <div class="warning nidwarn">Your Request Currently pending!
                                </div>
                            <?php
                        }

                        // Checking Verified
                        if(!get_user_meta($current_user->ID, 'user_nid_front', true) && empty(get_user_meta('id_verified')) ){
                            ?>
                            <div class="div nidcardadd">
                                <h5>Add NID Card</h5>
                                <div class="form-contril">
                                    <label for="unidnum">NID Number</label>
                                    <input id="unidnum" name="nidnumber" type="text" value="<?php echo __(get_user_meta(  $user->ID,'nid_number', true),'easy-rents'); ?>" placeholder="NID number"  autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="13">
                                </div>
                                <div class="form-contril filebtn">
                                    <div class="nidphoto frontImgShow">
                                        <label for="nidfront">Front Side</label>
                                        <input type="file"  name="nidfront" id="nidfront">
                                    </div>
                                    <div class="nidphoto backImgShow">
                                        <label for="nidback">Back Side</label>
                                        <input type="file"  name="nidback" id="nidback">
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        
                        if(!empty(get_user_meta('id_verified')) && get_user_meta('id_verified') == 'verified' ){

                            ?>
                            <div class="warning verified">NID Card is Verified ✅
                            </div>
                            <?php
                    
                        }
                        
                        if(!empty(get_user_meta('id_verified')) && get_user_meta('id_verified') == 'unverified' ){
                            ?>
                            <style>.nidcardadd{ display: none !important; }</style>
                            <div class="warning nidwarn">NID Card is not Activate. Please Re-Submit Valid NID Information.
                                <button id="resubmit-nid">Upload</button>
                            </div>

                            <div class="div nidcardadd">
                                <h5>Add NID Card</h5>
                                <div class="form-contril">
                                    <label for="unidnum">NID Number</label>
                                    <input id="unidnum" name="nidnumber" type="text" value="<?php echo __(get_user_meta(  $user->ID,'nid_number', true),'easy-rents'); ?>" placeholder="NID number"  autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="13">
                                </div>
                                <div class="form-contril filebtn">
                                    <div class="nidphoto frontImgShow">
                                        <label for="nidfront">Front Side</label>
                                        <input type="file"  name="nidfront" id="nidfront">
                                    </div>
                                    <div class="nidphoto backImgShow">
                                        <label for="nidback">Back Side</label>
                                        <input type="file"  name="nidback" id="nidback">
                                    </div>
                                </div>
                            </div>
                            <?php
                        }

                    }

                    ?>
                    <div class="add__adressess">
                        <div class="form-contril presentAddrs">
                            <label for="presentaddr">Present Address</label>
                            <?php
                            if(get_user_meta( $user->ID, 'present__addr', true )){
                                print_r('<style>.locationgroup{ display: none !important; }</style>');

                                if(get_user_meta( $user->ID, 'present__addr', true )){
                                    $present__addr = "";
                                    foreach(get_user_meta( $user->ID, 'present__addr', true ) as $addr){
                                        $present__addr .= $addr.', ';
                                    }
                                }

                                echo '<input type="text" readonly id="present__addr" value="'.substr($present__addr,0,-2).'">';
                                print_r($public_ins->er_prelocation_input('presentaddr', 'স্থানের নাম', 'presentaddr', ''));
                            }else{
                                print_r($public_ins->er_prelocation_input('presentaddr', 'স্থানের নাম', 'presentaddr', ''));
                            }
                            ?>
                        </div>
                        <div class="form-contril permanentAddrs">
                            <label for="permanentadd">Permanent Address</label>

                            <?php
                            if(get_user_meta( $user->ID, 'permanent__addr', true )){
                                if(get_user_meta( $user->ID, 'permanent__addr', true )){
                                    $permanent__addr = "";
                                    foreach(get_user_meta( $user->ID, 'permanent__addr', true ) as $addr){
                                        $permanent__addr .= $addr.', ';
                                    }
                                }

                                echo '<input type="text" readonly id="permanent__addr" value="'.substr($permanent__addr,0,-2).'">';
                                print_r($public_ins->er_prelocation_input('permanentadd', 'স্থানের নাম', 'permanentadd', ''));
                            }else{
                                print_r($public_ins->er_prelocation_input('permanentadd', 'স্থানের নাম', 'permanentadd', ''));
                            }
                            ?>
                        </div>
                        <div class="form-contril billingAddrs">
                            <label for="billingaddr">Billing Address</label>
                            <?php
                            if(get_user_meta( $user->ID, 'billing__addr', true )){
                                if(get_user_meta( $user->ID, 'billing__addr', true )){
                                    $billing__addr = "";
                                    foreach(get_user_meta( $user->ID, 'billing__addr', true ) as $addr){
                                        $billing__addr .= $addr.', ';
                                    }
                                }
                                echo '<input type="text" readonly id="billing__addr" value="'.substr($billing__addr,0,-2).'">';
                                print_r($public_ins->er_prelocation_input('billingaddr', 'স্থানের নাম', 'billingaddr', ''));
                            }else{
                                print_r($public_ins->er_prelocation_input('billingaddr', 'স্থানের নাম', 'billingaddr', ''));
                            }
                            ?>
                        </div>
                    </div>

                    <?php
                    if(!get_user_meta($current_user->ID, 'refer_code', true)){
                        ?>
                        <div class="form-contril referCode">
                            <button class="hasrefercode">I have a (Refer Code)</button>
                            <div class="refer_inp">
                                <span class="warn">Invalid Code</span>
                                <input id="reffer" type="text" name="refer_code" placeholder="Code">
                            </div>
                        </div>
                        <?php
                    }
                    ?>
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