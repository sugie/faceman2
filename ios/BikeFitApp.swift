// BikeFitApp.swift — 画面とフロー。
// アプリ表示名: Find My Motorcycle
// データと採点は BikeFitData.swift (自動生成) 側にある。
//
// Laravel 版との対応:
//   TopController      -> TopView
//   AnswerController   -> QuestionView (セッション進捗 bf_progress は @State index)
//   ResultController   -> ResultView
//
// サーバ・DB・外部依存なし。単体で完結する。 #IOS01

import SwiftUI

// Xcode がプロジェクト名から自動生成する `<プロジェクト名>App` と
// 名前が衝突しないよう、あえて別名にしてある。 #IOS02
@main
struct MotorcycleApp: App {
    var body: some Scene {
        WindowGroup {
            RootView()
        }
    }
}

// MARK: - 配色
// Web 版 (resources/views/bikefit/index.blade.php) の配色を踏襲する。

// AIComment.swift からも使うので private にしない。
enum Palette {
    static let background = Color(red: 0.055, green: 0.063, blue: 0.075) // #0e1013
    static let panel      = Color(red: 0.082, green: 0.098, blue: 0.129) // #151922
    static let accent     = Color(red: 0.000, green: 0.820, blue: 0.698) // #00d1b2
    static let text       = Color(red: 0.914, green: 0.933, blue: 0.957) // #e9eef4
    static let muted      = Color(red: 0.604, green: 0.655, blue: 0.698) // #9aa7b2
}

// MARK: - フロー

private enum Phase {
    case top
    case question
    case result
}

struct RootView: View {
    @State private var phase: Phase = .top
    @State private var questionIndex = 0
    @State private var picked: [Choice] = []

    var body: some View {
        ZStack {
            Palette.background.ignoresSafeArea()

            switch phase {
            case .top:
                TopView(onStart: start)

            case .question:
                QuestionView(
                    question: BikeFitData.questions[questionIndex],
                    step: questionIndex + 1,
                    total: BikeFitData.questions.count,
                    onPick: pick
                )
                // 質問が変わったことを SwiftUI に伝え、選択状態を持ち越さない。
                .id(questionIndex)

            case .result:
                ResultView(
                    genre: BikeFitData.bestGenre(for: picked),
                    totals: BikeFitData.score(for: picked),
                    picked: picked,
                    onRestart: start
                )
            }
        }
        .preferredColorScheme(.dark)
        .animation(.easeInOut(duration: 0.22), value: phase)
        .animation(.easeInOut(duration: 0.22), value: questionIndex)
    }

    /// 診断をはじめる / やりなおす。
    private func start() {
        picked = []
        questionIndex = 0
        phase = .question
    }

    /// 回答を1件記録し、最後なら結果へ進む。
    private func pick(_ choice: Choice) {
        picked.append(choice)
        if questionIndex + 1 < BikeFitData.questions.count {
            questionIndex += 1
        } else {
            phase = .result
        }
    }
}

// MARK: - トップ

struct TopView: View {
    let onStart: () -> Void

    var body: some View {
        VStack(alignment: .leading, spacing: 0) {
            Spacer()

            // iPhone 12 mini (幅 375pt) でも 2 行に収まる大きさにしてある。
            Text("Find My\nMotorcycle")
                .font(.system(size: 40, weight: .bold))
                .foregroundStyle(Palette.text)
                .lineSpacing(2)
                .minimumScaleFactor(0.8)

            Text("あなたに合うオートバイスタイルを見つける診断")
                .font(.body)
                .foregroundStyle(Palette.muted)
                .padding(.top, 12)

            Text("\(BikeFitData.questions.count)問に答えるだけ。1分で終わります。")
                .font(.subheadline)
                .foregroundStyle(Palette.muted)
                .padding(.top, 24)

            Spacer()

            Button(action: onStart) {
                Text("診断をはじめる")
                    .font(.headline)
                    .foregroundStyle(Palette.background)
                    .frame(maxWidth: .infinity, minHeight: 54)
                    .background(Palette.accent, in: RoundedRectangle(cornerRadius: 12))
            }

            Text("スギエ / Sugie")
                .font(.caption)
                .foregroundStyle(Palette.muted)
                .frame(maxWidth: .infinity, alignment: .center)
                .padding(.top, 14)
                .padding(.bottom, 24)
        }
        .padding(.horizontal, 28)
    }
}

// MARK: - 質問

struct QuestionView: View {
    let question: Question
    let step: Int
    let total: Int
    let onPick: (Choice) -> Void

