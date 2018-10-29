<p>Broj naloga: <?=$backorders[0]->backOrderId?>, Dobavljač: <?=$supplier->display_name?>,
Podaci o dobavljaču: <?=$supplierData['vendor_phone'][0] .' '. $supplierData['vendor_address'][0]?></p>
<p>Datum kreiranja: <?=$backorders[0]->createdAt?></p>

<div style="border-bottom:1px solid black;height:70px; margin-top:20px">
    <div style="float:left;width:200px">
        Non Stop Shop​ d.o.o. <br />
        Žorža Klemansoa 19<br />
        11000 Beograd <br />
    </div>
    <div style="float:left;width:200px">
        Nonstopshop.rs<br />
        prodaja@nonstopshop.rs<br />
        011 / 33 - 34 - 773 <br />
    </div>
</div>
<div style="clear:left;margin-bottom:20px;margin-top:20px">
    <strong>Nalog za naručivanje / preuzimanje proizvoda</strong>
</div>

<?php
$text = '';
include('backOrderDetailsView.php');
echo $text;
?>

<div style="height:150px;margin-top:20px">
    <div style="float:left;width:500px;">
        <strong>PREDAO NALOG</strong> : <br /><br />
        ________________________________________
    </div>
    <div style="float:left">
        <strong>PREUZEO NALOG</strong> : <br /><br />
        ________________________________________
    </div>
</div>