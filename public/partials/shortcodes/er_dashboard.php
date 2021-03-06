<?php
$page = 'dashboard';
/**
    * @link example.com
    * @since 1.0.0
    *
    * @package Easy_Rents
    * @subpackage Easy_Rents/public/partials/er_profile
    * */
?>
<?php
wp_enqueue_style( 'er_profile_style' );
wp_enqueue_script( 'er_profile_script' );
wp_localize_script( "er_profile_script", "er_profile_ajax", array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'security' => wp_create_nonce( 'er_profile' )
));
?>
<?php $public_ins = new Easy_Rents_Public(); ?>
<?php global $wpdb,$current_user,$wp_query; ?>
<section>
    <div id="er_profileMain">

        <!-- Sidebar -->
        <?php require_once(ER_PATH.'public/partials/profile_sidebar.php') ?>

        <main>
            <div class="tabs">
                <button class="pending erbtnactive" onclick="er_transform('pending',this)">Pending</button>
                <button class="running" onclick="er_transform('running',this)">Running</button>

                <a class="logout" href="<?php echo wp_logout_url(); ?>"><i class="fas fa-sign-out-alt"></i> Log Out</a>
            </div>
            <?php
            if (is_user_logged_in() && $public_ins->er_role_check(['driver'])){
                $userStatus = get_user_by( 'id', $current_user->ID );
                if($userStatus->user_status == 0){
                    echo '<div class="warning">Hey '.$userStatus->display_name.', Your account needs to be active for work here.</div>';
                }
            }
            ?>

            <div id="pending" class="tabelem">
                <?php
                if(Easy_Rents_Public::er_role_check( ['customer'] )){
                    // Checking for pending requ
                    $pendingrequests = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_applications WHERE customer_id = {$current_user->ID} AND status = 1");

                    if($pendingrequests){
                        foreach($pendingrequests as $pjob){
                            $jobs_args = array(
                                'post_type' => 'jobs',
                                'post_status' => 'publish',
                                'post__in' => [$pjob->post_id],
                                'order'     => 'ASC',
                                'order_by'     => 'date'
                            );
                            // Geting jobs
                            $pendingJobs = new WP_Query($jobs_args);

                            if ( $pendingJobs->have_posts() ){
                                while ( $pendingJobs->have_posts() ){
                                    $pendingJobs->the_post();

                                    $job_info = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}easy_rents_trips WHERE post_id = $pjob->post_id AND job_status = 'running' ORDER BY ID ASC");
                                    // Only active/ running job
                                    if($job_info){
                                        ?>
                                        <div class="jobitem">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th><strong>Trip ID</strong></th>
                                                        <th><strong>Price</strong></th>
                                                        <th><strong>Rattings</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <?php echo __(the_title(),'easy-rents'); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo __($pjob->price,'easy-rents'); ?>tk
                                                        </td>
                                                        <td>
                                                            100%
                                                        </td>
                                                    </tr>
                                                    <div class="job_actions">
                                                        <button class="view">
                                                        <a href="<?php echo the_permalink(); ?>" target="_junu">View</a></button>
                                                        <form action="" method="post">
                                                            <input type="hidden" value="<?php echo intval(get_post()->ID); ?>" name="erp">

                                                            <input type="hidden" value="<?php echo intval($pjob->driver_id); ?>" name="erd">
                                                            <?php
                                                            if($pjob->status = 1){
                                                            ?>
                                                                <button data-id="<?php echo intval($pjob->ID) ?>" class="acceptrequest" name="acceptrequest">Accept</button>
                                                            <?php
                                                            }
                                                            ?>
                                                            <button class="cancel ignorerequest" name="ignorerequest">Cancel</button>
                                                        </form>
                                                    </div>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                            <div class="helper">
                                                No request found
                                            </div>
                                        <?php
                                    }
                                }
                            }
                        }
                    }else{
                        ?>
                            <div class="helper">
                                No request found
                            </div>
                        <?php
                    }
                }

                if(Easy_Rents_Public::er_role_check( ['driver'] )){
                    // Checking for pending requ
                    $myapplications = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_applications WHERE driver_id = {$current_user->ID} AND status = 1");
                
                    // My pending requests loop
                    if($myapplications){
                        foreach($myapplications as $myapplication){

                            $jobs_args = array(
                                'post_type' => 'jobs',
                                'post_status' => 'publish',
                                'post__in' => [$myapplication->post_id],
                                'order'     => 'ASC',
                                'order_by'     => 'date'
                            );
                            // Geting jobs
                            $jobs = new WP_Query($jobs_args);
                            
                            if ( $jobs->have_posts() ){
                                while ( $jobs->have_posts() ){
                                    $jobs->the_post();

                                    $job_info = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}easy_rents_trips WHERE post_id = $myapplication->post_id AND job_status = 'running' ORDER BY ID ASC");
                                    // Only active/ running job
                                    if($job_info){
                                        ?>
                                        <div class="jobitem">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th><strong>Load Location</strong></th>
                                                        <th><strong>Unload Location</strong></th>
                                                        <th><strong>Load Time</strong></th>
                                                        <th><strong>Price</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i> 
                                                            <?php echo __(substr($job_info->location_1,0,29),'easy-rents'); ?>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> 
                                                            <?php echo __(substr($job_info->unload_loc,0,29),'easy-rents'); ?>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-clock-o" aria-hidden="true"></i> 
                                                            <?php echo __($job_info->load_time,'easy-rents'); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo __($myapplication->price,'easy-rents'); ?>tk
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <div class="job_actions">
                                                            <?php
                                                            if(Easy_Rents_Public::er_role_check( ['driver'] )){ ?>
                                                                <button class="view">
                                                                <a href="<?php echo the_permalink(); ?>" target="_junu">View</a></button>
                                                                <form action="" method="post">
                                                                    <input type="hidden" value="<?php echo intval(get_post()->ID); ?>" name="erp">

                                                                    <input type="hidden" value="<?php echo intval(get_post()->post_author); ?>" name="erc">

                                                                    <button class="cancel removejob" name="remove">Cancel</button>
                                                                </form>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                            <div class="helper">
                                                No request found
                                            </div>
                                        <?php
                                    }
                                }
                            }
                        }
                    }//My pending requests loop end
                    else{
                        ?>
                        <div class="helper">
                            No Jobs are pending
                            <span>Submit your proposal for getting job.</span>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <div id="running" class="tabelem">
            <?php
                if(Easy_Rents_Public::er_role_check( ['driver'] )){
                    // Checking for running project
                    $acceptedapplications = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_applications WHERE driver_id = {$current_user->ID} AND status = 2 OR status = 4"); 
                
                    // My inprogress loop
                    if($acceptedapplications){
                        foreach($acceptedapplications as $application){

                            $jobs_args = array(
                                'post_type' => 'jobs',
                                'post_status' => 'publish',
                                'post__in' => [$application->post_id],
                                'order'     => 'ASC',
                                'order_by'     => 'date'
                            );

                            // Geting jobs
                            $jobs = new WP_Query($jobs_args);
                            
                            if ( $jobs->have_posts() ){
                                while ( $jobs->have_posts() ){
                                    $jobs->the_post();

                                    $job_info = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}easy_rents_trips WHERE post_id = $application->post_id AND job_status = 'inprogress' ORDER BY ID ASC");
                                    // Only active/ running job
                                    if($job_info){
                                        ?>
                                        <div class="jobitem">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th><strong>Load Location</strong></th>
                                                        <th><strong>Unload Location</strong></th>
                                                        <th><strong>Load Time</strong></th>
                                                        <th><strong>Price</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i> 
                                                            <?php echo __(substr($job_info->location_1,0,29),'easy-rents'); ?>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> 
                                                            <?php echo __(substr($job_info->unload_loc,0,29),'easy-rents'); ?>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-clock-o" aria-hidden="true"></i> 
                                                            <?php echo __($job_info->load_time,'easy-rents'); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo __($application->price,'easy-rents'); ?>tk
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="background: white" colspan="4">
                                                        <span class="applydate"><span class="erbadge-color">Approved: </span><?php 
                                                            echo Easy_Rents_Public::time_elapsed_string(time()-$application->apply_date);
                                                        ?></span>
                                                        <span class="phone">
                                                            ☎️
                                                            <?php
                                                            echo get_user_meta($application->driver_id, 'user_phone_number', true);
                                                            ?>
                                                        </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <div class="job_actions">
                                                            <button class="view">
                                                            <a href="<?php echo the_permalink(); ?>">View</a></button>
                                                            <form action="" method="post">
                                                                <input type="hidden" value="<?php echo intval(get_post()->ID); ?>" name="erp">
                                                                <input type="hidden" value="<?php echo intval(get_post()->post_author); ?>" name="erc">
                                                                <?php
                                                                if($application->status == 2){
                                                                    ?>
                                                                    <button data-id="<?php echo intval($application->ID) ?>" class="finishedjob" name="finishedjob">Finished</button>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <button class="cancel cancelrunningjob" name="cancelrunningjob">Cancel</button>
                                                                <?php
                                                                    if($application->status == 4){
                                                                    ?>
                                                                    <span class="finishedjobreq">Request Pending</span>
                                                                    <?php
                                                                    }
                                                                ?>
                                                            </form>
                                                        </div>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                            <div class="helper">
                                                No request found
                                            </div>
                                        <?php
                                    }
                                }
                            }
                        }
                    }//My running loop end
                    else{
                        ?>
                        <div class="helper">
                            No Job Running
                            <span>Submit your proposal for getting job.</span>
                        </div>
                        <?php
                    }
                }

                if(Easy_Rents_Public::er_role_check( ['customer'] )){
                    // Checking for pending requ
                    $acceptedapplications = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_applications WHERE customer_id = {$current_user->ID} AND status = 2 OR status = 4");
                
                    // My running loop
                    if($acceptedapplications){
                        foreach($acceptedapplications as $application){

                            $jobs_args = array(
                                'post_type' => 'jobs',
                                'post_status' => 'publish',
                                'post__in' => [$application->post_id],
                                'order'     => 'ASC',
                                'order_by'     => 'date'
                            );

                            // Geting jobs
                            $jobs = new WP_Query($jobs_args);
                            
                            if ( $jobs->have_posts() ){
                                while ( $jobs->have_posts() ){
                                    $jobs->the_post();

                                    $job_info = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}easy_rents_trips WHERE post_id = $application->post_id AND job_status = 'inprogress' ORDER BY ID ASC");
                                    // Only active/ running job
                                    if($job_info){
                                        ?>
                                        <div class="jobitem">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th><strong>Trip ID</strong></th>
                                                        <th><strong>Price</strong></th>
                                                        <th><strong>Apply Date</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <?php echo __(the_title(),'easy-rents'); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo __($application->price,'easy-rents'); ?>tk
                                                        </td>
                                                        <td>
                                                        <?php 
                                                        echo Easy_Rents_Public::time_elapsed_string(time()-$application->apply_date);?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <div class="job_actions">
                                                            <button class="view">
                                                            <a href="<?php echo the_permalink(); ?>" target="_junu">View</a></button>
                                                            <form action="" method="post">
                                                                <input type="hidden" value="<?php echo intval(get_post()->ID); ?>" name="erp">

                                                                <input type="hidden" value="<?php echo intval($application->driver_id); ?>" name="erd">
                                                                <?php
                                                                if($application->status == 4){
                                                                ?>
                                                                    <button data-id="<?php echo intval($application->ID) ?>" class="finishedconfirm" name="finishedconfirm">Finished</button>
                                                                <?php
                                                                }
                                                                ?>
                                                            </form>
                                                            <?php
                                                                if($application->status == 4){
                                                                ?>
                                                                    <span class="requestforfinished">Request for finished</span>
                                                                <?php
                                                                }
                                                            ?>
                                                            
                                                        </div>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                            <div class="helper">
                                                No request found
                                            </div>
                                        <?php
                                    }
                                }
                            }
                        }
                    }//My running loop end
                    else{
                        ?>
                        <div class="helper">
                            No Job Running
                        </div>
                        <?php
                    }
                }
            ?>
            </div>

        </main>
    </div>
</section>

<!-- /.content-wrapper -->