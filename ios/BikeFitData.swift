// BikeFitData.swift — 自動生成。手で編集しないこと。
// 生成元: database/migrations/2025_10_26_010000_seed_bikefit.php
//         app/Services/Bikefit/BikefitService.php
// 再生成: node tools/gen_swift_data.mjs

import Foundation

struct Genre: Identifiable, Hashable {
    let id: Int
    let name: String
    let detail: String
    /// Asset カタログ上の画像名 (例: "8010")
    var imageName: String { String(id) }
}

struct Choice: Identifiable, Hashable {
    let id: Int
    let label: String
    /// BikeFitData.genres と同じ並びの加点。要素数は genres.count と一致する。
    let scores: [Int]
}

struct Question: Identifiable, Hashable {
    let id: Int
    let section: String
    let body: String
    let choices: [Choice]
}

enum BikeFitData {

    static let genres: [Genre] = [
        Genre(id: 8010, name: "ネイキッド", detail: """
            バイクの原点ともいえるスタイル。
            無駄のないシンプルな構造と自然なポジションで、街乗りからツーリングまで万能にこなします。
            バイクと一体になって走る「操る楽しさ」を感じたいあなたに最適です。
            """),
        Genre(id: 8020, name: "スーパースポーツ", detail: """
            空気を切り裂くような加速と精密なコーナリング性能。
            レース直系のDNAを持ち、サーキットはもちろんワインディングでも本領を発揮します。
            スピードと技術を極めたい、情熱的なライダーにおすすめ。
            """),
        Genre(id: 8030, name: "レーサーレプリカ", detail: """
            かつての名レースマシンを公道仕様にした、伝説の再現。
            タイトなポジションと高回転エンジンが生み出す官能的な走り。
            技術とロマンを両立した、ストイックなライダーに。
            """),
        Genre(id: 8040, name: "オフロード", detail: """
            未舗装路、山道、川沿い――どんな道も冒険のステージ。
            軽快な車体と高い走破性で、自然の中を自由に駆け抜けることができます。
            舗装路に飽きた探検家タイプにぴったり。
            """),
        Genre(id: 8050, name: "モタード", detail: """
            オフ車ベースにオンロードタイヤを履かせた、俊敏なストリートファイター。
            街中の交差点もワインディングも、アグレッシブに駆け抜けたい人へ。
            俊敏な操作と軽快さを楽しむ“走りの職人”向け。
            """),
        Genre(id: 8060, name: "ストリートファイター", detail: """
            スーパースポーツの魂を剥き出しにした攻撃的デザイン。
            ハンドル位置が高く扱いやすいのに、走りは本格派。
            街中で強烈な存在感を放ちたいライダーに。
            """),
        Genre(id: 8070, name: "クルーザー", detail: """
            低い重心とどっしりした安定感。
            ゆったりと流れる時間の中で風を感じる――そんな贅沢なひとときを提供します。
            スピードよりも余裕とスタイルを大切にする人へ。
            """),
        Genre(id: 8080, name: "ツアラー", detail: """
            長距離移動の快適性を徹底追求。
            大容量タンク、スクリーン、防風性能、シートの厚みまで、すべては「旅のため」に。
            どこまでも走り続けたい旅人の相棒。
            """),
        Genre(id: 8090, name: "カフェレーサー", detail: """
            1960年代のロンドンを駆け抜けた青年たちのスピリットを継ぐ。
            無駄を削ぎ落したスタイリッシュなフォルムに、独特のスポーティさが漂います。
            クラシックとスピードの融合を愛する美意識高いライダーに。
            """),
        Genre(id: 8100, name: "スクランブラー", detail: """
            オンでもオフでも自由自在。
            クラシカルな見た目に反して、道を選ばない万能タイプ。
            気ままに旅を楽しむ“自由人”にぴったりのバイクです。
            """),
        Genre(id: 8110, name: "アドベンチャー", detail: """
            未知の道を切り拓く冒険マシン。
            大柄な車体に積載力と長距離性能を備え、どんな地形も走破可能。
            「地平線の向こう」を目指す本格派トラベラーに。
            """),
        Genre(id: 8120, name: "クラシック", detail: """
            時を超える美しさと機械の鼓動。
            最新技術よりも“味”や“情緒”を重んじるあなたへ。
            エンジンの鼓動と共に、ゆったりとした時間を楽しめます。
            """),
        Genre(id: 8130, name: "ネオクラシック", detail: """
            レトロなデザインに最新技術を融合。
            見た目はヴィンテージ、でも中身は現代そのもの。
            「懐かしさ」と「新しさ」をバランスよく楽しみたい人に。
            """),
        Genre(id: 8140, name: "スクーター", detail: """
            気軽に乗れて、実用性抜群。
            通勤・買い物からちょっとした遠出まで、ストレスフリーな移動を実現します。
            日常に寄り添う“頼れる足”を求める人に最適。
            """),
        Genre(id: 8150, name: "ミニバイク", detail: """
            小さいけれど、自由で楽しい。
            気軽に扱える軽さと遊び心が魅力。
            街中やキャンプ地でのチョイ乗り、ガレージの相棒にも最高の一台です。
            """),
    ]

