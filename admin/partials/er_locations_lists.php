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

<h1>Locations</h1>
<hr>
<div class="topforms">
    <div class="add_locations">
        <form method="post" action="" autocomplete="off">
        <input  type="text" id="districtlocation" placeholder="District">
        <select id="districtlists">
        <?php
            global $wpdb;

            $districts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_prelocations ORDER BY ID DESC");

            if($districts){
                foreach($districts as $district){
                    ?>
                <option value="<?php echo __($district->district, 'easy-rents'); ?>"><?php echo __($district->district, 'easy-rents'); ?></option>
                    <?php
                }
            }
        ?>
        </select>
        
        <input  type="text" id="citylocation" placeholder="City">
        <input  type="text" id="unionlocation" placeholder="Union">

        <input type="submit" name="addlocation" class="button button-primary" value="Add">
        </form>
    </div>

    <div class="filter_search">
        <h3 class="searchttl">Search By District</h3>
            <form action="" method="post">
                <input type="submit" name="searchfilter" value="☭">
                &nbsp;
                <input type="text" class="filterItems" name="filterItems" placeholder="Select District" value="<?php echo $_POST['filterItems']; ?>">
        </form>
    </div>
</div>

<br>
<div class="locations" style="overflow-y:auto;">
    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>District</th>
                <th>City</th>
                <th>Union</th>
                <th class="del">Delete</th>
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