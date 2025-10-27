<div>
    <!-- 生涯学習センター一覧（Livewire コンポーネント） -->

    <div class="mb-4">
        <label for="zip" style="display:block; font-weight:bold;">郵便番号（ハイフンなし）</label>
        <input id="zip" type="text" wire:model.defer="zipCode" placeholder="例: 1600022" style="padding:6px; width: 200px;" />
        <button type="button" wire:click="search" style="padding:6px 12px;">検索</button>
        <div style="color:#666; font-size: 12px; margin-top: 4px;">3〜7桁の数字で前方一致検索を行います。</div>
    </div>

    @if($centerList === [])
        <div>該当する生涯学習センターはありません。</div>
    @else
        <ul style="list-style: none; padding-left: 0;">
            @foreach($centerList as $center)
                <li style="padding:8px 0; border-bottom: 1px solid #ddd;">
                    <div style="font-weight:bold;">{{ $center['name'] }}</div>
                    <div>〒{{ $center['zipcode'] }} {{ $center['address'] }}</div>
                    <div>TEL: {{ $center['tel'] }}</div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
