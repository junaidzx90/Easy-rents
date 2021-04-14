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
<?php wp_enqueue_script( 'er_profile_script' ); ?>

<section>
    <div id="er_profileMain">

        <!-- Sidebar -->
        <?php require_once(ER_PATH.'public/partials/profile_sidebar.php') ?>

        <div class="profile__settingsDetails">
            <div class="update__form">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-contril">
                        <label for="uname">User Name</label>
                        <input id="uname" type="text" value="" placeholder="Type your name">
                    </div>
                    <div class="form-contril">
                        <label for="uemail">User Email</label>
                        <input id="uemail" type="email" value="" placeholder="Type your email">
                    </div>
                    <div class="form-contril">
                        <label for="uphone">User Phone</label>
                        <input id="uphone" type="text" value="" placeholder="+880" required autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10">
                    </div>
                    <div class="form-contril">
                        <label for="ubkash">bKash Number</label>
                        <input id="ubkash" type="text" value="" placeholder="Your personal bkash number" required autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10">
                    </div>
                    <div class="div nidcardadd">
                        <h5>Add NID Card</h5>
                        <div class="form-contril">
                            <label for="unidnum">NID Number</label>
                            <input id="unidnum" type="number" value="" placeholder="NID number">
                        </div>
                        <div class="form-contril filebtn">
                            <div class="nidphoto">
                                <label for="nidfront">Front Side</label>
                                <input type="file" name="nidfront" id="nidfront">
                            </div>
                            <div class="nidphoto">
                                <label for="nidback">Back Side</label>
                                <input type="file" name="nidback" id="nidback">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-contril">
                        <label for="presentaddr">Present Address</label>
                        <?php print_r(Easy_Rents_Public::er_prelocation_input('presentaddr', 'স্থানের নাম', 'presentaddr'));?>
                    </div>
                    <div class="form-contril">
                        <label for="permanentadd">Permanent Address</label>
                        <?php print_r(Easy_Rents_Public::er_prelocation_input('permanentadd', 'স্থানের নাম', 'permanentadd'));?>
                    </div>
                    <div class="form-contril">
                        <label for="billingaddr">Billing Address</label>
                        <?php print_r(Easy_Rents_Public::er_prelocation_input('billingaddr', 'স্থানের নাম', 'billingaddr'));?>
                    </div>

                    <div class="form-contril">
                        <h5>Refferral Code</h5>
                        <input id="reffer" type="text" value="" placeholder="Code">
                    </div>

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
                    

                    <button class="submit-button">Submit</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- /.content-wrapper -->