<?php
/**
 * Plugin Name: Simple Backup
 * Plugin URI: https://devtechnic.online
 * Description: Basit bir WordPress yedekleme eklentisidir. Veritabanı ve dosyalarınızı yedekleyin.
 * Version: 1.0
 * Author: DevTechnic
 * Author URI: https://devtechnic.online
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Direct access not allowed.
}

// Yedekleme işlevlerini dahil et
require_once plugin_dir_path( __FILE__ ) . 'includes/backup-functions.php';

// Admin paneline yedekleme ayarları sayfası ekleyin
function simple_backup_menu() {
    add_menu_page(
        'Simple Backup', 
        'Backup', 
        'manage_options', 
        'simple-backup', 
        'simple_backup_page',
        'dashicons-backup',
        30
    );
}
add_action('admin_menu', 'simple_backup_menu');

// Yedekleme sayfası içeriği
function simple_backup_page() {
    ?>
    <div class="wrap">
        <h1>Simple Backup</h1>
        <p>Buradan veritabanı ve dosyalarınızın yedeklerini alabilirsiniz.</p>
        <form method="post">
            <?php 
            if (isset($_POST['backup'])) {
                simple_backup_database();
                simple_backup_files();
            }
            ?>
            <input type="submit" name="backup" class="button button-primary" value="Yedekle" />
        </form>
    </div>
    <?php
}
