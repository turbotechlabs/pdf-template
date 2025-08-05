@php
    $params = (object) $data;
    $logo = $params->headerImage ?? null;
@endphp

<table style="border:none;pedding:0;width:100%; border-bottom: 2px solid black;">
    <tr>
        <td>
            @if(isset($logo) && $logo)
                <img style="width: 190px; margin-top:-80px;margin-left:-5px" src="{{ $logo }}" />
            @endif
        </td>
        <td style="padding:0px;margin:0;width:65%;">
            <table style="width:100%;font-family:khmeroscontent;margin-top:-45px">
                <tr style="width:100%;text-align:center;">
                    <td style="text-align:center;width:100%;line-height:35px">
                        <div style="text-align:center;width:100%;">
                            <h3 style="color: #1fa8e1;font-size:25px; font-family:khmerosmoullight">ក្រុមហ៊ុន ធើបូថេក ឯ.ក</h3>
                            <h3 class="" style="color: #1fa8e1;font-size:24px;margin-top: 10px;font-weight: bold">TURBOTECH CO.,LTD</h3>
                        </div>
                    </td>
                </tr>
                <tr style="width:100%;text-align:left;letter-spacing:-0.5">
                    <td style="width:100%;text-align:left;padding-top:0px">
                        <p style="text-align:left;font-size: 14px; font-family:khmerosmoullight; font-weight: bold;text-align:center">
                            <span>
                                លេខអត្តសញ្ញាណកម្ម អតប
                            </span>
                            <span style="font-family:georgia; font-weight:bold;">
                                (VAT TIN) :K008-901701793
                            </span>
                        </p>
                    </td>
                </tr>
                <tr style="width:100%;text-align:left;letter-spacing:-0.5">
                    <td style="width:100%;text-align:left;padding-top:0px">
                        <div style="padding-top:20px;font-size: 12px;text-align:left;">
                            <p style="font-size: 9px; font-family:khmeroscontent; text-align:left">អាសយដ្ឋាន  :&nbsp;&nbsp;ផ្លូវ ៥៩៨ ភូមិ ខ១ សង្កាត់ ច្រាំងចំរេះទី២ ខណ្ឌ ឫស្សីកែវ រាជធានីភ្នំពេញ </p>
                            <p style="font-size: 9px; font-family:georgia;">Address : Street 598, Village Khor I, Sangkat Chrang Chamreh II, Khan Russey Keo, Phnom Penh.</p>
                            <p style="font-size: 9px; font-family:khmeroscontent; text-align:left"><span >ទូរស័ព្ទ</span> (Tel) : &nbsp;(855) 23 999 998, <span>អុីម៉ែល</span> (E-mail) : &nbsp;info@turbotech.com, Website : &nbsp;www.turbotech.com.kh</p>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>