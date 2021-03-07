<?php ob_start(); ?>
<?php get_header(); ?>
<?php wp_enqueue_style( 'er_jobs_style' ); ?>
<?php wp_enqueue_script( 'er_jobs_script' ); ?>
<?php
// If have post start
if(have_posts()){
?>
<section>
    <div id="er_jobs_section">
        <?php
        // Bid apply
        if(isset($_POST['bidbtn'])){
            if(isset($_POST['myprice']) && !empty($_POST['myprice'])){
                $myprice = sanitize_text_field( intval($_POST['myprice']) );
                $saveprice = get_post_meta( get_post()->ID, 'erjob_price', true);
                if(empty($saveprice)){
                    $commission = get_option('job_commission');
                    $totalprice =  $myprice + $myprice * $commission / 100;

                    

                    $redirect_page = Easy_Rents_Public::get_post_slug(get_option( 'trips_page', true ));
                    wp_safe_redirect( home_url( '/'.$redirect_page ) );
                }
            }
        }

        // Job apply
        if(isset($_POST['jobapply'])){
            $job_status = get_post_meta( get_post()->ID, 'er_job_info' );
            // Only active/ running job
            if($job_status[0]['job_status'] == 'running'){ ?>
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
                            <div class="myinfo">
                                <h3>Myname <i class="fa fa-check-circle green" aria-hidden="true"></i></h3>
                                <span class="mycar">TRUCK: T-54545</span>
                            </div>
                            <div class="applybtn">
                                <form action="" method="post">
                                    <button class="jobapply" name="jobapply">Apply</button>
                                </form>
                            </div>
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