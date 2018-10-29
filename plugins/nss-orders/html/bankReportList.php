<?php if (count($unresolvedPayments)): ?>
<form method="post" action="admin.php?page=nss-orders&tab=bankReportAction">
    <table>
        <tr>
            <th>Broj porudzbenice</th>
            <th>Ručno povezivanje (Match)</th>
        </tr>
        <?php foreach ($unresolvedPayments as $line): ?>
        <tr>
            <td><input name="orderId[<?=$line ?>]" value="<?=$line ?>" /></td>
            <td><input type="checkbox" name="selected[<?=$line ?>]" /> </td>
        </tr>
    <?php endforeach;?>
    </table>
    <input type="submit" value="Send" />
</form>
<?php endif; ?>