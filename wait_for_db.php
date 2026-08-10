<?php
/**
 * Database readiness check script.
 * Used by entrypoint.sh to wait for MySQL before starting the app.
 * Works on both Linux and Windows Docker environments.
 */
$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'mace_user';
$pass = getenv('DB_PASS') ?: 'mace_password';
$name = getenv('DB_NAME') ?: 'mace_admission';

$conn = @mysqli_connect($host, $user, $pass, $name);

if ($conn) {
    mysqli_close($conn);
    exit(0); // Success - DB is ready
}

exit(1); // Failure - DB not ready yet
