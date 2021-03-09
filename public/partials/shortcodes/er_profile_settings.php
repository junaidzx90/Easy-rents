<?php
/**
    * @link example.com
    * @since 1.0.0
    *
    * @package Easy_Rents
    * @subpackage Easy_Rents/public/partials/er_profile
    * */
?>
<?php wp_enqueue_style( 'er_profile_style' ); ?>
<?php wp_enqueue_style( 'er_profile_script' ); ?>

<section>
    <div id="er_profileMain">

        <nav class="ermenu" tabindex="0">
            <div class="smartphone-ermenu-trigger"></div>
            <header class="avatar">
                <img style="width: 100px" src="https://i.pinimg.com/originals/be/ac/96/beac96b8e13d2198fd4bb1d5ef56cdcf.jpg" />
                <h2>John D. <i class="fa fa-check-circle verified" aria-hidden="true"></i></h2>
            </header>
            <ul>
                <li tabindex="0" class="listitem">
                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                    <a href="<?php echo home_url($this->get_post_slug(get_option( 'profile_page', true ))) ?>"> <span>Dashboard</span> </a>
                </li>

                <li tabindex="0" class="listitem">
                    <i class="fa fa-tasks" aria-hidden="true"></i>
                    <a href="<?php echo home_url($this->get_post_slug(get_option( 'profile_trips', true ))) ?>"> <span>My Trips</span></a>
                </li>

                <li tabindex="0" class="listitem">
                    <i class="fa fa-institution" aria-hidden="true"></i>
                    <a href="<?php echo home_url($this->get_post_slug(get_option( 'profile_payment', true ))) ?>"><span>Payments</span></a>
                </li>

                <li tabindex="0" class="listitem eractive">
                    <i class="fa fa-user-secret" aria-hidden="true"></i>
                    <a href="<?php echo home_url($this->get_post_slug(get_option( 'erprofile_settings', true ))) ?>"><span class="eractivecolor">Settings</span></a>
                </li>
            </ul>
        </nav>

        <main>
            <div class="helper">
                RESIZE THE WINDOW
                <span>Breakpoints on 900px and 400px</span>
            </div>
        </main>
    </div>
</section>

<!-- /.content-wrapper -->