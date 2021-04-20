<?php
$admin_this = new Easy_Rents( );
wp_enqueue_style( 'jquery-ui' );
wp_enqueue_style( $admin_this->get_plugin_name() );
wp_enqueue_script( 'jquery-ui' );
wp_enqueue_script( 'easy-rents-locations' );
wp_localize_script( 'easy-rents-locations', "locations_ajaxurl", array(
    'ajax_url'  => admin_url('admin-ajax.php'),
    'nonce'     => wp_create_nonce('ajax-nonce')
));

?>

<h1>স্থানের লিস্ট</h1>
<hr>
<div class="topforms">
    <div class="add_locations">
        <form method="post" action="" autocomplete="off">
        <input  type="text" id="divisionlocation" placeholder="division">
        <select id="divisionlists">
        <?php
            global $wpdb;

            $divisions = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_prelocations ORDER BY ID DESC");

            if($divisions){
                foreach($divisions as $division){
                    ?>
                <option value="<?php echo __($division->division, 'easy-rents'); ?>"><?php echo __($division->division, 'easy-rents'); ?></option>
                    <?php
                }
            }
        ?>
        </select>
        
        <input  type="text" id="districtlocation" placeholder="district">
        <input  type="text" id="p_stationlocation" placeholder="p_station">

        <input type="submit" name="addlocation" class="button button-primary" value="Add">
        </form>
    </div>

    <div class="filter_search">
        <h3 class="searchttl">বিভাগ সার্চ করুণ</h3>
            <form action="" method="post">
                <input type="submit" name="searchfilter" value="☭">
                &nbsp;
                <input type="text" class="filterItems" name="filterItems" placeholder="Select division" value="<?php echo isset($_POST['filterItems'])? $_POST['filterItems']:''; ?>">
        </form>
    </div>
</div>

<br>
<div class="locations" style="overflow-y:auto;">
    <table>
        <thead>
            <tr>
                <th>ত্রমিক</th>
                <th>বিভাগ</th>
                <th>জেলা</th>
                <th>থানা</th>
                <th class="del">মুছুন</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($_POST['searchfilter'])){
                if(isset($_POST['filterItems']) && $_POST['filterItems'] !== ''){
                    $filter = sanitize_text_field( $_POST['filterItems'] );
                    echo Easy_Rents_Admin::erLocationsList($filter);
                }else{
                    echo Easy_Rents_Admin::erLocationsList();
                }
            }else{
                echo Easy_Rents_Admin::erLocationsList();
            }
            ?>
        </tbody>
    </table>
</div>