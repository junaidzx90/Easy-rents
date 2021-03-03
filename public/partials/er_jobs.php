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
        echo the_title();
        echo the_content();
    endwhile;
else :
    _e( 'Sorry, no job were found.', 'easy-rents' );
endif;
?>
<?php get_footer(); ?>
