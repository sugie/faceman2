<?php

namespace App\Livewire;

use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * 生涯学習センター検索 Livewire コンポーネント
 *  - 画面２にて対象となる生涯学習センターの一覧を表示する。
 *  - デモ用としてインメモリ配列を使用する（DB 未使用）。 #LLC01
 */
class LlcSearch extends Component
{
    /**
     * 入力された郵便番号（ハイフン無し 7 桁を想定）
     */
    public string $zipCode = '';

    /**
     * 検索結果のセンター一覧
     * - 画面レンダリング用の配列
     * - 要素: [name, zipcode, address, tel]
     */
    public array $centerList = [];

    /**
     * 初期化
     * 画面から初期 zip が渡される場合に設定する。
     */
    public function mount(?string $zip = null): void
    {
        if (!empty($zip)) {
            $this->zipCode = preg_replace('/[^0-9]/', '', $zip ?? '');
            $this->search();
        }
    }

    /**
     * 検索実行
     * - 入力検証を行い、部分一致（前方一致）で抽出する。
     */
    public function search(): void
    {
        // 入力正規化と簡易バリデーション
        $zip = preg_replace('/[^0-9]/', '', $this->zipCode);
        $this->zipCode = $zip;

        // 3 桁以上 7 桁以下の数字のみ許可（例: 100, 1600022, 530）
        if ($zip === '' || !preg_match('/^[0-9]{3,7}$/', $zip)) {
            // バリデーション NG の場合は一覧を空にする。
            $this->centerList = [];
            return;
        }

        // デモ用データセット（本来は DB から取得）
        $dataset = $this->dataset();

        // 郵便番号の前方一致でフィルタリング
        $this->centerList = array_values(array_filter($dataset, function (array $row) use ($zip) {
            return Str::startsWith($row['zipcode'], $zip);
        }));
    }

    /**
     * デモ用: 生涯学習センターの固定データ
     * 注意: 実運用では DB や外部 API を利用する。 #LLC02
     *
     * @return array<int,array{name:string,zipcode:string,address:string,tel:string}>
     */
    private function dataset(): array
    {
        return [
            [
                'name' => '千代田区 生涯学習センター',
                'zipcode' => '1010051',
                'address' => '東京都千代田区神田神保町1-1',
                'tel' => '03-1111-2222',
            ],
            [
                'name' => '新宿区 生涯学習総合センター',
                'zipcode' => '1600022',
                'address' => '東京都新宿区新宿3-3-3',
                'tel' => '03-3333-4444',
            ],
            [
                'name' => '大阪市 生涯学習センター 梅田',
                'zipcode' => '5300001',
                'address' => '大阪府大阪市北区梅田1-1-1',
                'tel' => '06-1111-0000',
            ],
            [
                'name' => '大阪市 生涯学習センター なんば',
                'zipcode' => '5560011',
                'address' => '大阪府大阪市浪速区難波中1-2-3',
                'tel' => '06-9999-8888',
            ],
            [
                'name' => '札幌市 生涯学習センター',
                'zipcode' => '0600042',
                'address' => '北海道札幌市中央区大通西1-1',
                'tel' => '011-123-4567',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.llc-search');
    }
}
