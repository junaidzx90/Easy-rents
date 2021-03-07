<?php
 /** 
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/public/partials/er_trips
 * */
 ?>
 <?php get_header(); ?>
 <?php wp_enqueue_style( 'er_jobs_style' ); ?>
 <?php wp_enqueue_script( 'er_jobs_script' ); ?>

<section>
    <h1>All trips</h1>
    <div id="er_jobs_section">
        <div class="er_jobs_content">
            <?php
            global $wp_query,$wpdb,$current_user;
            $jobs_args = array(
                'post_type' => 'jobs',
                'post_status' => 'publish',
                'order'     => 'ASC',
                'order_by'     => 'date'
            );
            $jobs = new WP_Query($jobs_args);
            if ( $jobs->have_posts() ) :
                while ( $jobs->have_posts() ) : $jobs->the_post();
                $job_info = get_post_meta( get_post()->ID, 'er_job_info' );
                    // Only active/ running job
                    if($job_info[0]['job_status'] == 'running'){
                        $post_id = get_post()->ID;
                        $myapplication = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = {$post_id} AND driver_id = {$current_user->ID}"); ?>

                        <div class="er_jobItem <?php echo (!empty($myapplication)?'disabled':'') ?>">
                            <a href="<?php echo the_permalink(  ); ?>">
                                <div class="er_jobimg">
                                    <?php
                                    $product_terms = wp_get_object_terms( $post->ID,  'truckstype' );

                                    if ( ! empty( $product_terms ) ) {
                                        if ( ! is_wp_error( $product_terms ) ) {
                                            foreach( $product_terms as $term ) {
                                                echo '<div class="erjobitemimg">';
                                                echo '<img width="150" src="'.get_term_meta( $term->term_id, 'term_image', true ).'" alt="'.esc_url( get_term_link( $term->slug, 'truckstype' ) ).'">';
                                                echo '</div>';
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </a>

                            <a href="<?php echo the_permalink(  ); ?>">
                                <div class="jobinfotexts">
                                    <div class="_jobitem">
                                        <span class="er_location">
                                            <i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i> 
                                            <?php echo __(substr($job_info[0]['location_1'],0,29),'easy-rents'); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="_jobitem">
                                        <span class="er_location">
                                            <i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> 
                                            <?php echo __(substr($job_info[0]['unload_location'],0,29),'easy-rents'); ?>
                                        </span>
                                    </div>
                                    <div class="_jobitem">
                                        <span class="er_time">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i> 
                                        <?php echo __($job_info[0]['loading_times'],'easy-rents'); ?>
                                        </span>
                                    </div>
                                    <div class="_jobitem">
                                        <span class="er_weight">
                                        <i class="fa fa-cubes" aria-hidden="true"></i> <?php echo __($job_info[0]['goods_weight'],'easy-rents'); ?> Ton
                                        </span>
                                    </div>
                                    <div class="_jobitem">
                                        <span class="er_laborer">
                                            <i class="fa fa-people-carry"></i>
                                            Laborer
                                            <?php echo ($job_info[0]['er_labore'] != "")? '<i class="fas fa-check-circle laboriconcheck"></i>':'<i class="fas fa-times-circle laboriconnone"></i>' ?>
                                        </span>
                                    </div>
                                </div>
                            </a>

                        </div>
                    <?php   
                    }
                endwhile;
                else :
                    _e( 'Sorry, no job were found.', 'easy-rents' );
            endif;
            ?>
        </div>

        <div class="er_sidebar">
            <?php echo the_content(); ?>
        </div>
    </div> 
</section>
<?php get_footer(); ?>