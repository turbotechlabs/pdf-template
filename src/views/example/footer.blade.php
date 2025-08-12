@php
    $fontSize = $isLandscape ? 12 : 11;
    $width = $isLandscape ? 10 : 8; // REM
    $marginLeft = $isLandscape ? 3.5 : 1.5; // REM
    $marginBottom = $isLandscape ? 0.2 : 0.75; // REM

    $signatures = [
        'prepared_by' => [
            "title" => 'Prepared By',
            "name" => null,
            "position" => null,
            "date" => null,
            "show" => $showPreparedBy ?? true
        ],
        'checked_by' => [
            "title" => 'Checked By',
            "name" => null,
            "position" => null,
            "date" => null,
            "show" => $showCheckedBy ?? true
        ],
        'verified_by' => [
            "title" => 'Verified By',
            "name" => null,
            "position" => null,
            "date" => null,
            "show" => $showVerifiedBy ?? false
        ],
        'approved_by' => [
            "title" => 'Approved By',
            "name" => null,
            "position" => null,
            "date" => null,
            "show" => $showApprovedBy ?? true
        ]
    ];

    $totalSignatures = count($signatures);
@endphp

<table style="width: 100%; margin-bottom: {{ $marginBottom }}rem; font-size: {{ $fontSize }}px; ">
    <tr>
        @foreach ($signatures as $key => $sign)
            @php
                $sign = (object) $sign;
                $width = $totalSignatures > 2 ? 8 : 10;
            @endphp

            @if ($sign->show == 1)
                <td style="text-align: center; padding: {{ $totalSignatures < 4 ? '3' : '0' }}rem;">
                    <h3 style="font-size: 12px;"> Prepared By </h3>
                    <table style="width: 100%; text-align: left; margin-top: 6rem; margin-left: {{ $marginLeft }}rem; font-family: 'ttstandinvoice';">
                        <tr>
                            <td style="width: 5rem;">Name :</td>
                            <td colspan="5" style="width: {{ $width }}rem;"> {{ $sign->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="width: 5rem;">Position :</td>
                            <td colspan="5" style="width: {{ $width }}rem;"> {{ $sign->position ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="width: 5rem;">Date :</td>
                            <td colspan="5" style="width: {{ $width }}rem;"> {{ $sign->date ?? '' }}</td>
                        </tr>
                    </table>
                </th>
            @endif
        @endforeach
    </tr>
</table>
