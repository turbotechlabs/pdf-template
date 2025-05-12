
<table style="width:100%; margin-left: 0; margin-right: 0; margin-top: 2rem; margin-bottom: 2rem; border: thin solid white; border-collapse: collapse; font-family: 'ttstandinvoice';">

    <tr>
        @for ($i = 0; $i < $cols; $i++)
            <th style="border: thin solid white; hieght: 30px; padding: 15px; background-color: #1fa8e0"></th>
        @endfor
    </tr>

    @for ($i = 0; $i < $rows; $i++)
        <tr>
            @for ($j = 0; $j < $cols; $j++)
                <td style="border: thin solid white; padding: 12px; background-color: {{ $i % 2 == 0 ? '#82dcff' : '#c2eeff' }}"></td>
            @endfor
        </tr>
    @endfor

</table>
