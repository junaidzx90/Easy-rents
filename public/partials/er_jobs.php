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
            global $wp_query;
            $jobs_args = array(
                'post_type' => 'jobs',
                'post_status' => 'publish'
            );
            $jobs = new WP_Query($jobs_args);
            if ( $jobs->have_posts() ) :
                while ( $jobs->have_posts() ) : $jobs->the_post();
                $job_info = get_post_meta( get_post()->ID, 'er_job_info' ); ?>
                    <div class="er_jobItem">
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
                                        <?php echo $job_info[0]['location_1']; ?>
                                    </span>
                                </div>
                                
                                <div class="_jobitem">
                                    <span class="er_location">
                                        <i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> 
                                        <?php echo $job_info[0]['unload_location']; ?>
                                    </span>
                                </div>
                                <div class="_jobitem">
                                    <span class="er_time">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i> 
                                    <?php echo $job_info[0]['loading_times']; ?>
                                    </span>
                                </div>
                                <div class="_jobitem">
                                    <span class="er_weight">
                                    <i class="fa fa-cubes" aria-hidden="true"></i> <?php echo $job_info[0]['goods_weight']; ?> Ton
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