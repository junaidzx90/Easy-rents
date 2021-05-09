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

<section id="trips_section">
    <div class="container">
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

                        if($tripinfo){
                            // Only active/ running job
                            $nofound ="";
                            if($tripinfo->job_status == 'running'){
                                
                                $myapplication = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = {$post_id} AND driver_id = {$current_user->ID}"); ?>

                                <div class="er_jobItem">
                                    <span class="erjobstatus"><?php 
                                    if(!empty($myapplication)){
                                        _e('অপেক্ষমান','easy-rents');
                                    }
                                    if(get_post()->post_author == $current_user->ID){
                                        _e('আমার ট্রিপ','easy-rents');
                                    } 
                                    ?></span>
                                    <a href="<?php echo the_permalink(  ); ?>">
                                        <div class="er_jobimg">
                                            <?php
                                            $product_terms = wp_get_object_terms( $post->ID,  'truckstype' );

                                            if ( ! empty( $product_terms ) ) {
                                                if ( ! is_wp_error( $product_terms ) ) {
                                                    foreach( $product_terms as $term ) {
                                                        echo '<div class="erjobitemimg" style="background-image: url('.((!empty(get_term_meta( $term->term_id, 'term_image', true )) || get_term_meta( $term->term_id, 'term_image', true ) != 0) ? get_term_meta( $term->term_id, 'term_image', true ) : '').')">';
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
                                                    <?php $arr = explode(',', $tripinfo->location_1);
                                                    _e( count($arr) > 1?$arr[0].','.$arr[1]:$arr[0],'easy-rents'); ?>
                                                </span>
                                            </div>
                                            
                                            <div class="_jobitem">
                                                <span class="er_location">
                                                    <i class="fas fa-arrow-alt-circle-down"></i>
                                                    <?php $arr = explode(',', $tripinfo->unload_loc);
                                                    _e( count($arr) > 1?$arr[0].','.$arr[1]:$arr[0],'easy-rents'); ?>
                                                </span>
                                            </div>
                                            <div class="_jobitem">
                                                <span class="er_time">
                                                <i class="far fa-clock"></i>
                                                <?php _e($tripinfo->load_time,'easy-rents'); ?>
                                                </span>
                                            </div>
                                            <div class="_jobitem">
                                                <span class="er_weight">
                                                <i class="fa fa-balance-scale" aria-hidden="true"></i> <?php _e($tripinfo->weight,'easy-rents'); ?> টন
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
        </div>
    </div>
</section>
<?php get_footer(); ?>