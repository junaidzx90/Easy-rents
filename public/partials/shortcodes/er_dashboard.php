<?php
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
<?php global $wpdb,$current_user,$wp_query; ?>
<section>
    <div id="er_profileMain">

        <nav class="ermenu" tabindex="0">
            <div class="smartphone-ermenu-trigger"></div>
            <header class="avatar">
                <img style="width: 100px" src="https://i.pinimg.com/originals/be/ac/96/beac96b8e13d2198fd4bb1d5ef56cdcf.jpg" />
                <h2>John D. <i class="fa fa-check-circle verified" aria-hidden="true"></i></h2>
            </header>
            <ul>
                <li tabindex="0" class="listitem eractive">
                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                    <a href="<?php echo home_url($this->get_post_slug(get_option( 'profile_page', true ))) ?>"> <span class="eractivecolor">Dashboard</span> </a>
                </li>

                <li tabindex="0" class="listitem">
                    <i class="fa fa-tasks" aria-hidden="true"></i>
                    <a href="<?php echo home_url($this->get_post_slug(get_option( 'profile_trips', true ))) ?>"> <span>My Trips</span></a>
                </li>

                <li tabindex="0" class="listitem">
                    <i class="fa fa-institution" aria-hidden="true"></i>
                    <a href="<?php echo home_url($this->get_post_slug(get_option( 'profile_payment', true ))) ?>"><span>Payments</span></a>
                </li>

                <li tabindex="0" class="listitem">
                    <i class="fa fa-user-secret" aria-hidden="true"></i>
                    <a href="<?php echo home_url($this->get_post_slug(get_option( 'erprofile_settings', true ))) ?>"><span>Settings</span></a>
                </li>
            </ul>
        </nav>

        <main>
            <div class="tabs">
                <button class="pending erbtnactive" onclick="er_transform('pending',this)">Pending</button>
                <button class="running" onclick="er_transform('running',this)">Running</button>
            </div>

            <div id="pending" class="tabelem">
                <?php
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
                                $job_info = get_post_meta( get_post()->ID, 'er_job_info' );
                                // Only active/ running job
                                if($job_info[0]['job_status'] == 'running'){
                                    ?>
                                    
                                    <div class="jobitem">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th><strong>Load Location</strong></th>
                                                <th><strong>Unload Location</strong></th>
                                                <th><strong>Load Time</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i> 
                                                    <?php echo __(substr($job_info[0]['location_1'],0,29),'easy-rents'); ?>
                                                </td>
                                                <td>
                                                    <i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> 
                                                    <?php echo __(substr($job_info[0]['unload_location'],0,29),'easy-rents'); ?>
                                                </td>
                                                <td>
                                                    <i class="fa fa-clock-o" aria-hidden="true"></i> 
                                                    <?php echo __($job_info[0]['loading_times'],'easy-rents'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <div class="job_actions">
                                                    <button class="view">
                                                    <a href="<?php echo the_permalink(); ?>">View</a></button>
                                                    <form action="" method="post">
                                                        <input type="hidden" value="<?php echo intval(get_post()->ID); ?>" name="erp">

                                                        <input type="hidden" value="<?php echo intval(get_post()->post_author); ?>" name="erc">

                                                        <button class="cancel removejob" name="remove">Cancel</button>
                                                    </form>
                                                </div>
                                            </tr>
                                        </tbody>
                                    </table>
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
                ?>
            </div>
            

            <div id="running" class="tabelem">
            <?php
                // Checking for pending requ
                $acceptedapplications = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_applications WHERE driver_id = {$current_user->ID} AND status = 2"); 
                
                // My pending requests loop
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
                                $job_info = get_post_meta( get_post()->ID, 'er_job_info' );
                                // Only active/ running job
                                if($job_info[0]['job_status'] == 'inprogress'){
                                    ?>
                                    
                                    <div class="jobitem">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th><strong>Load Location</strong></th>
                                                <th><strong>Unload Location</strong></th>
                                                <th><strong>Load Time</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i> 
                                                    <?php echo __(substr($job_info[0]['location_1'],0,29),'easy-rents'); ?>
                                                </td>
                                                <td>
                                                    <i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> 
                                                    <?php echo __(substr($job_info[0]['unload_location'],0,29),'easy-rents'); ?>
                                                </td>
                                                <td>
                                                    <i class="fa fa-clock-o" aria-hidden="true"></i> 
                                                    <?php echo __($job_info[0]['loading_times'],'easy-rents'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <div class="job_actions">
                                                    <button class="view">
                                                    <a href="<?php echo the_permalink(); ?>">View</a></button>
                                                    <form action="" method="post">
                                                        <input type="hidden" value="<?php echo intval(get_post()->ID); ?>" name="erp">
                                                        <input type="hidden" value="<?php echo intval(get_post()->post_author); ?>" name="erc">
                                                        <button class="finished" name="finished">Finished</button>
                                                        <button class="cancel" name="cancel">Cancel</button>
                                                    </form>
                                                </div>
                                            </tr>
                                        </tbody>
                                    </table>
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
                        No Job Running
                        <span>Submit your proposal for getting job.</span>
                    </div>
                    <?php
                }
                ?>
            </div>

        </main>
    </div>
</section>

<!-- /.content-wrapper -->