<?php

namespace App\Services\Bikefit;

class BikefitService
{
    public static function getBestOne($genre_scores)
    {
        $max_score = max($genre_scores);
        $best_genres = array_keys($genre_scores, $max_score);
        // If there's a tie, return one of the best genres randomly
        return $best_genres[array_rand($best_genres)];

    }

    public static function getGenreName($genre_id)
    {
        switch ($genre_id) {
            case 1:
                return 'ネイキッド';
            case 2:
                return 'スーパースポーツ';
            case 3:
                return 'レーサーレプリカ';
            case 4:
                return 'オフロード';
            case 5:
                return 'モタード';
            case 6:
                return 'ストリートファイター';
            case 7:
                return 'クルーザー';
            case 8:
                return 'ツアラー';
            case 9:
                return 'カフェレーサー';
            case 10:
                return 'スクランブラー';
            case 11:
                return 'アドベンチャー';
            case 12:
                return 'クラシック';
            case 13:
                return 'ネオクラシック';
            case 14:
                return 'スクーター';
            case 15:
                return 'ミニバイク';
            default:
                return '--';
        }
    }
}
