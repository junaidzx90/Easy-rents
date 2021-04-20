<?php
if (!Easy_Rents_Public::er_role_check(['customer'])) {
    wp_safe_redirect(home_url(Easy_Rents_Public::get_post_slug(get_option('profile_page', true))));
    exit;
}
/**
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/public/partials/er_addtrip
 * */
?>
<?php ob_start();?>
<?php get_header();?>
<?php do_shortcode( '[addjob_form]' ); ?>
<?php get_footer();?>