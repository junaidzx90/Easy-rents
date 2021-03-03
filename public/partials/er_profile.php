<?php
 /** 
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/public/partials/er_profile
 * */
 ?>

<?php get_header(); ?>
<?php $public_this = new Easy_Rents( ); ?>
<?php wp_enqueue_style( 'er_profile_style' ); ?>
<?php wp_enqueue_style( 'er_profile_script' ); ?>

<section>
    <div id="er_profileMain">
        <div class="er_profile_sidebar">
            <div class="er_profileImg">
                <img src="" alt="profile-img">
            </div>
            <ul class="er_profileMenu">
                <li>Dashboard</li>
                <li>My Trips</li>
                <li>Payment
                    <ul class="subpaymentmenu">
                        <li>Late fee</li>
                        <li>Payment History</li>
                    </ul>
                </li>
                <li>Edit Profile</li>
            </ul>
        </div>
        <div class="er_profile_content">
            <div class="er_jobItem">
                <h4 class="loadLocation_1">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur, est.</h4>
                <span class="loadTime">12:50pm ( 12/03/21 )</span>
                <form action="" method="post">
                    <input type="submit" name="er_job_cancel" value="Cancel">
                    <input type="submit" name="er_job_finish" value="Finished">
                </form>
            </div>
        </div>
    </div> 
</section>

<!-- /.content-wrapper -->
<?php get_footer(); ?>