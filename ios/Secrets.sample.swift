// Secrets.sample.swift — テンプレート。
//
// Mac 側で下記を実行してから、APIキーを貼って Xcode に追加する:
//
//     cp ios/Secrets.sample.swift ios/Secrets.swift
//
// ios/Secrets.swift は .gitignore 済み。公開リポジトリには乗らない。
// このサンプルのほうは Xcode のターゲットに追加しないこと (二重定義になる)。

enum Secrets {
    /// さくらのAI Engine の APIキー。
    /// さくらのクラウド コントロールパネル → AI Engine から発行する。
    static let sakuraAPIKey = ""
}
