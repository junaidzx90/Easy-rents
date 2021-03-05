<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/admin/partials
 */
?>
<?php $admin_this = new Easy_Rents( ); ?>
<?php 
wp_enqueue_style( $admin_this->get_plugin_name() );
wp_enqueue_script( $admin_this->get_plugin_name() );
?>
<?php
// Getting page identifyer
echo '<h1>ER Settings </h1> <hr>';
echo '<form action="options.php" method="post">';
settings_fields( 'er_settings_section' );
do_settings_fields( 'er-settings', 'er_settings_section' );
submit_button();
echo '</form>';

// Delete location from option
if(isset($_POST['delete'])){
    if(isset($_POST['addrind'])){
        $index = $_POST['addrind'];
        $locations = get_option( 'er_locations' );
        unset($locations[$index]);
        $locations = array_values($locations);
        
        update_option( 'er_locations', $locations );
    }
}

// Insert location to option
if(isset($_POST['addlocation'])){
    if(wp_verify_nonce( $_POST['location_nonce'], 'location_add_field' )){
        if(isset($_POST['location'])){
            $locinp = sanitize_text_field( $_POST['location'] );

            $locations = get_option( 'er_locations' );
            
            $location_push = array();
            // if already option as array
            if(!empty($locations) && is_array($locations)){
                foreach($locations as $location){
                    $location_push[] = $location;
                }
            }
            // Insert data into option
            if( !empty($locinp) ){
                if(array_push($location_push, $locinp)){
                    if(empty( $locations)){
                        add_option( 'er_locations', $location_push );
                    }else{
                        if(!in_array($locinp,$locations)){
                            update_option( 'er_locations', $location_push );
                        }else{
                            $warn = "It's already exists";
                        }
                    }
                }
            }else{
                $warn = "Empty value is not acceptable";
            }
        }
    }
    
}
?>
 <!-- Add locations -->
 <h3>Add Location</h3>
 <form action="" method="post">
    <input type="text" name="location" placeholder="Add Location">
    <?php wp_nonce_field( 'location_add_field', 'location_nonce' ) ?>
    <input type="submit" name="addlocation" class="button button-primary" value="Save">
    <br>
    <span class="warnings"><?php echo $warn; ?></span>
</form>

<div class="locations" style="overflow-y:auto;">
    <table>
        <tbody>
        <?php
            $locations = get_option( 'er_locations' );
            $i = 1;
            if(!empty($locations) && is_array($locations)){
                foreach($locations as $index => $location){
                    echo '<tr>';
                    echo '<th>'.$i.'</th>';
                    echo '<td>'.__($location,'easy-rents').'</td>';
                    echo '<td>';
                    echo '<form action="" method="post">';
                    echo '';
                    echo '<input type="hidden" name="addrind" value="'.$index.'">';
                    echo '<button class="delete_addr" name="delete">X</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                    $i++;
                }
            }
        ?>
        </tbody>
    </table>
</div>


