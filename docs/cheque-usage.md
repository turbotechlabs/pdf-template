
```php

    // cheque size
    $pdf = new PDF([
        "format" => [180, 90],
        "padding_header" => 5,
        "margin_top" => 5,
        "margin_left" => 5,
        "margin_right" => 5,
        "header" => "",
        "body" => view("accounting::disbursement.payBill.cheque", compact('data')),
        "footer" => ""
    ]);
    $pdf->render();

```