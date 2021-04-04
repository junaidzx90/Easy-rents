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

    $exists = $wpdb->get_var("SELECT post_id FROM {$wpdb->prefix}easy_rents_applications WHERE status > 2");

    $postinfo = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}easy_rents_trips WHERE post_id = $post_id ORDER BY ID ASC");
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
                    $totalprice =  $myprice * $commission / 100 + $myprice;

                    if(empty($application) && !$exists || $application == ""){

                        $apply = $wpdb->insert("{$wpdb->prefix}easy_rents_applications", 
                                array("driver_id" => $current_user->ID,
                                    "customer_id" => get_post()->post_author,
                                    "post_id" => get_post()->ID,
                                    "price" => $totalprice,
                                    "net_price" => $myprice,
                                    "commrate" => $commission,
                                    "status" => 1,
                                ),
                                array("%d", "%d", "%d","%f","%d"));

                        if($apply){
                            $redirect_page = Easy_Rents_Public::get_post_slug(get_option( 'trips_page', true ));
						    wp_safe_redirect( home_url( '/'.$redirect_page.'/'.get_the_title(  ) ) );
                        }else{
                            $msg = "দুঃক্ষিত! আবার চেষ্টা করুণ। 👍";
                        }
                    }

                    if ( is_wp_error( $application ) ) {
                        throw new Exception( "দুঃক্ষিত! 😥" );
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

            // Only active/ running job
            if($postinfo->job_status == 'running' && !$application){ ?>
                <div class="bidelem">
                    <table>
                        <tbody>
                        <tr>
                            <th>কমিশন</th>
                            <th>কমিশনের টাকা</th>
                            <th>ক্লাইন্ট দেখবে</th>
                        </tr>
                        <tr>
                            <td>
                                <span class="parcents"><?php echo get_option('job_commission'); ?></span>% = <span class="commrate">0</span>টাকা
                        </td>
                            <td>
                               + <span class="myrate">0</span>টাকা
                            </td>
                            <td>= <span class="sumwithcomm">0</span>টাকা</td>
                        </tr>
                        </tbody>
                    </table>

                    <h1><?php echo __('Write your budget', 'easy-rents') ?></h1>
                    <form action="" method="post">
                        <input type="number" name="myprice" id="myprice" placeholder="Write your budget">
                        <input type="submit" class="bidbtn" name="bidbtn" value="জমা দিন">
                    </form>
                </div>
            <?php
            }else{
                print_r("ট্রিপটি চালু নেই!");
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

            <?php
                
                if(!empty($postinfo)){ 
                    ?>
                    
                        <div class="locations">
                            <div class="loadpoint">
                                <h4> <i class="fas fa-map-marked-alt"></i> লোডের স্থান</h4>
                                <ul>
                                    <?php
                                    if(!empty($postinfo->location_1)){
                                        echo '<li><i class="fas fa-arrow-alt-circle-up"></i> '.__($postinfo->location_1, 'easy-rents').'</li>';
                                    }

                                    if(!empty($postinfo->location_2)){
                                        echo '<li><i class="fas fa-arrow-alt-circle-up"></i> '.__($postinfo->location_2, 'easy-rents').'</li>';
                                    }

                                    if(!empty($postinfo->location_3)){
                                        echo '<li><i class="fas fa-arrow-alt-circle-up"></i> '.__($postinfo->location_3, 'easy-rents').'</li>';
                                    }
                                    ?>
                                </ul>
                            </div>

                            <div class="unloadpoint">
                                <h4> <i class="fas fa-map-marked-alt"></i> আনলোডের স্থান</h4>
                                <ul>
                                    <?php
                                    if(!empty($postinfo->unload_loc)){
                                        echo '<li><i class="fas fa-arrow-alt-circle-down"></i> '.__($postinfo->unload_loc, 'easy-rents').'</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>

                        <div class="otherinfo">
                            <div class="jobinfoitem">
                                <h4 class="infotitle"><i class="fas fa-cubes" aria-hidden="true"></i> ওজন</h4>
                                 <?php
                                    if(!empty($postinfo->weight)){
                                        echo '<span>'.intval($postinfo->weight).' টন</span>';
                                    }
                                 ?>
                            </div>

                            <div class="jobinfoitem">
                                <h4 class="infotitle"><i class="fas fa-people-carry"></i> লেবার</h4>
                                <?php
                                if(!empty($postinfo->er_labore)){
                                    echo '<span>'.intval($postinfo->laborer).' জন</span>';
                                }else{
                                    echo '<span>'.intval($postinfo->laborer).' জন</span>';
                                }
                                ?>
                            </div>

                            <div class="jobinfoitem">
                                <h4 class="infotitle"><i class="fas fa-truck" aria-hidden="true"></i> ট্রাকের ধরণ</h4>

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
                                <h4 class="infotitle"><i class="fas fa-luggage-cart"></i> মালের ধরণ</h4>
                                <?php
                                if(!empty($postinfo->goods_type)){
                                    echo '<span>'.__($postinfo->goods_type,'easy-rents').'</span>';
                                }
                                ?>
                            </div>

                            <div class="jobinfoitem">
                                <h4 class="infotitle"><i class="far fa-clock"></i> লোডের সময়</h4>
                                <?php
                                if(!empty($postinfo->load_time)){
                                    echo '<span>'.__($postinfo->load_time,'easy-rents').'</span>';
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
                                    <span class="mycar">ট্রাক নাম্বার: T-54545</span>
                                </div>
                                <?php
                            }else{
                                if(is_user_logged_in(  )){
                                    echo __($current_user->user_nicename,'easy-rents');
                                }else{
                                    ?>
                                    <div class="myinfo">
                                        <h3><a href="<?php echo esc_url( home_url('/login') ) ?>">লগিন</a></h3>
                                    </div>
                                    <?php
                                }
                            }

                            // Apply btn
                            if($application == "" && is_user_logged_in(  ) && Easy_Rents_Public::er_role_check( ['driver'] )){
                                $myexiststrip = $wpdb->get_var("SELECT post_id FROM {$wpdb->prefix}easy_rents_applications WHERE driver_id = $current_user->ID AND status = 4 OR status = 2");

                                if($myexiststrip){
                                    ?>
                                    <div class="applybtn">
                                        <span class="jobapply" name="jobapply">দুঃক্ষিত! আপনি কারো সাথে চুক্তিবদ্ধ হয়েছেন।</span>
                                    </div>
                                    <?php
                                }else{
                                    $paymentstatus = $wpdb->get_results("SELECT payment FROM {$wpdb->prefix}easy_rents_applications WHERE driver_id = $current_user->ID AND status = 3 AND payment = 0");
                                    
                                    if(count($paymentstatus) > 0){
                                        ?>
                                        <div class="applybtn">
                                            <span class="jobapply" name="jobapply">বঁকেয়া বিল পরিশোধ করে আবার চেষ্টা করুণ। <a href="<?php echo esc_url(home_url(Easy_Rents_Public::get_post_slug(get_option( 'profile_payment', true )))) ?>">পরিশোধ করুণ!</a></span>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                        <div class="applybtn">
                                            <form action="" method="post">
                                                <button class="jobapply" name="jobapply">আবাদন করুণ</button>
                                            </form>
                                        </div>
                                        <?php
                                    }
                                    
                                }
                            }else{
                                // checking for job status
                                if($application == 1 && Easy_Rents_Public::er_role_check( ['driver'] )){
                                    if($postinfo->job_status == 'running'){
                                    ?>
                                        <!-- Disabled btn for existing applications -->
                                        <div class="applybtn">
                                            <button disabled class="jobapply disabledbtn">অপেক্ষমান</button>
                                        </div>
                                    <?php
                                    }
                                }else{
                                    if($application == 2 && Easy_Rents_Public::er_role_check( ['driver'] )){
                                        if($postinfo->job_status == 'inprogress'){
                                        ?>
                                            <!-- Disabled btn for existing applications -->
                                            <div class="applybtn">
                                                <button disabled class="jobapply disabledbtn">আপনি কারো সাথে চুক্তিবদ্ধ।</button>
                                            </div>
                                        <?php
                                        }
                                    }else{
                                        if(Easy_Rents_Public::er_role_check( ['customer'] )){
                                            // This for logged out users
                                            ?>
                                                <!-- Disabled btn for existing applications -->
                                                <div class="applybtn">
                                                    <button disabled class="jobapply disabledbtn">☕</button>
                                                </div>
                                            <?php
                                        }else{
                                            // This for logged out users
                                            ?>
                                                <!-- Disabled btn for existing applications -->
                                                <div class="applybtn">
                                                    <button disabled class="jobapply disabledbtn">আবেদন করুণ</button>
                                                </div>
                                            <?php
                                        }
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