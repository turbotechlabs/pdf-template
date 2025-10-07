@php
    $params = (object) $data;
    $isLanscape = strtoupper(($data->orientation ?? "")) === 'L';
    $titleLength = strlen($data->title ?? '');
    $marginTop = -5;

    if (!$isLanscape) {
        $marginTop = $titleLength > 35 ? -4.8 : -5;
    }

    $logo = $params->headerImage ?? null;
    $company = (object) array_merge(
        (array)[ "name" => "TURBOTECH CO., LTD.", "address" => "N/A", "phone" => "N/A", "email" => "N/A", "website" => "www.turbotech.com.kh" ],
        (array) ($params->company ?? [])
    );

    $sales = (object) array_merge(
        (array)[ "seller_name" => "N/A", "seller_phone" => "N/A", "seller_email" => "N/A" ],
        (array) ($params->sales ?? [])
    );

    $invoice = (object) array_merge(
        (array)[ "number" => "N/A", "currency" => "USD", "create_date" => null, "expire_date" => null ],
        (array) ($params->invoice ?? [])
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
            <table style="margin-top: {{ $marginTop }}rem; width: 100%; margin-left: 10px; text-align:left; border-collapse: collapse;">
                <tr>
                    <td style="width: 210px; line-height: 20px">
                        <h1 style="font-size: 16px; font-weight: bold;">{{ $company->name ?? 'TURBOTECH CO., LTD.' }}</h1>
                        <p style="font-size: 14px; font-family: 'ttstandinvoice';">Address: {{ str_replace([ "Address", "អាសយដ្ឋាន", " :" ], "", $company->address ?? "N/A") }}</p>
                        <p style="font-size: 14px; font-family: 'ttstandinvoice';">
                            Phone: {{ $company->phone ? preg_replace('/^(\+?855)?\s?(\d{2})\s?(\d{3})\s?(\d{3})$/', '+(855) $2 $3 $4', preg_replace('/[^\d+]/', '', $company->phone)) : 'N/A' }}
                        </p>
                        <p style="font-size: 14px; font-family: 'ttstandinvoice';">E-mail: {{ $company->email ?? "N/A" }}</p>
                        <p style="font-size: 14px; font-family: 'ttstandinvoice';">{{ $company->website ?? 'www.turbotech.com.kh' }}</p>
                        <p style="font-size: 11px; font-family: georgia; font-weight:bold;">
                            VAT TIN: K008-901701793
                        </p>
                    </td>
                    <td style="width: 250px; text-align: left; padding-left: 15px; padding-right: 15px; line-height: 20px">
                        <h1 style="font-size: 14px; font-weight: bold;">Sales Rep: {{ strtoupper($sales->seller_name ?? "N/A") }}</h1>
                        <p style="font-size: 14px;  white-space: nowrap;">
                            @php
                                $digits = preg_replace('/\D/', '', $sales->seller_phone ?? "");
                                if (strlen($digits) === 9) {
                                    $formattedMobile = preg_replace('/^(\d{3})(\d{3})(\d{3})$/', '$1 $2 $3', $digits);
                                } elseif (strlen($digits) === 10) {
                                    $formattedMobile = preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '$1 $2 $3', $digits);
                                } else {
                                    $formattedMobile = $digits;
                                }
                            @endphp
                            Mobile: {{ $formattedMobile ?? 'N/A' }}
                        </p>
                        <p style="font-size: 14px;  white-space: nowrap;">E-mail: {{ $sales->seller_email ?? "N/A" }}</p>
                    </td>
                    <td style="width: 120px; text-align: left; line-height: 20px; white-space: nowrap;">
                        <p style="font-size: 14px; white-space: nowrap;">Invoice No: {{ $invoice->number ?? "N/A"}}</p>
                        <p style="font-size: 14px; white-space: nowrap;">Currency Code: {{ $invoice->currency ?? "USD"}}</p>
                        <p style="font-size: 14px; white-space: nowrap;">Billing Date: {{ $invoice->create_date ? date("d/m/Y", strtotime($invoice->create_date)) : 'N/A' }}</p>
                        <p style="font-size: 14px; white-space: nowrap;">Expire Date: {{ $invoice->expire_date ? date("d/m/Y", strtotime($invoice->expire_date)) : 'N/A' }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="rounded-box" style="width: 100%; font-size: 12px; font-weight: bold; margin-bottom: 1rem;">
    {{ strtoupper($params->title ?? 'Invoice') }}
</div>