// AIComment.swift — さくらのAI Engine (Kimi K2.6) でパーソナライズ診断文を作る。
//
// 設計方針:
//   スコア計算による判定は今までどおり残す。AI はその上に乗せる。
//   通信が失敗しても結果画面は壊れない。デモ中に Wi-Fi が落ちても発表は続く。 #AI01
//
// APIキーは Secrets.swift に置く。あれは .gitignore 済みなので公開リポジトリに乗らない。

import SwiftUI

enum SakuraAI {

    static let endpoint = URL(string: "https://api.ai.sakura.ad.jp/v1/chat/completions")!

    /// モデル名。変わったらここだけ直す。
    /// 一覧は curl https://api.ai.sakura.ad.jp/v1/models -H "Authorization: Bearer $KEY" で取れる。
    static let model = "Kimi-K2.6"

    /// デモ用に短めに切る。会場の回線が遅くても画面が固まらないようにする。
    static let timeoutSeconds: TimeInterval = 12

    // MARK: - 通信

    struct ChatMessage: Codable {
        let role: String
        let content: String
    }

    private struct RequestBody: Encodable {
        let model: String
        let messages: [ChatMessage]
        let temperature: Double
        let max_tokens: Int
    }

    private struct ResponseBody: Decodable {
        struct Choice: Decodable { let message: ChatMessage }
        let choices: [Choice]
    }

    enum Failure: LocalizedError {
        case missingKey
        case badStatus(Int, String)
        case noContent

        var errorDescription: String? {
            switch self {
            case .missingKey:
                return "#AI02: Secrets.sakuraAPIKey が空です"
            case .badStatus(let code, let body):
                return "#AI03: HTTP \(code) / \(body.prefix(200))"
            case .noContent:
                return "#AI04: 応答に本文がありません"
            }
        }
    }

    static func complete(system: String, user: String) async throws -> String {
        let key = Secrets.sakuraAPIKey.trimmingCharacters(in: .whitespacesAndNewlines)
        guard !key.isEmpty else { throw Failure.missingKey }

        var request = URLRequest(url: endpoint)
        request.httpMethod = "POST"
        request.timeoutInterval = timeoutSeconds
        request.setValue("Bearer \(key)", forHTTPHeaderField: "Authorization")
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")
        request.httpBody = try JSONEncoder().encode(
            RequestBody(
                model: model,
                messages: [
                    ChatMessage(role: "system", content: system),
                    ChatMessage(role: "user", content: user),
                ],
                temperature: 0.8,
                max_tokens: 500
            )
        )

        let (data, response) = try await URLSession.shared.data(for: request)

        if let http = response as? HTTPURLResponse, !(200..<300).contains(http.statusCode) {
            throw Failure.badStatus(http.statusCode, String(data: data, encoding: .utf8) ?? "")
        }

        let decoded = try JSONDecoder().decode(ResponseBody.self, from: data)
        let text = (decoded.choices.first?.message.content ?? "")
            .trimmingCharacters(in: .whitespacesAndNewlines)
        guard !text.isEmpty else { throw Failure.noContent }
        return text
    }

    // MARK: - プロンプト

    private static let systemPrompt = """
    あなたはバイク選びに詳しい、話しやすいアドバイザーです。
    必ず日本語で、150〜200文字程度で答えてください。
    前置き・箇条書き・見出しは使わず、地の文だけで書いてください。
    相手の回答内容に具体的に触れて、その人だけに向けた文章にしてください。
    """

    /// 11問の回答と判定ジャンルから、その人向けの診断文を作る。
    static func personalDiagnosis(picked: [Choice], genre: Genre) async throws -> String {
        let answers = zip(BikeFitData.questions, picked)
            .map { "・\($0.body) → \($1.label)" }
            .joined(separator: "\n")

        let user = """
        次の人に合うバイクを診断してください。

        【この人の回答】
        \(answers)

        【スコア計算による第一候補】
        \(genre.name)

        この人がなぜ「\(genre.name)」に向いているのかを、上の回答の具体的な中身に触れながら説明してください。
        """

        return try await complete(system: systemPrompt, user: user)
    }
}

// MARK: - 画面部品

/// 結果画面に差し込む AI コメント欄。
/// 読み込み中・成功・失敗の 3 状態を持ち、失敗しても静かに引き下がる。
struct AICommentCard: View {
    let picked: [Choice]
    let genre: Genre

    @State private var comment: String?
    @State private var didFail = false

    var body: some View {
        VStack(alignment: .leading, spacing: 12) {
            HStack(spacing: 6) {
                Image(systemName: "sparkles")
                Text("AI による診断")
                Spacer()
                Text(SakuraAI.model)
                    .font(.caption2)
                    .foregroundStyle(Palette.muted)
            }
            .font(.caption.weight(.bold))
            .foregroundStyle(Palette.accent)

            if let comment {
                Text(comment)
                    .font(.body)
                    .foregroundStyle(Palette.text)
                    .lineSpacing(6)
                    .fixedSize(horizontal: false, vertical: true)
            } else if didFail {
                // デモ中に落ちても、下のスコア計算による診断は出ている。
                Text("いまはネットワークにつながらないため、AI コメントは省略しました。")
                    .font(.subheadline)
                    .foregroundStyle(Palette.muted)
            } else {
                HStack(spacing: 10) {
                    ProgressView().tint(Palette.accent)
                    Text("あなたの回答を読んでいます…")
                        .font(.subheadline)
                        .foregroundStyle(Palette.muted)
                }
            }
        }
        .padding(18)
        .frame(maxWidth: .infinity, alignment: .leading)
        .background(Palette.panel, in: RoundedRectangle(cornerRadius: 12))
        .overlay(
            RoundedRectangle(cornerRadius: 12)
                .stroke(Palette.accent.opacity(0.35), lineWidth: 1)
        )
        .task {
            do {
                comment = try await SakuraAI.personalDiagnosis(picked: picked, genre: genre)
            } catch {
                // 原因は Xcode のコンソールに出す。画面には出さない。
                print("#AI05: AI コメント取得に失敗: \(error.localizedDescription)")
                didFail = true
            }
        }
    }
}
