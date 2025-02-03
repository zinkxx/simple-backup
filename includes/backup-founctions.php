<?php

// Veritabanı yedeği alma fonksiyonu
function simple_backup_database() {
    global $wpdb;
    $tables = $wpdb->get_col('SHOW TABLES');
    $backup_file = plugin_dir_path(__FILE__) . 'backups/database-backup-' . time() . '.sql';
    
    $sql_dump = '';
    foreach ($tables as $table) {
        $sql_dump .= 'DROP TABLE IF EXISTS `' . $table . '`;' . "\n";
        $sql_dump .= $wpdb->get_var('SHOW CREATE TABLE ' . $table) . ';' . "\n\n";
        
        $rows = $wpdb->get_results('SELECT * FROM ' . $table, ARRAY_A);
        foreach ($rows as $row) {
            $values = array_map(array($wpdb, 'escape'), array_values($row));
            $sql_dump .= 'INSERT INTO ' . $table . ' (' . implode(', ', array_keys($row)) . ') VALUES (' . "'" . implode("', '", $values) . "');" . "\n";
        }
    }
    
    file_put_contents($backup_file, $sql_dump);
}

// Dosya yedeği alma fonksiyonu
function simple_backup_files() {
    $upload_dir = wp_upload_dir();
    $backup_dir = plugin_dir_path(__FILE__) . 'backups/files-backup-' . time();
    
    // Dosya yedeğini almak için tüm upload dizinini kopyala
    recurse_copy($upload_dir['basedir'], $backup_dir);
}

// Recursive copy fonksiyonu
function recurse_copy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}
