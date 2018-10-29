<?php
/**
 * @var WC_Order $order
 */
?>

<h1>Kreiranje naloga</h1>
<form method="post" action="/wp-admin/admin.php?page=my-reports&tab=backOrderCreate">
<table border="1">
    <tr>
        <th>Izaberi</th>
        <th>Broj narudžbenice</th>
        <th>Datum i vreme narudžbenice</th>
        <th>Tip</th>
        <th>Vrednost</th>
        <th>Način plaćanja</th>
        <th>Status</th>
    </tr>
    <?php foreach ($orders as $order): ?>
        <tr>
            <td>
                <input type="checkbox" name="orderId[]" value="<?=$order->get_id()?>" checked />
                <?php //echo CHtml::checkBox('za-nalog['.$d->gpoid.']', true)?>
                <?php //echo CHtml::hiddenField('nalog[]', $d->gpoid)?>
            </td>
            <td><?=$order->get_id()?>  ##  <?=$order->get_order_number()?></td>
            <td><?=$order->get_date_created()?></td>
            <td><?=$order->get_created_via() ?></td>
            <td><?=$order->get_total()?></td>
            <td><?=$order->get_payment_method_title()?></td>
            <td><?=$order->get_status()?></td>
        </tr>
    <?php endforeach; ?>
</table>
    <input type="submit" value="Posalji" />
</form>