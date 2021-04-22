<?php $public_ins = new Easy_Rents_Public(); ?>
<?php global $current_user; ?>
<nav class="ermenu" tabindex="0">
    <div class="smartphone-ermenu-trigger"></div>
    <header class="avatar">
        <div class="img avatarImg"></div>
        <!-- <img style="width: 100px" src="https://i.pinimg.com/originals/be/ac/96/beac96b8e13d2198fd4bb1d5ef56cdcf.jpg" /> -->
        <h2 class="username"><?php echo __($current_user->display_name,'easy-rents'); ?>
            <?php
            if (is_user_logged_in() && $public_ins->er_role_check(['driver'])){
                $userStatus = get_user_by( 'id', $current_user->ID );
                if($userStatus->user_status > 0){
                    echo '<i class="fa fa-check-circle verified" aria-hidden="true"></i>';
                }else{
                    echo '<i class="fas fa-times-circle unverified"></i>';
                }
            }
            ?>
        </h2>
        <?php
        if (is_user_logged_in() && $public_ins->er_role_check(['driver'])){
            ?>
                <progress id="profile__ratings" value="100" max="100"></progress>
                <span class="profile-ratings">100%</span>
            <?php
        }
        ?>
        
    </header>
    <ul>
        <li tabindex="0" class="listitem <?php echo ($page == 'dashboard')?'eractive':'' ?>">
            <i class="fas fa-recycle"></i>
            <a href="<?php echo home_url($public_ins->get_post_slug(get_option( 'profile_page', true ))) ?>"> <span class=" <?php echo ($page == 'dashboard')?'eractivecolor':'' ?>">Dashboard</span> </a>
        </li>

        <li tabindex="0" class="listitem  <?php echo ($page == 'trips')?'eractive':'' ?>">
            <i class="fa fa-tasks" aria-hidden="true"></i>
            <a href="<?php echo home_url($public_ins->get_post_slug(get_option( 'profile_trips', true ))) ?>"> <span class=" <?php echo ($page == 'trips')?'eractivecolor':'' ?>">My Trips</span></a>
        </li>
        <?php
        if($public_ins->er_role_check( ['driver'] )){
        ?>
            <li tabindex="0" class="listitem">
                <i class="fas fa-truck" aria-hidden="true"></i>
                <a href="<?php echo home_url($public_ins->get_post_slug(get_option( 'my_trucks_list', true ))) ?>"> <span>My Trucks</span></a>
            </li>
        <?php
        }

        if($public_ins->er_role_check( ['customer'] )){
            ?>
            <li tabindex="0" class="listitem">
                <i class="fas fa-plus-square" aria-hidden="true"></i>
                <a href="<?php echo home_url($public_ins->get_post_slug(get_option( 'add_trip_page', true ))) ?>"> <span>Request for truck</span></a>
            </li>
            <?php
        }
        ?>

        <?php
        if($public_ins->er_role_check( ['driver'] )){
        ?>
            <li tabindex="0" class="listitem  <?php echo ($page == 'payment')?'eractive':'' ?>">
                <i class="fas fa-hand-holding-usd"></i>
                <a href="<?php echo home_url($public_ins->get_post_slug(get_option( 'profile_payment', true ))) ?>"><span class=" <?php echo ($page == 'payment')?'eractivecolor':'' ?>">Payments</span>
                <?php
                global $wp_query,$current_user,$wpdb;
                $paymentstatus = $wpdb->query("SELECT * FROM {$wpdb->prefix}easy_rents_applications WHERE driver_id = {$current_user->ID} AND status = 3 AND payment < 1");
                if($paymentstatus){
                    echo '<span class="notification">'.intval($paymentstatus).'</span>';
                }
                ?>
                </a>
            </li>
        <?php
        }
        ?>

        <li tabindex="0" class="listitem <?php echo ($page == 'settings')?'eractive':'' ?>">
            <i class="fa fa-user-secret" aria-hidden="true"></i>
            <a href="<?php echo home_url($public_ins->get_post_slug(get_option( 'erprofile_settings', true ))) ?>"><span class=" <?php echo ($page == 'settings')?'eractivecolor':'' ?>">Settings</span></a>
        </li>
    </ul>
</nav>