    var body: some View {
        VStack(alignment: .leading, spacing: 0) {
            // 進捗
            HStack {
                Text(question.section)
                    .font(.caption.weight(.bold))
                    .foregroundStyle(Palette.accent)
                Spacer()
                Text("\(step) / \(total)")
                    .font(.caption.monospacedDigit())
                    .foregroundStyle(Palette.muted)
            }
            .padding(.top, 12)

            ProgressView(value: Double(step), total: Double(total))
                .tint(Palette.accent)
                .padding(.top, 8)

            Text(question.body)
                .font(.title2.weight(.bold))
                .foregroundStyle(Palette.text)
                .fixedSize(horizontal: false, vertical: true)
                .padding(.top, 28)

            ScrollView {
                VStack(spacing: 12) {
                    ForEach(question.choices) { choice in
                        Button {
                            onPick(choice)
                        } label: {
                            HStack {
                                Text(choice.label)
                                    .font(.body.weight(.medium))
                                    .foregroundStyle(Palette.text)
                                    .multilineTextAlignment(.leading)
                                Spacer()
                                Image(systemName: "chevron.right")
                                    .font(.caption.weight(.bold))
                                    .foregroundStyle(Palette.muted)
                            }
                            .padding(.horizontal, 18)
                            .frame(maxWidth: .infinity, minHeight: 58, alignment: .leading)
                            .background(Palette.panel, in: RoundedRectangle(cornerRadius: 10))
                        }
                    }
                }
                .padding(.top, 20)
                .padding(.bottom, 24)
            }
        }
        .padding(.horizontal, 24)
    }
}

// MARK: - 結果

struct ResultView: View {
    let genre: Genre
    let totals: [Int]
    let picked: [Choice]
    let onRestart: () -> Void

    /// 上位3ジャンル。診断の根拠を見せるため。
    private var ranking: [(genre: Genre, score: Int)] {
        zip(BikeFitData.genres, totals)
            .map { (genre: $0, score: $1) }
            .sorted { $0.score > $1.score }
            .prefix(3)
            .map { $0 }
    }

    var body: some View {
        ScrollView {
            VStack(alignment: .leading, spacing: 0) {
                Text("診断結果")
                    .font(.caption.weight(.bold))
                    .foregroundStyle(Palette.accent)
                    .padding(.top, 16)

                Text(genre.name)
                    .font(.system(size: 34, weight: .bold))
                    .foregroundStyle(Palette.text)
                    .padding(.top, 6)

                // 画像は Asset カタログに 8010 … 8150 の名前で入れておく。
                // 未登録でも落ちないようにフォールバックする。
                if UIImage(named: genre.imageName) != nil {
                    Image(genre.imageName)
                        .resizable()
                        .scaledToFill()
                        .frame(height: 220)
                        .clipShape(RoundedRectangle(cornerRadius: 14))
                        .padding(.top, 20)
                }

                // AI による診断。ここが今回の目玉。
                // 通信が失敗しても下の定型文とスコアは出るので、画面は壊れない。
                AICommentCard(picked: picked, genre: genre)
                    .padding(.top, 24)

                Text(genre.detail)
                    .font(.body)
                    .foregroundStyle(Palette.muted)
                    .lineSpacing(6)
                    .fixedSize(horizontal: false, vertical: true)
                    .padding(.top, 20)

                Text("スコア上位")
                    .font(.caption.weight(.bold))
                    .foregroundStyle(Palette.muted)
                    .padding(.top, 32)

                VStack(spacing: 10) {
                    ForEach(ranking, id: \.genre.id) { row in
                        HStack {
                            Text(row.genre.name)
                                .font(.subheadline)
                                .foregroundStyle(Palette.text)
                            Spacer()
                            Text("\(row.score)")
                                .font(.subheadline.monospacedDigit())
                                .foregroundStyle(Palette.accent)
                        }
                        .padding(.horizontal, 16)
                        .frame(minHeight: 44)
                        .background(Palette.panel, in: RoundedRectangle(cornerRadius: 8))
                    }
                }
                .padding(.top, 12)

                Button(action: onRestart) {
                    Text("もう一度診断する")
                        .font(.headline)
                        .foregroundStyle(Palette.background)
                        .frame(maxWidth: .infinity, minHeight: 54)
                        .background(Palette.accent, in: RoundedRectangle(cornerRadius: 12))
                }
                .padding(.top, 32)
                .padding(.bottom, 32)
            }
            .padding(.horizontal, 24)
        }
    }
}

#Preview {
    RootView()
}
