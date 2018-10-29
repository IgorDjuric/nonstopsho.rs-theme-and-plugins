<?php
/* @var WP_User $supplier */
$supplier = get_user_by('id', $backorders[0]->supplierId);
//if ($backorders[0]->backOrderId > 29 || $backorders[0]->backOrderId == 12) {
//    $supplier = get_users(
//        array(
//            'meta_key' => 'vendorid',
//            'meta_value' => $backorders[0]->supplierId,
//            'number' => 1,
//            'count_total' => false
//        )
//    )[0];
//}

$supplierData = get_user_meta($backorders[0]->supplierId);
?>
<script>
    function printDiv() {
        var w = window.open();
        w.document.write(document.getElementById('content-print').innerHTML);
        w.print();
        w.close();
    }
</script>

<h1>Pregled naloga</h1>

<p>Broj naloga: <?=$backorders[0]->backOrderId?></p>
<p>Dobavljač: <?=$supplier->display_name?> (id: <?=$supplier->ID?>)</p>
<p>Podaci o dobavljaču: <?=$supplierData['vendor_phone'][0] .' '. $supplierData['vendor_address'][0]?></p>
<p>Datum kreiranja: <?=$backorders[0]->createdAt?></p>
<!--<p>Status: </p>-->

<button onclick="printDiv()" class="print">Print</button>
<a href="admin.php?page=nss-orders&tab=backOrderEmail&id=<?=$backorders[0]->backOrderId?>">Email</a>
<a href="admin.php?page=nss-orders&tab=backOrderCopy&id=<?=$backorders[0]->backOrderId?>">Kopija</a>
<!--<a href="admin.php?page=nss-orders&tab=backOrderExport&id=<?=$backorders[0]->backOrderId?>">!Eksport</a> -->
<form method="post" action="">
    <?php
    $text = '';
    include('backOrderDetailsView.php');
    echo $text;
    ?>

<?php if ($backorders[0]->orderStatus != 3): ?>
    <input type="submit" name="submit" value="Sravni nalog" />
<?php endif; ?>
</form>

<div id="content-print" style="display: none">
    <?php
    $text = '';
    include('backOrderPrint.php');
//    echo $text;
    ?>
</div>