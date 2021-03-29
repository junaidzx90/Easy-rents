<?php
$admin_this = new Easy_Rents( );
wp_enqueue_style( 'jquery-ui' );
wp_enqueue_style( $admin_this->get_plugin_name() );
wp_enqueue_script( 'jquery-ui' );
wp_enqueue_script( $admin_this->get_plugin_name() );
?>
<h1>Locations</h1>

<div class="add_locations">
    <form method="post" action="" autocomplete="off">
    <input  type="text" id="districtlocation" placeholder="District">
    <select id="districtlists">
        <option value="district-1">district-1</option>
        <option value="district-2">district-2</option>
        <option value="district-3">district-3</option>
        <option value="district-4">district-4</option>
        <option value="district-5">district-5</option>
        <option value="district-6">district-6</option>
        <option value="district-7">district-7</option>
    </select>

    <input  type="text" id="citylocation" placeholder="City">
    <input  type="text" id="unionlocation" placeholder="Union">

    <input type="submit" name="addlocation" class="button button-primary" value="Add">
    </form>
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
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Dhaka</td>
                <td>Gazipur</td>
                <td>Konabari</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Khulna</td>
                <td>Bagerhat</td>
                <td>Sharonkhola</td>
            </tr>
        </tbody>
    </table>
</div>