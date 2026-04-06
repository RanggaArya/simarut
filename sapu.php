<?php
echo "<h1>Mulai Membersihkan...</h1>";

// 1. Bantai OPcache (RAM Server)
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "<p>✅ OPcache (RAM Server) berhasil dikosongkan.</p>";
} else {
    echo "<p>⚠️ OPcache tidak aktif.</p>";
}

// 2. Bantai File Cache Laravel SECARA PAKSA (Tanpa menyalakan Laravel)
// Sesuaikan '../bootstrap/cache' jika folder bootstrap Anda letaknya berbeda
$cacheDir = __DIR__ . '/../bootstrap/cache'; 
if (is_dir($cacheDir)) {
    $filesToDelete = ['packages.php', 'services.php', 'config.php', 'routes-v7.php', 'compiled.php'];
    foreach ($filesToDelete as $file) {
        $path = $cacheDir . '/' . $file;
        if (file_exists($path)) {
            unlink($path);
            echo "<p>✅ File <b>$file</b> berhasil dihapus manual.</p>";
        }
    }
} else {
    echo "<p>⚠️ Folder bootstrap/cache tidak ditemukan di jalur " . $cacheDir . "</p>";
}

echo "<h2>Pembersihan Selesai! Silakan cek web Anda.</h2>";
?>