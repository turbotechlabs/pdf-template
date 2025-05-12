<table style="width:100%; margin-top: 20rem; margin-bottom: 2rem;">
    <tr>
        <td style="width: 20%; text-align: center;">
            <img style="width: 190px; margin-top:-80px; margin-left:-5px" src="./images/logo.png" />
        </td>

        <td style="width:80%;">
            <table style="width:100%; text-align:center; margin-right: 10rem; margin-top: -5rem;">
                <tr style="width:100%;text-align:center;">
                    <td style="text-align:center;width:100%;line-height:35px">
                        <h1 style="font-size:20px; font-weight: bold">{{ $header->company ?? '' }}</h1>
                        <h2 style="font-size:16px; font-weight: bold">{{ $header->title ?? '' }}</h2>
                        <h3 style="font-size:14px; font-weight: bold">{{ $header->period ?? '' }}</h3>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>