$process = Start-Process php -ArgumentList "-S 127.0.0.1:8088 -t public server.php" -PassThru
Start-Sleep -Seconds 3

try {
    Write-Host "================== 1. TEST /api/app-config (Android) =================="
    curl.exe -i -s -X POST http://127.0.0.1:8088/api/app-config `
        -H "Content-Type: application/json" `
        -d '{"platform":"android","version":"1.0.6"}'
    Write-Host "`n"

    Write-Host "================== 2. TEST /api/app-config (iOS + Bearer null) =================="
    curl.exe -i -s -X POST http://127.0.0.1:8088/api/app-config `
        -H "Content-Type: application/json" `
        -H "Authorization: Bearer null" `
        -d '{"platform":"ios","version":"1.0.5"}'
    Write-Host "`n"

    Write-Host "================== 3. TEST /api/app-config (Validation Error - Missing platform) =================="
    curl.exe -i -s -X POST http://127.0.0.1:8088/api/app-config `
        -H "Content-Type: application/json" `
        -d '{"version":"1.0.6"}'
    Write-Host "`n"

    Write-Host "================== 4. TEST /api/rating/add (Unauthenticated - 401) =================="
    curl.exe -i -s -X POST http://127.0.0.1:8088/api/rating/add `
        -H "Content-Type: application/json" `
        -d '{"rating":"4.5"}'
    Write-Host "`n"

    Write-Host "================== 5. TEST /api/non-existent-endpoint (404 Error) =================="
    curl.exe -i -s -X GET http://127.0.0.1:8088/api/non-existent-endpoint `
        -H "Content-Type: application/json"
    Write-Host "`n"

    Write-Host "================== 6. TEST /api/login (Method Not Allowed - 405) =================="
    curl.exe -i -s -X DELETE http://127.0.0.1:8088/api/login `
        -H "Content-Type: application/json"
    Write-Host "`n"

    Write-Host "================== 7. TEST /api/login (Invalid Credentials) =================="
    curl.exe -i -s -X POST http://127.0.0.1:8088/api/login `
        -H "Content-Type: application/json" `
        -d '{"email":"invalid@user.com","password":"wrongpassword"}'
    Write-Host "`n"

} finally {
    Stop-Process -Id $process.Id -Force
}
