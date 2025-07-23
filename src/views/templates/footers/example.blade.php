@php
    $footer = (object)$data->footer;
    $text = $footer->text ?? 'ONE-STOP TECHNOLOGY SOLUTIONS';
@endphp



<table style="width: 100%; margin-bottom: 7rem; font-size: 16px; min-width: 850px;">
    <tr>
        <th style="width: 12%;"></th>
        <th style="text-align: center; padding: 1rem; border-top: 2px solid #1fa8e0;">
            <div style="font-size: 14px; margin-top: 0.5rem;">
                Authorize Signature
            </div>
        </th>
        <th style="width: 12%;"></th>
        <th style="text-align: center; padding: 1rem; border-top: 2px solid #1fa8e0;">
            <div style="font-size: 14px; margin-top: 0.5rem;">
                Customer Signature
            </div>
        </th>
        <th style="width: 12%;"></th>
    </tr>
</table>

<table style="width: 100%; font-size: 11px; margin-bottom: 1rem;">
    <tr>
        <th style="text-align: center; padding: 5px; background: #1fa8e0;">
            {{ $text }}
        </th>
    </tr>
</table>