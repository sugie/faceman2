#
$Url = "localhost:6144"

# Waterfox の実行ファイルのパス
$WaterfoxPath = "C:\Program Files\Waterfox\waterfox.exe"

# URL を引数として Waterfox を起動
Start-Process -FilePath $WaterfoxPath -ArgumentList $Url
