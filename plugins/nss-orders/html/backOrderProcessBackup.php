<?php
/* @var WP_User $supplier */
$supplier = get_users(
    array(
        'meta_key' => 'vendorid',
        'meta_value' => $backorders[0]->supplierId,
        'number' => 1,
        'count_total' => false
    )
)[0];
$supplierData = get_user_meta($supplier->ID);
?>

<h1>Pregled naloga</h1>

<p>Broj naloga: <?=$backorders[0]->backOrderId?></p>
<p>Dobavljač: <?=$supplier->display_name?></p>
<p>Podaci o dobavljaču: <?=$supplierData['vendor_phone'][0] .' '. $supplierData['vendor_address'][0]?></p>
<p>Datum kreiranja: <?=$backorders[0]->createdAt?></p>
<!--<p>Status: </p>-->

<form method="post" action="">
<table border="1">
    <tr>
<!--        <th>Izaberi</th>-->
        <th>Šifra NSS</th>
        <th>Šifra proizvoda</th>
        <th>Naziv proizvoda</th>
        <th>Velicina</th>
        <th>MP Cena (kom)</th>
        <th>Poreska osnovica (kom)</th>
        <th>Stopa PDV-a</th>
        <th>Naručeno komada</th>
        <th>Ima na stanju</th>
        <th>Ukupno za naručivanje</th>
        <th>Potvrdi</th>
        <th>Broj porudzbine</th>
        <th>Nachin placanja</th>
    </tr>
    <?php foreach ($backorders as $order):
        //@TODO should not be needed
        $pdv = str_replace('"', '', $order->pdv);

        $pdv = str_replace('.00', '', $pdv);
        $item = wc_get_product($order->itemId);
        $wcOrder = wc_get_order($order->orderId);
        $orderQty = $order->qty - $item->get_meta('quantity');
        if ($orderQty < 0) {
            $orderQty = 0;
        }
    ?>
        <tr <?php if ($order->itemStatus): echo 'style="color:red"'; endif; ?>>
            <td><?=$order->itemId?></td>
            <td><?=$item->get_meta('vendor_code')?></td>
            <td><?=$order->name?></td>
            <td><?=$order->variant?></td>
            <td><?=$order->price ?></td>
            <td><?=round($order->price * 100 / (100 + $pdv), 2) ?></td>
            <td><?=$order->pdv?></td>
            <td><?=$order->qty?></td>
            <td><?=(int) $item->get_meta('quantity')?></td>
            <td><?=$orderQty?></td>

            <td><input type="checkbox" name="itemId[<?=$order->itemId?>]" <?php if ($order->itemStatus): echo 'disabled';
            else: echo 'checked'; endif; ?> /></td>
            <td><?=$order->orderId?></td>
            <td><?=$wcOrder->get_payment_method_title()?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php if ($backorders[0]->orderStatus != 3): ?>
    <input type="submit" name="submit" value="Sravni nalog" />
<?php endif; ?>
</form>