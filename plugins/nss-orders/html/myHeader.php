<div class="wrap">
    <div class="Header">
        <h1>Obrada porudzbenica</h1>
        <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
            <a href="/wp-admin/admin.php?page=nss-orders&tab=backOrderList" class="<?php if (isset($_GET['tab']) && $_GET['tab'] == 'backOrderList'){echo 'nav-tab nav-tab-active';}else{echo 'nav-tab';} ?> ">Pregledaj naloge</a>
<!--            <a href="/wp-admin/admin.php?page=nss-orders&tab=backOrderManualCreate" class="<?php if (isset($_GET['tab']) && $_GET['tab'] == 'backOrderManualCreate'){echo 'nav-tab nav-tab-active';}else{echo 'nav-tab';} ?> ">Kreiraj naloge</a>  -->
            <a href="/wp-admin/admin.php?page=nss-orders&tab=bankReportForm" class="<?php if (isset($_GET['tab']) && $_GET['tab'] == 'bankReportForm'){echo 'nav-tab nav-tab-active';}else{echo 'nav-tab';} ?>">Evidentiranje uplata</a>
<!--            <a href="/wp-admin/admin.php?page=nss-orders&tab=courierReportDeliveredForm" class="<?php if (isset($_GET['tab']) && $_GET['tab'] == 'courierReportDeliveredForm'){echo 'nav-tab nav-tab-active';}else{echo 'nav-tab';} ?> ">Poslate posiljke</a> -->
            <a href="/wp-admin/admin.php?page=nss-orders&tab=courierReportForm" class="<?php if (isset($_GET['tab']) && $_GET['tab'] == 'courierReportForm'){echo 'nav-tab nav-tab-active';}else{echo 'nav-tab';} ?> ">Isporucene posiljke</a>
            <a href="/wp-admin/admin.php?page=nss-orders&tab=itemExport" class="<?php if (isset($_GET['tab']) && $_GET['tab'] == 'itemExport'){echo 'nav-tab nav-tab-active';}else{echo 'nav-tab';} ?> ">! Export proizvoda (jitex)</a>
        </nav>
    </div>
</div>