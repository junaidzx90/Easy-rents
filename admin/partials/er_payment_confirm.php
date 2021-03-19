<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/admin/partials
 */

$admin_this = new Easy_Rents( );
wp_enqueue_style( $admin_this->get_plugin_name() );
wp_enqueue_script( $admin_this->get_plugin_name() );
wp_localize_script( $admin_this->get_plugin_name(), "admin_ajaxurl", array(
    'ajax_url' => admin_url('admin-ajax.php')
));
?>
<div class="notice">
    <h3>PAYMENT STATUS FOR FINISHED JOBS</h3>
    
    <table class="table">
        <thead>
            <tr>
                <th>Job ID</th>
                <th>Price</th>
                <th>Payment</th>
                <th>Driver</th>
                <th>Phone</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                global $wpdb,$wp_query;
                $payment_status = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_applications WHERE status = 3");

                if(!empty($payment_status)){
                    foreach($payment_status as $payment){
                        $jobs_args = array(
                            'post_type' => 'jobs',
                            'post_status' => 'publish',
                            'post__in' => [$payment->post_id],
                            'order'     => 'ASC',
                            'order_by'     => 'date'
                        );

                        // Geting jobs
                        $jobs = new WP_Query($jobs_args);
                        
                        if ( $jobs->have_posts() ){
                            while ( $jobs->have_posts() ){
                                $jobs->the_post();
                                $job_info = get_post_meta( get_post()->ID, 'er_job_info' );
                                    // Only active/ running job
                                if($job_info[0]['job_status'] == 'ends'){
                                    $driverName = get_user_by( 'id', $payment->driver_id )->user_nicename;
                                    $user_number = get_user_meta( $payment->driver_id, 'user_phone_number', true );
                                    if($payment->payment == 1){
                                    $paymentStatus = "<span style='color:#65bc7b'>Paid&nbsp;☑</span>"; 
                                    }
                                    if($payment->payment == 0){
                                        $paymentStatus = "<span style='color:#ca4a1f'>Unpaid&nbsp;❗</span>"; 
                                    }

                                    if($payment->net_price > 0){
                                        $paybill = $payment->price-$payment->net_price;
                                    }else{
                                        $comm = 100 + $payment->commrate;
                                        $commbill =  $payment->price/$comm * $payment->commrate;
                                        $paybill = round($commbill);
                                    }
                                    ?>
                                    <tr>
                                        <td scope="row"><?php echo __(the_title(),'easy-rents'); ?></td>
                                        <td><?php echo __($payment->price,'easy-rents') ?> tk</td>
                                        <td><?php echo __($paybill,'easy-rents') ?> tk</td>
                                        <td><?php echo __($driverName,'easy-rents') ?></td>
                                        <td><?php echo __(($user_number != ""? $user_number:'N/A'),'easy-rents') ?></td>
                                        <td><?php echo __($paymentStatus,'easy-rents') ?></td>
                                        <td>
                                            <?php
                                            if($payment->payment == 0){
                                                ?>
                                                <button data-driver="<?php echo intval($payment->driver_id) ?>" data-amount="<?php echo intval( $paybill ) ?>" class="sendpaymentalert">✉</button>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                            }
                        }
                    }
                }
            ?>
        </tbody>
    </table>
</div>