$baseUrl = "http://localhost/AuthX"
$session = New-Object Microsoft.PowerShell.Commands.WebRequestSession

Write-Host "1. Testing Registration..."
$regResponse = Invoke-WebRequest -Uri "$baseUrl/register_action.php" -Method Post -Body @{
    username = "testuser_auto"
    email = "testauto@example.com"
    password = "password123"
    confirm_password = "password123"
} -WebSession $session -MaximumRedirection 0 -ErrorAction SilentlyContinue -UseBasicParsing

Write-Host "Registration redirect code: $($regResponse.StatusCode)"

Write-Host "2. Testing Login..."
$loginResponse = Invoke-WebRequest -Uri "$baseUrl/login_action.php" -Method Post -Body @{
    email = "testauto@example.com"
    password = "password123"
} -WebSession $session -MaximumRedirection 0 -ErrorAction SilentlyContinue -UseBasicParsing

Write-Host "Login redirect code: $($loginResponse.StatusCode)"

Write-Host "3. Accessing Dashboard..."
$dashboardResponse = Invoke-WebRequest -Uri "$baseUrl/dashboard.php" -Method Get -WebSession $session -UseBasicParsing

if ($dashboardResponse.Content -match "testuser_auto") {
    Write-Host "Dashboard access: SUCCESS (username found in page)"
} else {
    Write-Host "Dashboard access: FAILED"
}

Write-Host "4. Deleting Account..."
$deleteResponse = Invoke-WebRequest -Uri "$baseUrl/delete_account.php" -Method Post -WebSession $session -MaximumRedirection 0 -ErrorAction SilentlyContinue -UseBasicParsing

Write-Host "Delete account redirect code: $($deleteResponse.StatusCode)"

Write-Host "Test complete."