    static let questions: [Question] = [
        Question(id: 100, section: "体格", body: "身長のレンジを選んでください", choices: [
            Choice(id: 1001, label: "<160cm", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
            Choice(id: 1002, label: "160-170cm", scores: [40, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40, 40, 40, 40]),
            Choice(id: 1003, label: "170-180cm", scores: [0, 40, 0, 0, 0, 40, 40, 40, 0, 0, 40, 0, 0, 0, 0]),
            Choice(id: 1004, label: ">180cm", scores: [0, 40, 0, 0, 0, 40, 40, 40, 0, 0, 40, 0, 0, 0, 0]),
        ]),
        Question(id: 200, section: "体格", body: "体重のレンジを選んでください", choices: [
            Choice(id: 2001, label: "<55kg", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
            Choice(id: 2002, label: "55-70kg", scores: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]),
            Choice(id: 2003, label: "70-85kg", scores: [0, 0, 0, 0, 0, 0, 40, 40, 0, 0, 40, 0, 0, 0, 0]),
            Choice(id: 2004, label: ">85kg", scores: [0, 0, 0, 0, 0, 0, 40, 40, 0, 0, 40, 0, 0, 0, 0]),
        ]),
        Question(id: 300, section: "体格", body: "足つき性（足がべったり着くこと）の重視度", choices: [
            Choice(id: 3001, label: "気にしない", scores: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]),
            Choice(id: 3002, label: "やや気にする", scores: [30, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 30, 30, 0, 0]),
            Choice(id: 3003, label: "かなり気にする", scores: [50, 0, 0, 0, 0, 0, 50, 0, 0, 0, 0, 0, 0, 50, 50]),
        ]),
        Question(id: 400, section: "経験", body: "バイクの運転経験年数", choices: [
            Choice(id: 4001, label: "なし", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
            Choice(id: 4002, label: "教習中", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
            Choice(id: 4003, label: "1年未満", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
            Choice(id: 4004, label: "1-3年", scores: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]),
            Choice(id: 4005, label: "3年以上", scores: [0, 40, 40, 40, 0, 40, 0, 0, 0, 0, 40, 0, 0, 0, 0]),
        ]),
        Question(id: 500, section: "用途", body: "主な用途（最も近いもの1つ）", choices: [
            Choice(id: 5001, label: "通勤通学", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
            Choice(id: 5002, label: "街乗り", scores: [40, 0, 0, 0, 0, 0, 0, 0, 40, 40, 0, 40, 40, 0, 0]),
            Choice(id: 5003, label: "週末ツーリング", scores: [0, 0, 0, 0, 0, 0, 50, 50, 0, 0, 50, 0, 0, 0, 0]),
            Choice(id: 5004, label: "長距離ツアラー", scores: [0, 0, 0, 0, 0, 0, 50, 50, 0, 0, 50, 0, 0, 0, 0]),
            Choice(id: 5005, label: "オフロード", scores: [0, 0, 0, 50, 50, 0, 0, 0, 0, 0, 50, 0, 0, 0, 0]),
            Choice(id: 5006, label: "サーキット/峠", scores: [0, 50, 50, 0, 50, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0]),
            Choice(id: 5007, label: "二人乗りが多い", scores: [0, 0, 0, 0, 0, 0, 50, 50, 0, 0, 0, 0, 0, 50, 0]),
            Choice(id: 5008, label: "買い物・積載重視", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
        ]),
        Question(id: 600, section: "環境", body: "主な走行環境（最も近いもの1つ）", choices: [
            Choice(id: 6001, label: "都市部", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
            Choice(id: 6002, label: "郊外", scores: [40, 0, 0, 0, 0, 0, 0, 0, 40, 40, 40, 0, 0, 0, 0]),
            Choice(id: 6003, label: "山間部", scores: [40, 0, 0, 0, 0, 0, 0, 0, 40, 40, 40, 0, 0, 0, 0]),
            Choice(id: 6004, label: "高速道路が多い", scores: [0, 0, 0, 0, 0, 0, 50, 50, 0, 0, 50, 0, 0, 0, 0]),
            Choice(id: 6005, label: "未舗装路が多い", scores: [0, 0, 0, 50, 50, 0, 0, 0, 0, 50, 50, 0, 0, 0, 0]),
        ]),
        Question(id: 700, section: "嗜好", body: "取り回しの軽さ重視度", choices: [
            Choice(id: 7001, label: "低", scores: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]),
            Choice(id: 7002, label: "中", scores: [0, 0, 0, 0, 0, 0, 0, 0, 30, 0, 0, 30, 30, 0, 0]),
            Choice(id: 7003, label: "高", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
        ]),
        Question(id: 800, section: "コスト", body: "維持費の重視度", choices: [
            Choice(id: 8001, label: "低", scores: [0, 30, 0, 0, 0, 0, 30, 30, 0, 0, 30, 0, 0, 0, 0]),
            Choice(id: 8002, label: "中", scores: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]),
            Choice(id: 8003, label: "高", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
        ]),
        Question(id: 900, section: "快適", body: "積載・快適性の重視度", choices: [
            Choice(id: 9001, label: "低", scores: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]),
            Choice(id: 9002, label: "中", scores: [30, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 30, 0, 0]),
            Choice(id: 9003, label: "高", scores: [0, 0, 0, 0, 0, 0, 50, 50, 0, 0, 50, 0, 0, 50, 0]),
        ]),
        Question(id: 1000, section: "制約", body: "購入予算（万円）", choices: [
            Choice(id: 10001, label: "<30", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
            Choice(id: 10002, label: "30-60", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
            Choice(id: 10003, label: "60-100", scores: [30, 0, 0, 0, 0, 0, 0, 0, 0, 0, 30, 30, 30, 0, 0]),
            Choice(id: 10004, label: "100-150", scores: [30, 0, 0, 0, 0, 0, 0, 0, 0, 0, 30, 30, 30, 0, 0]),
            Choice(id: 10005, label: ">150", scores: [0, 40, 0, 0, 0, 0, 40, 40, 0, 0, 40, 0, 0, 0, 0]),
        ]),
        Question(id: 1100, section: "免許", body: "現在の二輪免許区分", choices: [
            Choice(id: 11001, label: "原付", scores: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
            Choice(id: 11002, label: "小型限定", scores: [50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 50]),
            Choice(id: 11003, label: "普通二輪", scores: [40, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40, 40, 40, 0, 0]),
            Choice(id: 11004, label: "大型", scores: [0, 50, 0, 0, 0, 0, 50, 50, 0, 0, 50, 0, 0, 0, 0]),
        ]),
    ]

    /// 選んだ選択肢からジャンル別合計点を出す。
    /// Laravel 版 BfWeight::getDiagnostic と同じ計算。
    static func score(for choices: [Choice]) -> [Int] {
        var totals = [Int](repeating: 0, count: genres.count)
        for choice in choices {
            for (index, value) in choice.scores.enumerated() {
                totals[index] += value
            }
        }
        return totals
    }

    /// 最高得点のジャンル。同点なら ID の小さい方を採る
    /// (Laravel 版 BikefitService::getBestOne と同じ挙動)。
    static func bestGenre(for choices: [Choice]) -> Genre {
        let totals = score(for: choices)
        var bestIndex = 0
        for (index, value) in totals.enumerated() where value > totals[bestIndex] {
            bestIndex = index
        }
        return genres[bestIndex]
    }
}
