<?php ob_start(); ?>
<?php get_header(); ?>
<?php wp_enqueue_style( 'er_jobs_style' ); ?>
<?php wp_enqueue_script( 'er_jobs_script' ); ?>
<?php
// If have post start
if(have_posts()){
    global $wpdb,$current_user;
    $post_id = get_post()->ID;
    $msg = "";
    $application = $wpdb->get_var("SELECT status FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = $post_id AND driver_id = {$current_user->ID}");
?>
<section>
    <div id="er_jobs_section">
        <?php
        // Job apply STEP 2
        if(isset($_POST['bidbtn'])){
            if(!is_user_logged_in(  ) && !Easy_Rents_Public::er_role_check( ['partner','driver'] )){
                wp_safe_redirect( home_url('/'));
                exit;
            }

            if(isset($_POST['myprice']) && !empty($_POST['myprice'])){
                $myprice = sanitize_text_field( intval($_POST['myprice']) );
                
                try {
                    $commission = get_option('job_commission');
                    $totalprice =  $myprice + $myprice * $commission / 100;

                    if(empty($application) || $application == ""){

                        $apply = $wpdb->insert("{$wpdb->prefix}easy_rents_applications", 
                                array("driver_id" => $current_user->ID,
                                    "customer_id" => get_post()->post_author,
                                    "post_id" => get_post()->ID,
                                    "price" => $totalprice,
                                    "status" => 1,
                                ),
                                array("%d", "%d", "%d","%f","%d"));

                        if($apply){
                            $redirect_page = Easy_Rents_Public::get_post_slug(get_option( 'trips_page', true ));
						    wp_safe_redirect( home_url( '/'.$redirect_page.'/'.get_the_title(  ) ) );
                        }else{
                            $msg = "Sorry You made some problems!";
                        }
                    }

                    if ( is_wp_error( $application ) ) {
                        throw new Exception( "Something problems" );
                    }
                }
                
                //catch exception
                catch(Exception $e) {
                    echo 'Message: ' .$e->getMessage();
                }
            }
        }

        // Job apply STEP 1
        if(isset($_POST['jobapply'])){
            if(!is_user_logged_in(  ) && !Easy_Rents_Public::er_role_check( ['partner','driver'] )){
                wp_safe_redirect( home_url('/'));
                exit;
            }

            $job_status = get_post_meta( get_post()->ID, 'er_job_info' );
            // Only active/ running job
            if($job_status[0]['job_status'] == 'running' && !$application){ ?>
                <div class="bidelem">
                    <table>
                        <tbody>
                        <tr>
                            <th>Commission</th>
                            <th>Commission Money</th>
                            <th>Customer see</th>
                        </tr>
                        <tr>
                            <td>
                                <span class="parcents"><?php echo get_option('job_commission'); ?></span>% = <span class="commrate">0</span>tk
                        </td>
                            <td>
                               + <span class="myrate">0</span>tk
                            </td>
                            <td>= <span class="sumwithcomm">0</span>tk</td>
                        </tr>
                        </tbody>
                    </table>

                    <h1><?php echo __('Write your budget', 'easy-rents') ?></h1>
                    <form action="" method="post">
                        <input type="number" name="myprice" id="myprice" placeholder="Write your budget">
                        <input type="submit" class="bidbtn" name="bidbtn" value="BID">
                    </form>
                </div>
            <?php
            }else{
                print_r("This job ended");
            }
        }else{ ?>
            <div class="er_jobs_content single_page_job">
            <?php
                // Showing notify for job status
                if($application == 1){
                    echo '<div class="warning">Your request currently pending!</div>';
                }
                if($application == 2){
                    echo '<div class="warning working">You currently working!</div>';
                }
            ?>
                <!-- Showing form information -->
                <div class="msghandler">
                    <?php
                        if(!empty($msg)){
                            echo '<div class="warning">'. __($msg,'easy-rents').'</div>';
                        }
                    ?>
                </div>

                <h1><?php echo __('JOB Informations', 'easy-rents') ?></h1>
            <?php
                $postinfo = get_post_meta( get_post()->ID, 'er_job_info' );
                if(!empty($postinfo)){ 
                    $jobitem = $postinfo[0];
                    ?>
                    
                        <div class="locations">
                            <div class="loadpoint">
                                <h4> <i class="fa fa-map-marker" aria-hidden="true"></i> Load point</h4>
                                <ul>
                                    <?php
                                    if(!empty($jobitem['location_1'])){
                                        echo '<li><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i> '.__($jobitem['location_1'], 'easy-rents').'</li>';
                                    }

                                    if(!empty($jobitem['location_2'])){
                                        echo '<li><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i> '.__($jobitem['location_2'], 'easy-rents').'</li>';
                                    }

                                    if(!empty($jobitem['location_3'])){
                                        echo '<li><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i> '.__($jobitem['location_3'], 'easy-rents').'</li>';
                                    }
                                    ?>
                                </ul>
                            </div>

                            <div class="unloadpoint">
                                <h4> <i class="fa fa-map-marker" aria-hidden="true"></i> Unload point</h4>
                                <ul>
                                    <?php
                                    if(!empty($jobitem['unload_location'])){
                                        echo '<li><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> '.__($jobitem['unload_location'], 'easy-rents').'</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>

                        <div class="otherinfo">
                            <div class="jobinfoitem">
                                <h4 class="infotitle"><i class="fa fa-cubes" aria-hidden="true"></i> Weights</h4>
                                 <?php
                                    if(!empty($jobitem['goods_weight'])){
                                        echo '<span>'.intval($jobitem['goods_weight']).' Ton</span>';
                                    }
                                 ?>
                            </div>

                            <div class="jobinfoitem">
                                <h4 class="infotitle"><i class="fa fa-people-carry"></i> Laborer</h4>
                                <?php
                                if(!empty($jobitem['er_labore'])){
                                    echo '<span>'.intval($jobitem['er_labore']).' Labores</span>';
                                }else{
                                    echo '<span>'.intval($jobitem['er_labore']).' Labores</span>';
                                }
                                ?>
                            </div>

                            <div class="jobinfoitem">
                                <h4 class="infotitle"><i class="fa fa-truck" aria-hidden="true"></i> Truck Type</h4>

                                <?php
                                $product_terms = wp_get_object_terms( get_post()->ID,  'truckstype' );

                                if ( ! empty( $product_terms ) ) {
                                    if ( ! is_wp_error( $product_terms ) ) {
                                        foreach( $product_terms as $term ) {
                                            echo '<span>'. __(ucfirst($term->name),'easy-rents').'</span>';
                                        }
                                    }
                                }
                                ?>
                            </div>

                            <div class="jobinfoitem">
                                <h4 class="infotitle"><i class="fa fa-houzz" aria-hidden="true"></i> Goods Type</h4>
                                <?php
                                if(!empty($jobitem['goods_type'])){
                                    echo '<span>'.__($jobitem['goods_type'],'easy-rents').'</span>';
                                }
                                ?>
                            </div>

                            <div class="jobinfoitem">
                                <h4 class="infotitle"><i class="fa fa-clock-o" aria-hidden="true"></i> Load time</h4>
                                <?php
                                if(!empty($jobitem['loading_times'])){
                                    echo '<span>'.__($jobitem['loading_times'],'easy-rents').'</span>';
                                }
                                ?>
                            </div>
                        </div>

                        <hr class="erhr">

                        <div class="jobbottom">

                            <?php
                            if(is_user_logged_in(  ) && Easy_Rents_Public::er_role_check( ['driver','partner'] )){
                                ?>
                                <div class="myinfo">
                                    <h3><?php echo __($current_user->user_nicename,'easy-rents'); ?> <i class="fa fa-check-circle green" aria-hidden="true"></i></h3>
                                    <span class="mycar">TRUCK: T-54545</span>
                                </div>
                                <?php
                            }else{
                                if(is_user_logged_in(  )){
                                    echo __($current_user->user_nicename,'easy-rents');
                                }else{
                                    ?>
                                    <div class="myinfo">
                                        <h3><a href="<?php echo esc_url( home_url('/login') ) ?>">Login</a></h3>
                                    </div>
                                    <?php
                                }
                            }

                            if($application == "" && is_user_logged_in(  ) && Easy_Rents_Public::er_role_check( ['driver','partner'] )){ ?>

                                <div class="applybtn">
                                    <form action="" method="post">
                                        <button class="jobapply" name="jobapply">Apply</button>
                                    </form>
                                </div>

                            <?php
                            }else{
                                // checking for job status
                                if($application == 1){
                                    if($jobitem['job_status'] == 'running'){
                                    ?>
                                        <!-- Disabled btn for existing applications -->
                                        <div class="applybtn">
                                            <button disabled class="jobapply disabledbtn">Pending</button>
                                        </div>
                                    <?php
                                    }
                                }else{
                                    if($application == 2){
                                        if($jobitem['job_status'] == 'inprogress'){
                                        ?>
                                            <!-- Disabled btn for existing applications -->
                                            <div class="applybtn">
                                                <button disabled class="jobapply disabledbtn">You working</button>
                                            </div>
                                        <?php
                                        }
                                    }else{
                                        // This for logged out users
                                        ?>
                                            <!-- Disabled btn for existing applications -->
                                            <div class="applybtn">
                                                <button disabled class="jobapply disabledbtn">Apply</button>
                                            </div>
                                        <?php
                                    }
                                }
                            } ?>
                        </div>

                    <?php
                }
            ?>
            </div>
            <?php
        }
        ?>
        <div class="er_sidebar single_job_pricebox">
            <?php echo the_content(); ?>
        </div>

    </div>
</section>
<?php  
}// End If have post start ?>
<?php get_footer(); ?>