<?php
if(!Easy_Rents_Public::er_role_check( ['driver'] )){
    wp_safe_redirect( home_url(Easy_Rents_Public::get_post_slug(get_option( 'profile_page', true ))) );
    exit;
}
$page = 'payment';
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
<?php
    // Make payment
    if(isset($_POST['paynow'])){
        if(isset($_POST['myid']) && isset($_POST['tripid'])){
            $myid = intval($_POST['myid']);
            $tripid = intval($_POST['tripid']);
            ?>
            <div id="paymentform">
                <div class="formhelper">
                    <div class="paymentform">
                        <div class="paymethodselect">
                            <Select id="selectpaymethod">
                                <option value="" disabled selected>Select Payment Method</option>
                                <option value="bkash">Bkash</option>
                                <option value="roket">Rocket</option>
                            </Select>
                        </div>
                        <!-- bkash method -->
                        <div id="bkash">
                            <span class="backbtn">⇦</span>
                            <div class="bkashlogo">
                                <img src="https://i0.wp.com/www.the-potato.net/extra-images/404_text_01.png" alt="bkash">
                            </div>
                            <div class="addr">
                                <div class="qr">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/QR_code_for_mobile_English_Wikipedia.svg/1200px-QR_code_for_mobile_English_Wikipedia.svg.png" alt="bahak-bkash-qr">
                                </div>
                                <span>
                                    Personal
                                    +8801953828421
                                </span>
                            </div>
                            <div class="transiction_number">
                                <form action="" method="post">
                                    <label for="transiction">Transiction Number</label>
                                    <input id="transiction" type="text" name="bktransiction" placeholder="Enter transiction number">
                                    <small>Make Sure don't give wrong transiction number!</small>
                                    <button class="bkashsubmit" name="paysubmit">Submit</button>
                                </form>
                            </div>
                        </div>
                        <!-- roket method -->
                        <div id="roket">
                            <span class="backbtn">⇦</span>
                            <div class="bkashlogo">
                                <img src="https://www.dutchbanglabank.com/img/mlogo.png" alt="roket">
                            </div>
                            <div class="addr">
                                <div class="qr">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/QR_code_for_mobile_English_Wikipedia.svg/1200px-QR_code_for_mobile_English_Wikipedia.svg.png" alt="bahak-bkash-qr">
                                </div>
                                <span>
                                    Personal
                                    +8801953828421
                                </span>
                            </div>
                            <div class="transiction_number">
                                <form action="" method="post">
                                    <label for="transiction">Transiction Number</label>
                                    <input id="transiction" type="text" name="transiction" placeholder="Enter transiction number">
                                    <small>Make Sure don't give wrong transiction number!</small>
                                    <button class="submitbtn" name="paysubmit">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
?>
<section>
    <div id="er_profileMain">

        <!-- Sidebar -->
        <?php require_once(ER_PATH.'public/partials/profile_sidebar.php') ?>

        <main>
        <?php
            global $wpdb,$current_user;
            $trips = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_applications WHERE driver_id = $current_user->ID AND status = 3 OR status = 4 ORDER BY payment ASC");

            if(!empty($trips)){
                foreach($trips as $trip){
                   $trip_args = array(
                        'post_type' => 'jobs',
                        'post_status' => 'publish',
                        'post__in' => [$trip->post_id],
                        'order'     => 'ASC',
                        'order_by'     => 'date'
                    );
                    // Geting jobs
                    $mytrips = new WP_Query($trip_args);

                    if ( $mytrips->have_posts() ){
                        while ( $mytrips->have_posts() ){
                            $mytrips->the_post();
                            ?>
                            <div class="jobitem">
                                <table>
                                    <thead>
                                        <tr>
                                            <th><strong>Trip ID</strong></th>
                                            <th><strong>Price</strong></th>
                                            <th><strong>Payment</strong></th>
                                            <th><strong>Finished Date</strong></th>
                                            <th><strong>Action</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php echo __(the_title(),'easy-rents'); ?>
                                            </td>
                                            <td>
                                                <?php echo __($trip->price,'easy-rents'); ?> tk
                                            </td>
                                            <td>
                                                <?php
                                                $price = $trip->price;
                                                $netprice = $trip->net_price;
                                                $commission = $trip->commrate;
                                
                                                if($netprice > 0){
                                                    $paybill = $price-$netprice;
                                                }else{
                                                    $comm = 100 + $commission;
                                                    $commbill =  $price/$comm * $commission;
                                                    $paybill = round($commbill);
                                                }
                                                $paymentstatus = '<span class="unpaid">UNPAID ⛔</span>';
                                                if($trip->payment == 1){
                                                    $paymentstatus = '<span class="paid">PAID ☑</span>';
                                                }
                                                if($trip->payment == 2){
                                                    $paymentstatus = '<span class="pending">PENDING ◔</span>';
                                                }
                                                if($trip->payment == 0){
                                                    $paymentstatus = '<span class="unpaid">UNPAID ⛔</span>';
                                                }

                                                echo '<span class="amount">'.$paybill.'</span>tk ';
                                                echo $paymentstatus;
                                                ?>
                                            </td>
                                            <td>
                                            <?php echo date("jS F, Y", strtotime($trip->finished_date)) ?>
                                            </td>
                                            <td>
                                                <?php
                                                if($trip->payment == 1){
                                                    echo 'Paid';
                                                }
                                                if($trip->payment == 2){
                                                    echo 'Pending';
                                                }
                                                if($trip->payment == 0){
                                                    ?>
                                                    <form action="" method="post">
                                                        <input type="hidden" value="<?php echo intval($trip->driver_id); ?>" name="myid">
                                                        <input type="hidden" value="<?php echo intval($trip->ID); ?>" name="tripid">
                                                        <button name="paynow">Pay Now</button>
                                                    </form>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        }
                    } 
                }
            }else{
                ?>
                <div class="helper">
                    You haven't completed any trips yet
                    <span>Submit your proposal for getting job.</span>
                </div>
                <?php
            }
        ?>
        </main>
    </div>
</section>

<!-- /.content-wrapper -->