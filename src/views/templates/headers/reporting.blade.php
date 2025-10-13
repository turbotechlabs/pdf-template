@php
    $header = (object)$data;
    $isLanscape = strtoupper($header->orientation) == 'L';
    $titleLength = strlen($header->title ?? '');

    $lineHeight = 35;
    $marginTop = -5;

    $companySize = 20;
    $titleSize = 16;
    $periodSize = 14;

    if (!$isLanscape) {
        $lineHeight = $titleLength > 35 ? 28 : 35;
        $marginTop = $titleLength > 35 ? -4.8 : -5;

        // $companySize = $titleLength > 35 ? 18 : 20;
        // $titleSize = $titleLength > 35 ? 14 : 16;
        // $periodSize = $titleLength > 35 ? 12 : 14;
    }

    $headerImage = $header->headerImage ?? null;

@endphp
<table style="width:100%; margin-top: 20rem; margin-bottom: 0.75rem;">
    <tr>
        <td style="width: 20%; text-align: center;">
            @if(isset($headerImage) && $headerImage)
            <img style="width: 190px; margin-top:-80px; margin-left:-5px" src="{{ $headerImage }}" />
            @endif
        </td>

        <td style="width:80%;">
            <table style="width:100%; text-align:center; margin-right: 10rem; margin-top: {{ $marginTop }}rem;">
                <tr style="width:100%;text-align:center;">
                    <td style="text-align:center;width:100%; line-height: 24px;">
                        <h1 style="font-size:{{ $companySize }}px; font-weight: bold;">{!! $header->company ?? '' !!}</h1>
                        <h2 style="font-size:{{ $titleSize }}px; font-weight: bold;">{!! $header->title ?? '' !!}</h2>
                        <h3 style="font-size:{{ $periodSize }}px; font-weight: bold;">{!! $header->period ?? '' !!}</h3>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>