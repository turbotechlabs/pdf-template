@php
    $isLanscape = strtoupper($header->orientation) == 'L';
    $titleLength = strlen($header->title ?? '');

    $companySize = 20;
    $titleSize = 16;
    $periodSize = 14;

    $imageSize = $logoSize ?? 170;

    $hidden = $hiddenLogo ?? true;

    if (!$isLanscape) {
        $lineHeight = $titleLength > 35 ? 28 : 35;

        $companySize = 18;
        $titleSize = 14;
        $periodSize = 12;
        $imageSize -= 20;
    }

@endphp


@if (!$hidden)
    @if(isset($headerImage) && $headerImage)
        <div style="width: {{ $imageSize }}px; position: absolute; top: 3rem; left: 4rem;">
            <img style="width: {{ $imageSize }}px;" src="{{ $headerImage }}" />
        </div>
    @endif
@else
    <div style="text-align: center; width: 100%; line-height: 0.85rem; padding-top: 1.5rem;">
        <h1 style="font-size:{{ $companySize }}px; font-weight: bold;">{{ $header->company ?? '' }}</h1>
        <h2 style="font-size:{{ $titleSize }}px; font-weight: bold;">{{ $header->title ?? '' }}</h2>
        <h3 style="font-size:{{ $periodSize }}px; font-weight: bold;">{{ $header->period ?? '' }}</h3>
    </div>
@endif