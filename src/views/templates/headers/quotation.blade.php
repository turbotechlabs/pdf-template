@php
    $params = (object) $params;
    $isLanscape = strtoupper(($params->orientation ?? "")) === 'L';
    $titleLength = strlen($params->title ?? '');
    $marginTop = -5;

    if (!$isLanscape) {
        $marginTop = $titleLength > 35 ? -4.8 : -5;
    }

    $logo = $params->logo ?? null;

    $company = (object) array_merge(
        (array)[
            "name" => "TURBOTECH CO., LTD.",
            "address" => "N/A",
            "phone" => "N/A",
            "email" => "N/A",
            "website" => "www.turbotech.com.kh"
        ],
        (array) ($params->company ?? [])
    );

    $sales = (object) array_merge(
        (array)[
            "name" => "N/A",
            "phone" => "N/A",
            "email" => "N/A"
        ],
        (array) ($params->sales ?? [])
    );

    $quotation = (object) array_merge(
        (array)[
            "number" => "N/A",
            "currency" => "USD",
            "created_date" => null,
            "expire_date" => null
        ],
        (array) ($params->quotation ?? [])
    );
@endphp


<table style="width:100%; margin-top: 20rem; margin-bottom: 0.75rem;">
    <tr>
        <td style="text-align: center;">
            @if(isset($logo) && $logo)
                <img style="width: 170px; margin-top:-85px;" src="{{ $logo }}" />
            @endif
        </td>

        <td style="width: 750px">
            <table width="100%" style="margin-top: {{ $marginTop }}rem; width: 100%; margin-left: 10px; text-align:left; border-collapse: collapse;">
                <tr>
                    <td style="width: 210px; line-height: 20px">
                        <h1 style="font-size: 16px; font-weight: bold;">{{ $company->name ?? 'TURBOTECH CO., LTD.' }}</h1>
                        <p style="font-size: 14px">Address: {{ $company->address }}</p>
                        <p style="font-size: 14px">Phone:  {{ $company->phone }}</p>
                        <p style="font-size: 14px">E-mail: {{ $company->email }}</p>
                        <p style="font-size: 14px">{{ $company->website ?? 'www.turbotech.com.kh' }}</p>
                    </td>
                    <td style="width: 200px; text-align: left; padding-left: 15px;  line-height: 20px">
                        <h1 style="font-size: 14px; font-weight: bold;">Sales Rep: {{ $sales->name }}</h1>
                        <p style="font-size: 14px">Mobile: {{ $sales->phone }}</p>
                        <p style="font-size: 14px">E-mail: {{ $sales->email }}</p>
                    </td>
                    <td style="width: 120px; text-align: left; line-height: 20px; white-space: nowrap;">
                        <p style="font-size: 14px; white-space: nowrap;">Quote Number: {{ $quotation->number}}</p>
                        <p style="font-size: 14px; white-space: nowrap;">Currency Code: {{ $quotation->currency}}</p>
                        <p style="font-size: 14px; white-space: nowrap;">Quotation Date: {{ $quotation->created_date ? date("d/m/Y", strtotime($quotation->created_date)) : 'N/A' }}</p>
                        <p style="font-size: 14px; white-space: nowrap;">Expire Date: {{ $quotation->expire_date ? date("d/m/Y", strtotime($quotation->expire_date)) : 'N/A' }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="rounded-box" style="width: 100%; font-size: 12px; font-weight: bold;">
    {{ strtoupper($params->title ?? 'Quotation') }}
</div>