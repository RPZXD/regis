<?php
// Mock GET
$_GET['stu_id'] = 1;
$_SESSION['student_login'] = 1; // Mock session to bypass check

// Mock mPDF to avoid binary output and just string capture
namespace Mpdf;
class Mpdf {
    public function __construct($config = []) {}
    public function SetTitle($title) { echo "SetTitle: $title\n"; }
    public function WriteHTML($html) { echo "WriteHTML Called. Content Length: " . strlen($html) . "\n"; }
    public function Output() { echo "Output generated.\n"; }
}

// Global scope
require_once 'config/Database.php';
require_once 'function.php';

// Mock DB connection if needed or use real one
// use real one to test query

// We need to suppress the actual require vendor/autoload because we mocked Mpdf
// But the real file requires it.
// We will modify the real file content in memory or just copy logic?
// Copying logic is safer to avoid modifying the file again if it's just a test.

// Let's try to run the actual file but capture output?
// But `require_once __DIR__ . '/vendor/autoload.php';` will fail if valid Mpdf class is loaded first?
// Or we can just use the real Mpdf and catch output buffer?
// But Mpdf output is binary PDF.
// If there is a PHP error *before* PDF output, we want to see it.

// Let's use `php -f print_pdf_m1.php` but we need to set $_GET variables.
// standard way: export query string? No, php-cgi does that.
// Let's try to just include it from a wrapper that sets $_GET.

$_GET['stu_id'] = 1;
$_SESSION['student_login'] = 1;

// Define helper if missing (based on previous findings)
if (!function_exists('ck_level2')) {
    function ck_level2($lvl) { return "M.$lvl"; }
}
if (!function_exists('ck_typeregis')) {
    function ck_typeregis($type) { return $type; }
}

// We need to prevent the script from redirecting if session is wrong
// The script has:
// if ($_SESSION['student_login'] !== $uid) { header("location: index.php"); }
// So we must match them.

ob_start();
try {
    // We can't easily prevent require vendor/autoload from loading real mpdf.
    // If real mpdf loads, it might try to output headers.
    // Let's just catch all text output.
    include 'print_pdf_m1.php';
} catch (Throwable $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . " on line " . $e->getLine();
}
$output = ob_get_clean();

if (strpos($output, '%PDF') === 0) {
    echo "PDF Generated Successfully (Header found)";
} else {
    echo "Output was not PDF:\n";
    echo substr($output, 0, 2000); // Show first 2000 chars
}

?>
