<?php
$page = 'settings';
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

        <!-- Sidebar -->
        <?php require_once(ER_PATH.'public/partials/profile_sidebar.php') ?>

        <main>
            <div class="helper">
                RESIZE THE WINDOW
                <span>Breakpoints on 900px and 400px</span>
            </div>
        </main>
    </div>
</section>

<!-- /.content-wrapper -->