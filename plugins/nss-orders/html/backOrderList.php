<?php

if (is_admin()) {
    new Gf_Back_Order_Wp_List_Table($backorders);
}
/**
 * Paulund_Wp_List_Table class will create the page to load the table
 */
class Gf_Back_Order_Wp_List_Table
{
    /**
     * Gf_Search_Wp_List_Table constructor.
     *
     * @param \GF\Search\Elastica\TermSearch $search
     */
    public function __construct($backorders)
    {
        $this->list_table_page($backorders);
    }

    /**
     * Display the list table page
     *
     * @return Void
     */
    public function list_table_page($backorders)
    {
        $ListTable = new Back_Order_Wp_List_Table($backorders);
        $ListTable->prepare_items($backorders);
        ?>
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>Pregled naloga</h2>
            <?php $ListTable->display(); ?>
        </div>
        <?php
    }
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Back_Order_Wp_List_Table extends WP_List_Table
{
    /** Class constructor */
    public function __construct($backorders) {

        parent::__construct( [
            'singular' => 'Backorder',
            'plural'   => 'Backorders',
            'ajax'     => false //should this table support ajax?

        ] );

    }

    public function prepare_items($backorders)
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data($backorders);
        usort($data, array(&$this, 'sort_data'));
        $perPage = 30;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page' => $perPage
        ));
        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    public function get_columns()
    {
        $columns = array(
            'backOrderId' => 'Broj naloga',
            'supplier' => 'Dobavljač',
            'createdAt' => 'Datum kreiranja',
            'status' => 'Status',
            'action' => 'Akcija',
        );
        return $columns;
    }

    public function get_hidden_columns()
    {
        return array();
    }

    public function get_sortable_columns()
    {
        return array(
            'backOrderId' => array('backOrderId', false),
            'supplier' => array('supplier', true),
            'createdAt' => array('createdAt', false),
            'status' => array('status', false),

        );
    }

    private function table_data($backorders)
    {
        $data = [];
        foreach ($backorders as $order) {
            $supplier = get_user_by('id', $order->supplierId);

            if ($order->status == 1): $status = 'novi';
            elseif ($order->status == 2): $status = 'naručen';
            elseif ($order->status == 3): $status = 'parcijalno izvršen';
            else: $status = 'izvršen'; endif;

            if ($order->status == 1):
                $action = '<a href="/wp-admin/admin.php?page=nss-orders&tab=backOrderEmail&id=<?= $backorders[0]->backOrderId ?>">Email</a>
                            <a href="/wp-admin/admin.php?page=nss-orders&tab=backOrderProcess&id=<?= $order->backOrderId ?>">Pregledaj</a>';
            elseif ($order->status == 2 || $order->status == 3):
                $action = '<a href="/wp-admin/admin.php?page=nss-orders&tab=backOrderProcess&id=<?= $order->backOrderId ?>">Sravni</a>';
            else:
                $action = '<a href="/wp-admin/admin.php?page=nss-orders&tab=backOrderProcess&id=<?= $order->backOrderId ?>">Pregledaj</a>';
            endif;

            $data[] = array(
                'backOrderId' => $order->backOrderId,
                'supplier' => $supplier->display_name,
                'createdAt' => $order->createdAt,
                'status' => $status,
                'action' => $action,
            );
        }
        return $data;
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'backOrderId':
            case 'supplier':
            case 'createdAt':
            case 'status':
            case 'action':
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    private function sort_data($a, $b)
    {
        // Set defaults
        $orderby = 'createdAt';
        $order = 'desc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc')
        {
            return $result;
        }
        return -$result;

    }
}


if (isset($_POST['backOrdersManualCreate'])) {
    echo '<div class="notice notice-success is-dismissible">
        <p>Uspešno ste kreirali naloge!</p>
    </div>';
}

?>
<!--<h1>Pregled naloga</h1>-->
<!--<table border="1">-->
<!--    <tr>-->
<!--        <!--        <th>Izaberi</th>-->-->
<!--        <th>Broj naloga</th>-->
<!--        <th>Dobavljač</th>-->
<!--        <th>Datum kreiranja</th>-->
<!--        <th>Status</th>-->
<!--        <th>Akcija</th>-->
<!--    </tr>-->
<!--    --><?php //foreach ($backorders as $order):
//        $supplier = get_user_by('id', $order->supplierId);
////    if ($order->backOrderId > 29 || $order->backOrderId == 12) {
////        $supplier = get_users(
////            array(
////                'meta_key' => 'vendorid',
////                'meta_value' => $order->supplierId,
////                'number' => 1,
////                'count_total' => false
////            )
////        )[0];
////    }
//
//        ?>
<!--        <tr>-->
<!--            <td>--><?//= $order->backOrderId ?><!--</td>-->
<!--            <td>--><?//= $supplier->display_name ?><!--</td>-->
<!--            <td>--><?//= $order->createdAt ?><!--</td>-->
<!--            <td>--><?php //if ($order->status == 1): echo 'novi';
//                elseif ($order->status == 2): echo 'naručen';
//                elseif ($order->status == 3): echo 'parcijalno izvršen';
//                else: echo 'izvršen'; endif; ?><!--</td>-->
<!--            <td>-->
<!--                --><?php //if ($order->status == 1): ?>
<!--                    <a href="/wp-admin/admin.php?page=nss-orders&tab=backOrderEmail&id=--><?//= $backorders[0]->backOrderId ?><!--">Email</a>-->
<!--                    <a href="/wp-admin/admin.php?page=nss-orders&tab=backOrderProcess&id=--><?//= $order->backOrderId ?><!--">Pregledaj</a>-->
<!--                --><?php //elseif ($order->status == 2 || $order->status == 3): ?>
<!--                    <a href="/wp-admin/admin.php?page=nss-orders&tab=backOrderProcess&id=--><?//= $order->backOrderId ?><!--">Sravni</a>-->
<!--                --><?php //else: ?>
<!--                    <a href="/wp-admin/admin.php?page=nss-orders&tab=backOrderProcess&id=--><?//= $order->backOrderId ?><!--">Pregledaj</a>-->
<!--                --><?php //endif; ?>
<!--            </td>-->
<!--        </tr>-->
<!--    --><?php //endforeach; ?>
<!--</table>-->