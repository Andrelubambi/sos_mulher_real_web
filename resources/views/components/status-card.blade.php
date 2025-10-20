@props(['card']) {{-- ✅ Agora espera um prop chamado 'card' --}}

<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
    <div class="card-box height-100-p widget-style3">
        <div class="d-flex flex-wrap">
            <div class="widget-data">
                {{-- Acessando os dados via $card --}}
                <div class="weight-700 font-24 text-dark">{{ $card['count'] }}</div>
                <div class="font-14 text-secondary weight-500">{{ $card['label'] }}</div>
            </div>
            <div class="widget-icon">
                <div class="icon" data-color="{{ $card['color'] }}">
                    <i class="icon-copy {{ $card['icon'] }}" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</div>