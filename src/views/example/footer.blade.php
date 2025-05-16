@php
    $fontSize = $isLandscape ? 12 : 11;
    $width = $isLandscape ? 10 : 8; // REM
    $marginLeft = $isLandscape ? 3.5 : 1.5; // REM
    $marginBottom = $isLandscape ? 0.2 : 0.75; // REM
@endphp

<table style="width: 100%; margin-bottom: {{ $marginBottom }}rem; font-size: {{ $fontSize }}px; ">
    <tr>
        <td style="text-align: center;">
            <h3 style="font-size: 12px;"> Prepared By </h3>
            <table style="width: 100%; text-align: left; margin-top: 6rem; margin-left: {{ $marginLeft }}rem; font-family: 'ttstandinvoice';">
                <tr>
                    <td style="width: 5rem;">Name :</td>
                    <td colspan="5" style="width: {{ $width }}rem;"></td>
                </tr>
                <tr>
                    <td style="width: 5rem;">Position :</td>
                    <td colspan="5" style="width: {{ $width }}rem;"></td>
                </tr>
                <tr>
                    <td style="width: 5rem;">Date :</td>
                    <td colspan="5" style="width: {{ $width }}rem;"></td>
                </tr>
            </table>
        </th>

        <td style="text-align: center;">
            <h3 style="font-size: 12px;"> Checked By </h3>
            <table style="width: 100%; text-align: left; margin-top: 6rem; margin-left: {{ $marginLeft }}rem; font-family: 'ttstandinvoice';">
                <tr>
                    <td style="width: 5rem;">Name :</td>
                    <td colspan="5" style="width: {{ $width }}rem;"></td>
                </tr>
                <tr>
                    <td style="width: 5rem;">Position :</td>
                    <td colspan="5" style="width: {{ $width }}rem;"></td>
                </tr>
                <tr>
                    <td style="width: 5rem;">Date :</td>
                    <td colspan="5" style="width: {{ $width }}rem;"></td>
                </tr>
            </table>
        </th>

        <td style="text-align: center;">
            <h3 style="font-size: 12px;"> Verified By </h3>
            <table style="width: 100%; text-align: left; margin-top: 6rem; margin-left: {{ $marginLeft }}rem; font-family: 'ttstandinvoice';">
                <tr>
                    <td style="width: 5rem;">Name :</td>
                    <td colspan="5" style="width: {{ $width }}rem;"></td>
                </tr>
                <tr>
                    <td style="width: 5rem;">Position :</td>
                    <td colspan="5" style="width: {{ $width }}rem;"></td>
                </tr>
                <tr>
                    <td style="width: 5rem;">Date :</td>
                    <td colspan="5" style="width: {{ $width }}rem;"></td>
                </tr>
            </table>
        </th>

        <td style="text-align: center;">
            <h3 style="font-size: 12px;"> Approved By </h3>
            <table style="width: 100%; text-align: left; margin-top: 6rem; margin-left: {{ $marginLeft }}rem; font-family: 'ttstandinvoice';">
                <tr>
                    <td style="width: 5rem;">Name :</td>
                    <td colspan="5" style="width: {{ $width }}rem;"></td>
                </tr>
                <tr>
                    <td style="width: 5rem;">Position :</td>
                    <td colspan="5" style="width: {{ $width }}rem;"></td>
                </tr>
                <tr>
                    <td style="width: 5rem;">Date :</td>
                    <td colspan="5" style="width: {{ $width }}rem;"></td>
                </tr>
            </table>
        </th>
    </tr>
</table>
