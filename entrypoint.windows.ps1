# PowerShell entrypoint for Windows Server Docker deployments
param()

Write-Host "Waiting for MySQL to be ready..."

$maxRetries = 30
$retries = 0
$ready = $false

while (-not $ready -and $retries -lt $maxRetries) {
    try {
        $conn = New-Object MySql.Data.MySqlClient.MySqlConnection
        $conn.ConnectionString = "Server=$env:DB_HOST;Database=$env:DB_NAME;Uid=$env:DB_USER;Pwd=$env:DB_PASS;"
        $conn.Open()
        $conn.Close()
        $ready = $true
    } catch {
        $retries++
        Write-Host "  MySQL not ready yet. Retrying in 3 seconds... ($retries/$maxRetries)"
        Start-Sleep -Seconds 3
    }
}

if (-not $ready) {
    Write-Error "Could not connect to MySQL after $maxRetries attempts. Aborting."
    exit 1
}

Write-Host "MySQL is ready! Running database migrations..."
php spark migrate --no-interaction

Write-Host "Starting Apache web server..."
& apache2-foreground
