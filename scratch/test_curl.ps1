$process = Start-Process php -ArgumentList "-S 127.0.0.1:8088 server.php" -PassThru
Start-Sleep -Seconds 2

try {
    # 1. Android request
    '{"platform":"android","version":"1.0.6"}' | Set-Content -Encoding ascii scratch/android.json
    Write-Host "================== 1. TEST /api/app-config (Android) =================="
    curl.exe -i -s -X POST http://127.0.0.1:8088/api/app-config `
        -H "Content-Type: application/json" `
        --data-binary "@scratch/android.json"
    Write-Host "`n"

    # 2. iOS request with Bearer null
    '{"platform":"ios","version":"1.0.5"}' | Set-Content -Encoding ascii scratch/ios.json
    Write-Host "================== 2. TEST /api/app-config (iOS + Bearer null) =================="
    curl.exe -i -s -X POST http://127.0.0.1:8088/api/app-config `
        -H "Content-Type: application/json" `
        -H "Authorization: Bearer null" `
        --data-binary "@scratch/ios.json"
    Write-Host "`n"

    # 3. Validation error (missing platform)
    '{"version":"1.0.6"}' | Set-Content -Encoding ascii scratch/missing.json
    Write-Host "================== 3. TEST /api/app-config (Validation Error - Missing platform) =================="
    curl.exe -i -s -X POST http://127.0.0.1:8088/api/app-config `
        -H "Content-Type: application/json" `
        --data-binary "@scratch/missing.json"
    Write-Host "`n"

    # 4. Rating API unauthenticated
    '{"rating":"4.5"}' | Set-Content -Encoding ascii scratch/rating.json
    Write-Host "================== 4. TEST /api/rating/add (Unauthenticated - 401) =================="
    curl.exe -i -s -X POST http://127.0.0.1:8088/api/rating/add `
        -H "Content-Type: application/json" `
        --data-binary "@scratch/rating.json"
    Write-Host "`n"

    # 5. 404 Route Not Found
    Write-Host "================== 5. TEST /api/non-existent-endpoint (404 Error) =================="
    curl.exe -i -s -X GET http://127.0.0.1:8088/api/non-existent-endpoint `
        -H "Content-Type: application/json"
    Write-Host "`n"

    # 6. 405 Method Not Allowed
    Write-Host "================== 6. TEST /api/login (Method Not Allowed - 405) =================="
    curl.exe -i -s -X DELETE http://127.0.0.1:8088/api/login `
        -H "Content-Type: application/json"
    Write-Host "`n"

} finally {
    Stop-Process -Id $process.Id -Force
}
