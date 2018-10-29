<?php

$details = '<table border="1">
    <tr>
        <th>Redni broj</th>
        <th>Šifra NSS</th>
        <th>Šifra proizvoda</th>
        <th>Naziv proizvoda</th>
        <th>Veličina</th>
        <th>MP Cena (kom)</th>
        <th>Poreska osnovica (kom)</th>
        <th>Stopa PDV-a</th>
        <th>Ukupno za naručivanje</th>
        <th>Broj porudžbine</th>
        <th>Način plaćanja</th>
    </tr>';

$i = 1;
foreach ($backorders as $order):
    $order = (object) $order;
    $pdv = str_replace('.00', '', $order->pdv);
    $item = wc_get_product($order->itemId);
    $wcOrder = wc_get_order($order->orderId);
    $orderQty = $order->qty - $item->get_meta('quantity');
    if ($orderQty < 0) {
        $orderQty = 0;
    }

    $details .= '<tr>
            <td>'.$i.'</td>
            <td>'.$order->itemId.'</td>
            <td>'.$item->get_meta('vendor_code').'</td>
            <td>'.$order->name.'</td>
            <td>'.$order->variant.'</td>
            <td>'.$order->price .'</td>
            <td>'.round($order->price * 100 / (100 + $pdv), 2) .'</td>
            <td>'.$order->pdv.'</td>
            <td>'.$orderQty.'</td>
            <td>'.$order->orderId.'</td>
            <td>'.$wcOrder->get_payment_method_title().'</td>
        </tr>';

    $i++;
endforeach;

$details .= '</table>';
