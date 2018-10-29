<?php
/**
 * @var WP_User $supplier
 */

$text .= '<table border="1">
    <tr>
<!--        <th>Izaberi</th>-->
        <th>Šifra NSS</th>
        <th>Šifra proizvoda</th>
        <th>Naziv proizvoda</th>
        <th>Veličina</th>
        <th>MP Cena (kom)</th>
        <th>Poreska osnovica (kom)</th>
        <th>Stopa PDV-a</th>
        <th>Naručeno komada</th>
        <th>Ima na stanju</th>
        <th>Ukupno za naručivanje</th>
        <th>Potvrdi</th>
        <th>Broj porudzbine</th>
        <th>Način placanja</th>
    </tr>';

foreach ($backorders as $order):
    //@TODO should not be needed
    $pdv = str_replace('"', '', $order->pdv);

    $pdv = str_replace('.00', '', $pdv);
    $item = wc_get_product($order->itemId);
    $wcOrder = wc_get_order($order->orderId);
    $orderQty = $order->qty - $item->get_meta('quantity');
    if ($orderQty < 0) {
        $orderQty = 0;
    }
    $style = '';
    $input = 'checked';
    if ($order->itemStatus) {
        $style = 'style="color:red"';
        $input = 'disabled';
    }

    $text .= '<tr '.$style.'>
            <td>'.$item->get_sku().'</td>
            <td>'.$item->get_meta('vendor_code').'</td>
            <td>'.$order->name.'</td>
            <td>'.$order->variant.'</td>
            <td>'.$order->price .'</td>
            <td>'.round($order->price * 100 / (100 + $pdv), 2) .'</td>
            <td>'.$order->pdv.'</td>
            <td>'.$order->qty.'</td>
            <td>'.(int) $item->get_meta('quantity').'</td>
            <td>'.$orderQty.'</td>
            <td><input type="checkbox" name="itemId['.$wcOrder->get_id().']['.$order->itemId.']" '. $input . ' /></td>
            <td>'. nss_woocommerce_order_number('', $wcOrder).'</td>
            <td>'.$wcOrder->get_payment_method_title().'</td>
        </tr>';
    
endforeach;

$text .= '</table>';
