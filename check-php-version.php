<?php

echo "🔍 Checking PHP Version Compatibility\n";
echo "=====================================\n\n";

$currentVersion = PHP_VERSION;
$requiredVersion = '8.2.0';

echo "Current PHP Version: " . $currentVersion . "\n";
echo "Required PHP Version: " . $requiredVersion . "+\n\n";

if (version_compare($currentVersion, $requiredVersion, '>=')) {
    echo "✅ PHP version is compatible!\n";
    echo "You can proceed with the installation.\n";
} else {
    echo "❌ PHP version is not compatible!\n";
    echo "Please upgrade to PHP 8.2 or higher.\n";
    echo "\nTo upgrade PHP:\n";
    echo "- On Ubuntu/Debian: sudo apt update && sudo apt install php8.2\n";
    echo "- On CentOS/RHEL: sudo yum install php82\n";
    echo "- On macOS: brew install php@8.2\n";
    echo "- On Windows: Download from https://windows.php.net/download/\n";
}

echo "\n📋 Additional Information:\n";
echo "- PHP SAPI: " . php_sapi_name() . "\n";
echo "- PHP Extensions: " . implode(', ', get_loaded_extensions()) . "\n";
echo "- Memory Limit: " . ini_get('memory_limit') . "\n";
echo "- Max Execution Time: " . ini_get('max_execution_time') . "\n"; 