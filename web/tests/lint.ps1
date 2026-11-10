$ErrorActionPreference = 'Stop'

$webRoot = Split-Path -Parent $PSScriptRoot
$phpFiles = Get-ChildItem -Path $webRoot -Recurse -File -Filter '*.php'

if (-not $phpFiles) {
    throw 'Tidak ada file PHP yang ditemukan di folder web.'
}

foreach ($file in $phpFiles) {
    Write-Host "Memeriksa $($file.FullName)" -ForegroundColor Cyan
    php -l $file.FullName
    if ($LASTEXITCODE -ne 0) {
        throw "Pemeriksaan sintaks gagal: $($file.FullName)"
    }
}

Write-Host "Selesai. $($phpFiles.Count) file PHP lolos pemeriksaan sintaks." -ForegroundColor Green
