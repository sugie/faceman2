<?php

namespace App\Services\Bikefit;

class BikefitService
{
    public static function getBestOne($genre_scores)
    {
        $max_score = max($genre_scores);
        $best_genres = array_keys($genre_scores, $max_score);
        // If there's a tie, return one of the best genres randomly
        return $best_genres[0];

    }

    public static function getGenreName($genre_id)
    {
        switch ($genre_id) {
            case 8010:
                return 'ネイキッド';
            case 8020:
                return 'スーパースポーツ';
            case 8030:
                return 'レーサーレプリカ';
            case 8040:
                return 'オフロード';
            case 8050:
                return 'モタード';
            case 8060:
                return 'ストリートファイター';
            case 8070:
                return 'クルーザー';
            case 8080:
                return 'ツアラー';
            case 8090:
                return 'カフェレーサー';
            case 8100:
                return 'スクランブラー';
            case 8110:
                return 'アドベンチャー';
            case 8120:
                return 'クラシック';
            case 8130:
                return 'ネオクラシック';
            case 8140:
                return 'スクーター';
            case 8150:
                return 'ミニバイク';
            default:
                return '--';
        }
    }
}
