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
<?php wp_enqueue_style('jquery.timepicker.min');?>
<?php wp_enqueue_style('er_addjob_style');?>

<?php wp_enqueue_script('select2');?>
<?php wp_enqueue_script('jquery-ui'); ?>
<?php wp_enqueue_script('jquery.timepicker.min');?>
<?php wp_enqueue_script('er_addjob_script');
wp_localize_script( 'er_addjob_script', "addjob_ajaxurl", array(
    'ajax_url'  => admin_url('admin-ajax.php'),
    'nonce'     => wp_create_nonce('ajax-nonce')
));
//  Get form data to upload database

?>

<section id="ermainsection">
    <div id="eraddjob">
        <!-- Form -->
        <div class="additemform">
            <h1><?php _e('Request for truck','easy-rents') ?></h1>
            <form action="" method="post" id="addjobform">
                <div class="erform_items">

                    <div class="locations">
                        
                        <label for="location_1"><?php _e('<i class="fas fa-arrow-alt-circle-up"></i> Load location', 'easy-rents') ?></label>
                        <?php print_r(Easy_Rents_Public::er_prelocation_input('location_1','location_1','Location')); ?>

                        <label for="location_2"><?php _e('<i class="fas fa-arrow-alt-circle-up"></i> More load location', 'easy-rents') ?> <small class="optional">( Optional)</small></label>
                        <?php print_r(Easy_Rents_Public::er_prelocation_input('location_2','location_2','Location')); ?>

                        <label for="location_3"><?php _e('<i class="fas fa-arrow-alt-circle-up"></i> More load location', 'easy-rents') ?> <small class="optional">( Optional)</small></label>
                        <?php print_r(Easy_Rents_Public::er_prelocation_input('location_3','location_3','Location')); ?>
                        
                        <label for="unload_location"><?php _e('<i class="fas fa-arrow-alt-circle-down"></i> Unload location', 'easy-rents') ?></label>
                        <?php print_r(Easy_Rents_Public::er_prelocation_input('unload_location','unload_location','Location')); ?>

                        <div class="input-group">
                            <label class="datetimelbl" for="loading_time"><?php _e('<i class="far fa-clock"></i> Loading Time/Date', 'easy-rents') ?> </label>
                            <div class="required datetimewrap">
                                <input required type="text" autocomplete="off" name="loading_time" id="loading_time"
                                    placeholder="Select time">
                                <input required type="text" autocomplete="off" name="loading_date" id="loading_date"
                                    placeholder="Select Date">
                            </div>
                        </div>
                    </div>

                    <div class="erkobinfo">
                        <div class="input-group required">
                            <label for="truck_type"><?php _e('<i class="fas fa-truck" aria-hidden="true"></i> Truck type', 'easy-rents') ?></label>
                            <select required name="truck_type" id="truck_type">
                                <option value=""><?php _e('Select truck', 'easy-rents') ?></option>
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

                        <div class="input-group required">
                            <label for="goods_type"><?php _e('<i class="fas fa-luggage-cart"></i> Type of goods', 'easy-rents') ?></label>
                            <input required type="text" name="goods_type" id="goods_type" placeholder="Goods type">
                        </div>

                        <div class="input-group required">
                            <label for="goods_weight"><?php _e('<i class="fa fa-cubes" aria-hidden="true"></i> Weight of goods', 'easy-rents') ?></label>
                            <input required type="number" name="goods_weight" id="goods_weight" placeholder="10 ton">
                        </div>

                        <div class="input-group er_laborebox">
                            <label for="er_labore"><?php _e('<i class="fa fa-people-carry"></i> Labor', 'easy-rents') ?></label>
                            <select name="er_labore" id="er_labore">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="submit" name="addjob" id="addjob" class="addjob" value="Place">
                    </div>

                </div>
            </form>
        </div>
    </div>
</section>
<?php get_footer();?>