<?php
$page = 'settings';
/**
    * @link example.com
    * @since 1.0.0
    *
    * @package Easy_Rents
    * @subpackage Easy_Rents/public/partials/er_profile
    * */
?>
<?php wp_enqueue_style( 'er_profile_style' ); ?>
<?php wp_enqueue_script( 'er_profile_script' ); 
wp_localize_script('er_profile_script', "er_profile_ajax", array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('ajax-nonce'),
));
?>
<?php $public_ins = new Easy_Rents_Public(); ?>
<?php
global $current_user;
$user = get_user_by( 'id', $current_user->ID );
?>
<section>
    <div id="er_profileMain">

        <!-- Sidebar -->
        <?php require_once(ER_PATH.'public/partials/profile_sidebar.php') ?>

        <div class="profile__settingsDetails">
            <div class="update__form">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-contril username">
                        <label for="uname">User Name</label>
                        <input id="uname" type="text" value="<?php echo __($user->display_name, 'easy-rents'); ?>" placeholder="Type your name">
                    </div>
                    <div class="form-contril emailaddr">
                        <label for="uemail">User Email</label>
                        <input id="uemail" type="email" value="" placeholder="Type your email">
                    </div>
                    <div class="form-contril phonenum">
                        <label for="uphone">User Phone</label>
                        <input id="uphone" type="text" disabled readonly placeholder="+880<?php echo __($user->user_login, 'easy-rents'); ?>" required autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10">
                    </div>
                    <div class="form-contril bkashnom">
                        <label for="ubkash">bKash Number</label>
                        <input id="ubkash" type="text" value="" placeholder="Your personal bkash number" required autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10">
                    </div>

                    <?php
                    if (is_user_logged_in() && $public_ins->er_role_check(['driver'])) {
                    ?>
                        <div class="div nidcardadd">
                            <h5>Add NID Card</h5>
                            <div class="form-contril">
                                <label for="unidnum">NID Number</label>
                                <input id="unidnum" type="text" value="" placeholder="NID number" required autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="13">
                            </div>
                            <div class="form-contril filebtn">
                                <div class="nidphoto frontImgShow">
                                    <label for="nidfront">Front Side</label>
                                    <input type="file" name="nidfront" id="nidfront">
                                </div>
                                <div class="nidphoto backImgShow">
                                    <label for="nidback">Back Side</label>
                                    <input type="file" name="nidback" id="nidback">
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="form-contril presentAddrs">
                        <label for="presentaddr">Present Address</label>
                        <?php print_r($public_ins->er_prelocation_input('presentaddr', 'স্থানের নাম', 'presentaddr'));?>
                    </div>
                    <div class="form-contril permanentAddrs">
                        <label for="permanentadd">Permanent Address</label>
                        <?php print_r($public_ins->er_prelocation_input('permanentadd', 'স্থানের নাম', 'permanentadd'));?>
                    </div>
                    <div class="form-contril billingAddrs">
                        <label for="billingaddr">Billing Address</label>
                        <?php print_r($public_ins->er_prelocation_input('billingaddr', 'স্থানের নাম', 'billingaddr'));?>
                    </div>

                    <div class="form-contril referCode">
                        <h5>Refferral Code</h5>
                        <input id="reffer" type="text" value="" placeholder="Code">
                    </div>
                    <?php
                    if (is_user_logged_in() && $public_ins->er_role_check(['driver'])){
                        ?>
                        <div class="truckAdd">
                            <h5>Add Truck</h5>
                            <div class="addingContents">
                                <div class="form-row multi-inps">
                                    <div class="form-contril">
                                        <label for="carnumber">Number</label>
                                        <input id="carnumber" type="text" value="" placeholder="Car Number">
                                    </div>
                                    <div class="form-contril">
                                        <label for="truckName">Truck Name</label>
                                        <select name="truckName" id="truckName">
                                            <option value="">Truck</option>
                                        </select>
                                    </div>
                                    <div class="form-contril">
                                        <label for="truckSize">Truck Size</label>
                                        <select name="truckSize" id="truckSize">
                                            <option value="">Size</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row truckImgwrap">
                                    <div class="truckImg">
                                        <label for="truckImg">Truck Img</label>
                                        <input type="file" name="truckImg" id="truckImg">
                                    </div>
                                    <div class="truckshow"></div>
                                </div>
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