 <?php
 /** 
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/public/partials/er_addtrip
 * */
 ?>
 <?php get_header(); ?>
 <?php wp_enqueue_style( 'er_addjob_style' ); ?>
 <?php wp_enqueue_script( 'er_addjob_script' ); ?>
 <h1>Request for truck</h1>

 <section>
     <div id="eraddjob">
         <!-- Form -->
         <div class="additemform">
             <form action="" method="post" id="addjobform">
                 <div class="erform_items">

                     <div class="locations">
                         <div class="input-group locationgroup">
                             <label for="location_1">Write your location</label>
                             <input required type="text" name="location_1" id="location_1" placeholder="Type location name">
                         </div>

                         <div class="input-group locationgroup">
                             <label for="location_2">More load location ( Optional )</label>
                             <input type="text" name="location_2" id="location_2" placeholder="Type location name">
                         </div>

                         <div class="input-group locationgroup">
                             <label for="location_3">More load location ( Optional )</label>
                             <input type="text" name="location_3" id="location_3" placeholder="Type location name">
                         </div>

                         <div class="input-group locationgroup">
                             <label for="unload_location">Unload location ( Optional )</label>
                             <input type="text" name="unload_location" id="unload_location" placeholder="Type location name">
                         </div>

                         <div class="input-group">
                             <label for="loading_time">Loading Time</label>
                             <input required type="text" name="loading_time" id="loading_time" placeholder="Select time">
                         </div>
                     </div>

                     <div class="erkobinfo">

                         <div class="input-group">
                             <label for="truck_type">Truck type</label>
                             <select required name="truck_type" id="truck_type">
                                 <option value="">Select truck</option>
                                 <option value="t1">Truck one</option>
                                 <option value="t2">Truck two</option>
                                 <option value="t3">Truck three</option>
                             </select>
                         </div>

                         <div class="input-group">
                             <label for="goods_type">Type of goods</label>
                             <input required type="text" name="goods_type" id="goods_type" placeholder="Goods type">
                         </div>

                         <div class="input-group">
                             <label for="goods_weight">Weight of goods</label>
                             <input required type="text" name="goods_weight" id="goods_weight" placeholder="Goods weight">
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