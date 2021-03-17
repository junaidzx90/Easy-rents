 <?php
 /** 
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/public/partials/er_addtrip
 * */
 ?>
 <?php ob_start(); ?>
 <?php get_header(); ?>
 <?php wp_enqueue_style( 'select2' ); ?>
 <?php wp_enqueue_style( 'er_addjob_style' ); ?>
 <?php wp_enqueue_script( 'select2' ); ?>
 <?php wp_enqueue_script( 'er_addjob_script' ); ?>
 <?php 
    if(!Easy_Rents_Public::er_role_check( ['Customer'] )){
        echo 'Please Login To See';
        return;
    }
 ?>
 <?php 
//  Get form data to upload database

if(isset($_POST['addjob']) && isset($_POST['jobform']) && $_POST['jobform'] != ""){
    if(!is_user_logged_in(  ) && !Easy_Rents_Public::er_role_check( ['Customer'] )){
        return;
    }
    if ( empty($_POST) || ! wp_verify_nonce( $_POST['er_addjob_nonce'], 'er_addjob_nonce_val') ){
        print 'Verification failed. Try again.';
        exit;
    }
    
    // Checking required data empty value
    if($_POST['location_1'] != "" && $_POST['unload_location'] != "" && $_POST['loading_time'] != "" && $_POST['loading_date'] != "" && $_POST['truck_type'] != "" && $_POST['goods_type'] != "" && $_POST['goods_weight'] != "" && $_POST['er_labore'] != ""){
        $location_2 = '';
        $location_3 = '';

        if(isset($_POST['location_2'] ) && $_POST['location_2'] != ""){
            $location_2 = sanitize_text_field($_POST['location_2']);
        }
        if(isset($_POST['location_3'] ) && $_POST['location_3'] != ""){
            $location_3 = sanitize_text_field($_POST['location_3']);
        }

        $location_1 = sanitize_text_field( $_POST['location_1'] );
        $unload_location = sanitize_text_field( $_POST['unload_location'] );
        $loading_time = date('h: i a',strtotime(sanitize_text_field( $_POST['loading_time'] )));
        $loading_date = sanitize_text_field( $_POST['loading_date'] );
        $truck_type = intval( $_POST['truck_type'] );
        $goods_type = sanitize_text_field( $_POST['goods_type'] );
        $goods_weight = sanitize_text_field( $_POST['goods_weight'] );
        $er_labore = intval( $_POST['er_labore'] );

        $invoice = new Easy_Rents();

        global $current_user;
        $invoice_nom = $invoice->get_invoice_id($current_user->ID);

        $job_info = array(
            'location_1'        => $location_1,
            'location_2'        => $location_2,
            'location_3'        => $location_3,
            'unload_location'   => $unload_location,
            'loading_times'     => $loading_date .' | '. $loading_time,
            'goods_type'        => $goods_type,
            'goods_weight'      => $goods_weight,
            'er_labore'         => $er_labore,
            'job_status'        => 'running',
        );

        // Create post object
        $job_post = array(
            'post_type' => 'jobs',
            'post_status'   => 'publish',
            'post_title'    => wp_strip_all_tags( $invoice_nom ),
            'post_name'     => wp_strip_all_tags( $invoice_nom ),
            'post_content'  => '',
            'post_author'   => $current_user->ID,
            'meta_input'    => array(
                'er_job_info'  => $job_info
            )
        );
        
        $post_id = wp_insert_post( $job_post );
        $set_term = wp_set_post_terms( $post_id, $truck_type, 'truckstype');

        $redirect_page = Easy_Rents_Public::get_post_slug(get_option( 'trips_page', true ));
        wp_safe_redirect( home_url('/'.$redirect_page) );
        exit;
    }
}

