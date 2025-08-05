@php
    $params = (object) $data->footerData ?? [];
    $footer = $params->result[0] ?? null;
    $hasAcknowledgedBy = isset($footer->acknowledged_by);
@endphp

<table border="0" cellpadding="1" cellspacing="1" style="width: 100%; margin-bottom: 5rem">
    <tbody>
        <tr>
            @if (!$hasAcknowledgedBy)
                <td style="width: 15%">&nbsp;</td>
            @endif
            <td style="font-size:11px; font-family: Arial, Helvetica, sans-serif; font-weight: bold;">Quote Accepted By:</td>
            @if ($hasAcknowledgedBy)
                <td>&nbsp;</td>
                <td style="font-size:11px; font-family: Arial, Helvetica, sans-serif; font-weight: bold;">Acknowledged by:</td>
                <td>&nbsp;</td>
            @endif
            <td style="font-size:11px; font-family: Arial, Helvetica, sans-serif; font-weight: bold;">Prepared by:</td>
        </tr>
        <tr>
            @if (!$hasAcknowledgedBy)
                <td>&nbsp;</td>
            @endif
            <td style="font-size:11px; height: 3rem;"></td>
            @if ($hasAcknowledgedBy)
                <td>&nbsp;</td>
                <td style="font-size:11px; height: 3rem;"></td>
                <td>&nbsp;</td>
            @endif
            <td style="font-size:11px; height: 3rem;"></td>
        </tr>

        <tr>
            @if (!$hasAcknowledgedBy)
                <td>&nbsp;</td>
            @endif
            <td style="font-size:11px;">.............................................................</td>
            @if ($hasAcknowledgedBy)
                <td>&nbsp;</td>
                <td style="font-size:11px;">.............................................................</td>
                <td>&nbsp;</td>
            @endif
            <td style="font-size:11px;">.............................................................</td>
        </tr>

        <tr>
            @if (!$hasAcknowledgedBy)
                <td>&nbsp;</td>
            @endif
            <td style="font-size:11px;">Name:&nbsp;&nbsp;&nbsp; ..............................................</td>
            @if ($hasAcknowledgedBy)
                <td>&nbsp;</td>
                <td style="font-size:11px;">Name:&nbsp;&nbsp;&nbsp; {{ $footer->acknowledged_by ?? "" }}</td>
                <td>&nbsp;</td>
            @endif
            <td style="font-size:11px;">Name:&nbsp;&nbsp;&nbsp; ..............................................</td>
        </tr>

        <tr>
            @if (!$hasAcknowledgedBy)
                <td>&nbsp;</td>
            @endif
            <td style="font-size:11px;">Position:&nbsp;&nbsp;&nbsp; ...........................................</td>
            @if ($hasAcknowledgedBy)
                <td>&nbsp;</td>
                <td style="font-size:11px;">Position:&nbsp;&nbsp;&nbsp; {{ $footer->acknowledged_by_position ?? "" }} </td>
                <td>&nbsp;</td>
            @endif
            <td style="font-size:11px;">Position:&nbsp;&nbsp;&nbsp; ...........................................</td>
        </tr>

        <tr>
            @if (!$hasAcknowledgedBy)
                <td>&nbsp;</td>
            @endif
            <td style="font-size:11px;">Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ............../.............../...............</td>
            @if ($hasAcknowledgedBy)
                <td>&nbsp;</td>
                <td style="font-size:11px;">Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ............../.............../...............</td>
                <td>&nbsp;</td>
            @endif
            <td style="font-size:11px;">Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ............../.............../...............</td>
        </tr>

    </tbody>
</table>