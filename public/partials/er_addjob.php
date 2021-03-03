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
 <?php wp_enqueue_style( 'er_addjob_style' ); ?>
 <?php wp_enqueue_script( 'er_addjob_script' ); ?>
 <?php 
    if(!current_user_can( 'administrator' )){
        return;
    }
 ?>
 <?php 
//  Get form data to upload database
if(isset($_POST['addjob'])){
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
        $truck_type = sanitize_text_field( $_POST['truck_type'] );
        $goods_type = sanitize_text_field( $_POST['goods_type'] );
        $goods_weight = sanitize_text_field( $_POST['goods_weight'] );
        $er_labore = intval( $_POST['er_labore'] );

        // wp_insert_post( $postarr:array, $wp_error:boolean, $fire_after_hooks:boolean )
        $invoice = new Easy_Rents();
        global $current_user;
        $invoice_nom = $invoice->get_invoice_id($current_user->ID);

        $job_info = array(
            'location_1'        => $location_1,
            'location_2'        => $location_2,
            'location_3'        => $location_3,
            'unload_location'   => $unload_location,
            'loading_times'     => $loading_date .' | '. $loading_time,
            'truck_type'        => $truck_type,
            'goods_type'        => $goods_type,
            'goods_weight'      => $goods_weight,
            'er_labore'         => $er_labore,
            'job_status'        => 'running',
        );

        // Create post object
        $job_post = array(
            'post_type' => 'erjobs',
            'post_status'   => 'publish',
            'post_title'    => wp_strip_all_tags( $invoice_nom ),
            'post_name'     => wp_strip_all_tags( $invoice_nom ),
            'post_content'  => '',
            'post_author'   => $current_user->ID,
            'meta_input'        => array(
                'er_job_info'        => $job_info
            )
        );
        if(wp_insert_post( $job_post )){
            wp_safe_redirect( home_url('/profile') );
            exit;
        }

    }
}
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
                             <input required type="text" name="location_1" id="location_1" placeholder="Type location name">
                         </div>

                         <div class="input-group locationgroup">
                             <label for="location_2">More load location <small class="optional">( Optional )</small></label>
                             <input type="text" name="location_2" id="location_2" placeholder="Type location name">
                         </div>

                         <div class="input-group locationgroup">
                             <label for="location_3">More load location <small class="optional">( Optional )</small></label>
                             <input type="text" name="location_3" id="location_3" placeholder="Type location name">
                         </div>

                         <div class="input-group locationgroup required">
                             <label for="unload_location">Unload location</label>
                             <input type="text" name="unload_location" id="unload_location" placeholder="Type location name">
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
                                 <option value="t1">Truck one</option>
                                 <option value="t2">Truck two</option>
                                 <option value="t3">Truck three</option>
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
                            <label class="eraddjobformwarning">Publish job</label>
                            <input type="submit" name="addjob" value="Place">
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