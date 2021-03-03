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
<h1>all trips</h1>

<?php
global $wp_query;
$jobs_args = array(
    'post_type' => 'erjobs',
    'post_status' => 'publish'
);
$jobs = new WP_Query($jobs_args);
if ( $jobs->have_posts() ) :
    while ( $jobs->have_posts() ) : $jobs->the_post();
        echo '<h1>'.__(the_title(),'easy-rents').'</h1>';

        $job_info = get_post_meta( get_post()->ID, 'er_job_info' );
        echo $job_info[0]['location_1'];
        echo $job_info[0]['location_2'];
        echo $job_info[0]['location_3'];
        
    endwhile;
else :
    _e( 'Sorry, no job were found.', 'easy-rents' );
endif;
?>
<?php get_footer(); ?>
