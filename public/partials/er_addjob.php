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
<?php wp_enqueue_style('select2');?>
<?php wp_enqueue_style('jquery-ui');?>
<?php wp_enqueue_style('select2.min');?>
<?php wp_enqueue_style('jquery.timepicker.min');?>
<?php wp_enqueue_style('er_addjob_style');?>

<?php wp_enqueue_script('select2');?>
<?php wp_enqueue_script('jquery-ui');?>
<?php wp_enqueue_script('select2.min');?>
<?php wp_enqueue_script('jquery.timepicker.min');?>
<?php wp_enqueue_script('er_addjob_script');
wp_localize_script('er_addjob_script', "addjob_ajaxurl", array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('ajax-nonce'),
));
//  Get form data to upload database

?>

<section id="ermainsection">
    <div id="eraddjob">
        <!-- Form -->
        <div class="additemform">
            <h1><?php _e('ট্রাক আবেদন ফর্ম', 'easy-rents')?></h1>
            <form action="" method="post" id="addjobform">
                <div class="erform_items">

                    <div class="locations">

                        <label for="location_1"><?php _e('<i class="fas fa-arrow-alt-circle-up"></i> লোডের স্থান', 'easy-rents')?></label>
                        <?php print_r(Easy_Rents_Public::er_prelocation_input('location_1', 'স্থানের নাম', 'loc1'));?>

                        <label for="location_2"><?php _e('<i class="fas fa-arrow-alt-circle-up"></i> আরো লোডের স্থান', 'easy-rents')?> <small class="optional">( গুরুত্বপুর্ণ নয়)</small></label>
                        <?php print_r(Easy_Rents_Public::er_prelocation_input('location_2', 'স্থানের নাম', 'loc2'));?>

                        <label for="location_3"><?php _e('<i class="fas fa-arrow-alt-circle-up"></i> আরো লোডের স্থান', 'easy-rents')?> <small class="optional">( গুরুত্বপুর্ণ নয় )</small></label>
                        <?php print_r(Easy_Rents_Public::er_prelocation_input('location_3', 'স্থানের নাম', 'loc3'));?>

                        <label for="unload_location"><?php _e('<i class="fas fa-arrow-alt-circle-down"></i> আনলোডের স্থান', 'easy-rents')?></label>
                        <?php print_r(Easy_Rents_Public::er_prelocation_input('unload_location', 'স্থানের নাম', 'unload_loc'));?>

                        <div class="input-group">
                            <label class="datetimelbl" for="loading_time"><?php _e('<i class="far fa-clock"></i> লোডের সময়/তারিখ', 'easy-rents')?> </label>
                            <div class="datetimewrap">
                                <input class="required" required type="text" autocomplete="off" name="loading_time" id="loading_time"
                                    placeholder="সময় নির্বাচন করুন">
                                <input class="required" required type="text" autocomplete="off" name="loading_date" id="loading_date"
                                    placeholder="তারিখ নির্বাচন করুন">
                            </div>
                        </div>
                    </div>

                    <div class="erkobinfo">
                        <div class="input-group">
                            <label for="truck_type"><?php _e('<i class="fas fa-truck" aria-hidden="true"></i> ট্রাক', 'easy-rents')?></label>
                            <select required name="truck_type" id="truck_type" class="required">
                                <option value=""><?php _e('ট্রাক বাছুন', 'easy-rents')?></option>
                                <?php
$args = array(
    'taxonomy' => 'truckstype',
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => false,
);
$the_query = new WP_Term_Query($args);
foreach ($the_query->get_terms() as $term) {

    echo '<option value="' . intval($term->term_id) . '">' . ucfirst($term->name) . '</option>';

}
?>
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="er_goodssizes"><?php _e('<i class="fas fa-cubes"></i> আয়োতন', 'easy-rents')?></label>
                            <input class="" type="number" name="er_goodssizes" id="er_goodssizes" placeholder="আয়োতন" autocomplete="off">
                        </div>

                        <div class="input-group">
                            <label for="goods_type"><?php _e('<i class="fas fa-luggage-cart"></i> মালের ধরন', 'easy-rents')?></label>
                            <input class="required" required type="text" name="goods_type" id="goods_type" placeholder="বাসা পরিবর্তন" autocomplete="off">
                        </div>

                        <div class="input-group ">
                            <label for="goods_weight"><?php _e('<i class="fa fa-balance-scale" aria-hidden="true"></i> মালের ওজন', 'easy-rents')?></label>
                            <input class="required" required type="number" name="goods_weight" id="goods_weight" placeholder="১০ টন" autocomplete="off">
                        </div>

                        <div class="input-group er_laborebox">
                            <label for="er_labore"><?php _e('<i class="fa fa-people-carry"></i> লেবার', 'easy-rents')?></label>
                            <select name="er_labore" id="er_labore">
                                <option value="0">লাগবেনা</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="submit" name="addjob" id="addjob" class="addjob" value="পাবলিশ">
                    </div>

                </div>
            </form>
        </div>
    </div>
</section>
<?php get_footer();?>