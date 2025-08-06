
<table border="0" cellspacing="0" cellpadding="5" style="width: 100%; font-size: 12px;">
    <tr>
        <td colspan="3" style="text-align: right; letter-spacing: 5px; padding-right: 0.8rem; padding-top: 0.8rem; font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;">
            {{ $data[0]->date ?? '' }}
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left; padding-left: 5rem; padding-top: 1.5rem; font-style: italic;">
            {{ $data[0]->pay_to ?? '' }}
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left; padding-left: 5rem; padding-top: 0.75rem; font-style: italic;">
            {{ $data[0]->sum_of ?? '' }}
        </td>
    </tr>
    <tr>
        <td style="text-align: right; padding-right: 20px; padding-top: 0.75rem; font-size: 16px; font-weight: bold; font-style: italic; width: 33%"> &nbsp; </td>
        <td style="text-align: right; padding-right: 20px; padding-top: 0.75rem; font-size: 16px; font-weight: bold; font-style: italic; width: 33%"> &nbsp; </td>
        <td style="text-align: left; padding-left: 2rem; padding-top: 0.8rem; font-size: 14px; font-weight: bold; font-style: italic;">
            {{ $data[0]->usd ?? '' }}
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left; padding-left: 5rem; font-style: italic; padding-top: 4.2rem;">
            {{ $data[0]->purpose ?? '' }}
        </td>
    </tr>
    </tbody>
</table>