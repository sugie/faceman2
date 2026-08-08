# Find My Motorcycle: iOS の Assets.xcassets を丸ごと生成する。
#
#   pwsh tools/make_ios_assets.ps1
#
# Xcode 上での画像追加作業をなくすのが目的。生成物をフォルダごと
# プロジェクトにドラッグすれば、15 ジャンルの画像とアプリアイコンが揃う。 #AST01

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'
Add-Type -AssemblyName System.Drawing

$root = Split-Path -Parent $PSScriptRoot
$srcDir = Join-Path $root 'public/images/bikefit/genres'
$outDir = Join-Path $root 'ios/Assets.xcassets'

# ジャンル画像は 8010.png 〜 8150.png。末尾に b の付いた別案は使わない。
$sourceList = Get-ChildItem -Path $srcDir -Filter '*.png' |
    Where-Object { $_.BaseName -match '^\d+$' } |
    Sort-Object BaseName

if ($sourceList.Count -eq 0) {
    throw "#AST02: ジャンル画像が見つかりません: $srcDir"
}
Write-Host "#AST03: ジャンル画像 $($sourceList.Count) 件を検出"

# 生成物なので毎回作り直す。消す対象が想定どおりの場所かだけ確認する。
if ($outDir -notmatch 'Assets\.xcassets$') { throw "#AST04: 出力先が不正: $outDir" }
if (Test-Path $outDir) { Remove-Item -LiteralPath $outDir -Recurse -Force }
New-Item -ItemType Directory -Path $outDir -Force | Out-Null

# ルートの Contents.json
@'
{
  "info" : {
    "author" : "xcode",
    "version" : 1
  }
}
'@ | Set-Content -LiteralPath (Join-Path $outDir 'Contents.json') -Encoding utf8

# ── ジャンル画像 ────────────────────────────────────
# 元画像が既に 1024x1024 なので変換せずそのまま置く。
# 単一スケール (scale 指定なし) の imageset にする。
foreach ($src in $sourceList) {
    $name = $src.BaseName
    $setDir = Join-Path $outDir "$name.imageset"
    New-Item -ItemType Directory -Path $setDir -Force | Out-Null
    Copy-Item -LiteralPath $src.FullName -Destination (Join-Path $setDir "$name.png")

    @"
{
  "images" : [
    {
      "filename" : "$name.png",
      "idiom" : "universal"
    }
  ],
  "info" : {
    "author" : "xcode",
    "version" : 1
  }
}
"@ | Set-Content -LiteralPath (Join-Path $setDir 'Contents.json') -Encoding utf8
}
Write-Host "#AST05: imageset を $($sourceList.Count) 件生成"

# ── アプリアイコン ──────────────────────────────────
# App Store Connect はアルファチャンネル付きのアイコンを弾くため、
# 24bit RGB のビットマップに描き直して透過を潰す。
$iconSource = $sourceList | Where-Object { $_.BaseName -eq '8010' } | Select-Object -First 1
if ($null -eq $iconSource) { $iconSource = $sourceList[0] }

$iconSetDir = Join-Path $outDir 'AppIcon.appiconset'
New-Item -ItemType Directory -Path $iconSetDir -Force | Out-Null

$bitmap = New-Object System.Drawing.Bitmap(1024, 1024, [System.Drawing.Imaging.PixelFormat]::Format24bppRgb)
$graphics = [System.Drawing.Graphics]::FromImage($bitmap)
try {
    # 透過部分の下地。トップ画面の背景色 #0e1013 に合わせる。
    $graphics.Clear([System.Drawing.Color]::FromArgb(255, 14, 16, 19))
    $graphics.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
    $source = [System.Drawing.Image]::FromFile($iconSource.FullName)
    try {
        $graphics.DrawImage($source, 0, 0, 1024, 1024)
    } finally {
        $source.Dispose()
    }
    $bitmap.Save((Join-Path $iconSetDir 'AppIcon.png'), [System.Drawing.Imaging.ImageFormat]::Png)
} finally {
    $graphics.Dispose()
    $bitmap.Dispose()
}

@'
{
  "images" : [
    {
      "filename" : "AppIcon.png",
      "idiom" : "universal",
      "platform" : "ios",
      "size" : "1024x1024"
    }
  ],
  "info" : {
    "author" : "xcode",
    "version" : 1
  }
}
'@ | Set-Content -LiteralPath (Join-Path $iconSetDir 'Contents.json') -Encoding utf8

Write-Host "#AST06: AppIcon を $($iconSource.Name) から生成 (1024x1024 / アルファなし)"
$total = (Get-ChildItem -LiteralPath $outDir -Recurse -File | Measure-Object -Property Length -Sum).Sum
Write-Host ("#AST07: {0} を生成しました ({1:N1} MB)" -f $outDir, ($total / 1MB))
