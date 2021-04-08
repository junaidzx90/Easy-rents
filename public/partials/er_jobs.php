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
    <h1>চালু ট্রিপগুলি</h1>
    <div id="er_jobs_section">
        <div class="er_jobs_content">
            <?php
            global $wp_query,$wpdb,$current_user;

            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $jobs_args = array(
                'post_type' => 'jobs',
                'post_status' => 'publish',
                'order'     => 'DESC',
                'paged'     => $paged,
                'posts_per_page'     => 12,
                'order_by'     => 'date'
            );
            $jobs = new WP_Query($jobs_args);

            if ( $jobs->have_posts() ) :
                while ( $jobs->have_posts() ) : $jobs->the_post();
                    $post_id = get_post()->ID;

                    $tripinfo = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}easy_rents_trips WHERE post_id = $post_id ORDER BY ID ASC");

                    
                    // Only active/ running job
                    $nofound ="";
                    if($tripinfo->job_status == 'running'){
                        
                        $myapplication = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = {$post_id} AND driver_id = {$current_user->ID}"); ?>

                        <div class="er_jobItem">
                            <span class="erjobstatus"><?php 
                            if(!empty($myapplication)){
                                echo __('অপেক্ষমান','easy-rents');
                            }
                            if(get_post()->post_author == $current_user->ID){
                                echo __('আমার ট্রিপ','easy-rents');
                            } 
                            ?></span>
                            <a href="<?php echo the_permalink(  ); ?>">
                                <div class="er_jobimg">
                                    <?php
                                    $product_terms = wp_get_object_terms( $post->ID,  'truckstype' );

                                    if ( ! empty( $product_terms ) ) {
                                        if ( ! is_wp_error( $product_terms ) ) {
                                            foreach( $product_terms as $term ) {
                                                echo '<div class="erjobitemimg">';
                                                if(!empty(get_term_meta( $term->term_id, 'term_image', true )) || get_term_meta( $term->term_id, 'term_image', true ) != 0){
                                                    echo '<img width="150" src="'.get_term_meta( $term->term_id, 'term_image', true ).'" alt="'.esc_url( get_term_link( $term->slug, 'truckstype' ) ).'">';
                                                }else{
                                                    echo '<img width="150" src="'.ER_PATH.'public/images/truck.PNG" alt="">';
                                                }
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
                                            <i class="fas fa-arrow-alt-circle-up"></i> 
                                            <?php echo __(substr($tripinfo->location_1,0,29),'easy-rents'); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="_jobitem">
                                        <span class="er_location">
                                            <i class="fas fa-arrow-alt-circle-down"></i> 
                                            <?php echo __(substr($tripinfo->unload_loc,0,29),'easy-rents'); ?>
                                        </span>
                                    </div>
                                    <div class="_jobitem">
                                        <span class="er_time">
                                        <i class="far fa-clock"></i>
                                        <?php echo __($tripinfo->load_time,'easy-rents'); ?>
                                        </span>
                                    </div>
                                    <div class="_jobitem">
                                        <span class="er_weight">
                                        <i class="fa fa-balance-scale" aria-hidden="true"></i> <?php echo __($tripinfo->weight,'easy-rents'); ?> টন
                                        </span>
                                    </div>
                                    <div class="_jobitem">
                                        <span class="er_laborer">
                                            <i class="fa fa-people-carry"></i>
                                            লেবার
                                            <?php echo ($tripinfo->laborer > 0)? '<i class="fas fa-check-circle laboriconcheck"></i>':'<i class="fas fa-times-circle laboriconnone"></i>' ?>
                                        </span>
                                    </div>
                                </div>
                            </a>

                        </div>
                    <?php  
                    }else{
                        $nofound = 'দুঃখিত! কোন ট্রিপ চালু নেই ।';
                    }
                        
                    
                endwhile;

                echo '<div class="pagination"> <div class="paginate">';
                if($jobs->max_num_pages > 1){
                    global $wp_query;
 
                    $big = 999999999; // need an unlikely integer
                    $translated = __( 'Page', 'easy-rents' ); // Supply translatable string
                    
                    echo paginate_links( array(
                        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format' => '?paged=%#%',
                        'current' => max( 1, get_query_var('paged') ),
                        'total' => $jobs->max_num_pages,
                            'before_page_number' => '<span class="screen-reader-text">'.$translated.' </span>'
                    ) );
                }
                echo '</div></div>';
                wp_reset_postdata(  );
                echo $nofound;
                else :
                    _e( 'দুঃখিত! কোন ট্রিপ চালু নেই ।', 'easy-rents' );
            endif;
            ?>
        </div>

        <div class="er_sidebar">
            <?php the_content(); ?>
        </div>
    </div> 
</section>
<?php get_footer(); ?>