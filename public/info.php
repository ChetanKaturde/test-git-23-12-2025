<?php
echo "<h1>Laravel Monitorbizz Application</h1>";
echo "<p>Server Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";

// Test Laravel bootstrap
if (file_exists('../bootstrap/app.php')) {
    echo "<p>✅ Laravel bootstrap found</p>";
} else {
    echo "<p>❌ Laravel bootstrap not found</p>";
}

// Test .env file
if (file_exists('../.env')) {
    echo "<p>✅ .env file found</p>";
} else {
    echo "<p>❌ .env file not found</p>";
}
?>