// Get Location addresses
$entry_locations = get_option( 'er_locations' );
 ?>

 <h1>Request for truck</h1>
 <section>
     <div id="eraddjob">
         <!-- Form -->
         <div class="additemform">
             <form action="" method="post" id="addjobform">
                 <div class="erform_items">

                     <div class="locations">
                         <div class="input-group locationgroup required">
                             <label for="location_1">Write your location</label>
                             
                             <select name="location_1" id="location_1">
                                <?php
                                if(!empty($entry_locations)){
                                    echo '<option value="">Select a location</option>';
                                    foreach($entry_locations as $addrname){
                                        echo '<option value="'.sanitize_text_field( $addrname ).'">'.__($addrname,'easy-rents').'</option>';
                                    }
                                }else{
                                    echo '<option value="">Select a location</option>';
                                }
                                ?>
                             </select>
                         </div>

                         <div class="input-group locationgroup">
                             <label for="location_2">More load location <small class="optional">( Optional )</small></label>
                             
                             <select name="location_2" id="location_2">
                                <?php
                                if(!empty($entry_locations)){
                                    echo '<option value="">Select a location</option>';
                                    foreach($entry_locations as $addrname){
                                        echo '<option value="'.sanitize_text_field( $addrname ).'">'.__($addrname,'easy-rents').'</option>';
                                    }
                                }else{
                                    echo '<option value="">Select a location</option>';
                                }
                                ?>
                             </select>
                         </div>

                         <div class="input-group locationgroup">
                             <label for="location_3">More load location <small class="optional">( Optional )</small></label>
                             
                             <select name="location_3" id="location_3">
                                <?php
                                if(!empty($entry_locations)){
                                    echo '<option value="">Select a location</option>';
                                    foreach($entry_locations as $addrname){
                                        echo '<option value="'.sanitize_text_field( $addrname ).'">'.__($addrname,'easy-rents').'</option>';
                                    }
                                }else{
                                    echo '<option value="">Select a location</option>';
                                }
                                ?>
                             </select>
                         </div>

                         <div class="input-group locationgroup required">
                             <label for="unload_location">Unload location</label>
                             <select name="unload_location" id="unload_location">
                                <?php
                                if(!empty($entry_locations)){
                                    echo '<option value="">Select a location</option>';
                                    foreach($entry_locations as $addrname){
                                        echo '<option value="'.sanitize_text_field( $addrname ).'">'.__($addrname,'easy-rents').'</option>';
                                    }
                                }else{
                                    echo '<option value="">Select a location</option>';
                                }
                                ?>
                             </select>
                         </div>

                         <div class="input-group">
                             <label class="datetimelbl" for="loading_time">Loading Time/Date </label>
                             <div class="required datetimewrap">
                                <input required type="time" name="loading_time" id="loading_time" placeholder="Select time">
                                <input required type="date" name="loading_date" id="loading_date" placeholder="Select Date">
                            </div>
                         </div>
                     </div>
                     
                     <div class="erkobinfo">
                         <div class="input-group required">
                             <label for="truck_type">Truck type</label>
                             <select required name="truck_type" id="truck_type">
                                <option value="">Select truck</option>
                                <?php
                                $args = array(
                                    'taxonomy'               => 'truckstype',
                                    'orderby'                => 'name',
                                    'order'                  => 'ASC',
                                    'hide_empty'             => false,
                                );
                                $the_query = new WP_Term_Query($args);
                                foreach($the_query->get_terms() as $term){ 
                                    
                                    echo '<option value="'.intval($term->term_id).'">'.ucfirst($term->name).'</option>';
                                    
                                }
                                ?>
                             </select>
                         </div>

                         <div class="input-group required">
                             <label for="goods_type">Type of goods</label>
                             <input required type="text" name="goods_type" id="goods_type" placeholder="Goods type">
                         </div>

                         <div class="input-group required">
                             <label for="goods_weight">Weight of goods</label>
                             <input required type="number" name="goods_weight" id="goods_weight" placeholder="10 ton">
                         </div>

                         <div class="input-group">
                             <label for="er_labore">Labore</label>
                             <select name="er_labore" id="er_labore">
                                 <option value="0">0</option>
                                 <option value="1">1</option>
                                 <option value="2">2</option>
                                 <option value="3">3</option>
                                 <option value="4">4</option>
                                 <option value="5">5</option>
                             </select>
                         </div>
                        <?php wp_nonce_field( 'er_addjob_nonce_val', 'er_addjob_nonce' ); ?>
                         <div class="input-group">
                            <input type="hidden" id="jobform" value="<?php echo rand(); ?>" name="jobform">
                            <input type="submit" name="addjob" class="addjob" value="Place">
                        </div>

                     </div>
                 </div>
             </form>
         </div>

         <!-- Sidebar -->
         <div class="er_sidebar">
             <!-- Getting revulution elements -->
             <?php echo the_content( ); ?>
         </div>
     </div>
 </section>
 <?php get_footer(); ?>