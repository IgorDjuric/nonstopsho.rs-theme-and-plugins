<?php

class NSS_BankReport
{
    public function changeOrdersStatusByIdFromPost()
    {
        $unresolvedPayments = [];
        $resolved = [];
        foreach ($_POST['selected'] as $orderId => $on) {
            /* @var WC_Order $order */
            $order = wc_get_order(explode('-', $_POST['orderId'][$orderId])[1]);
            if (!$order) {
                $unresolvedPayments[] = $orderId;
                continue;
                throw new Exception('Porudžbina ID ' . $orderId . ' nije pronađena' . '</br>');
            }
            $this->changeOrderStatus($order);
            $resolved[] = $order;
        }
        echo sprintf('Izmenjeno ukupno %s porudžbina.', count($resolved));

        return $unresolvedPayments;
    }

    public function changeOrdersStatusByIdFromBankReport()
    {
        $data = $this->parseBankReportData();
        $unresolvedPayments = [];
        $resolved = [];
        foreach ($data as $line) {
            $cleanLine = str_replace('00 ', '', $line);
            $cleanLine = str_replace(' ', '', $cleanLine);
            $orderId = trim(str_replace('\n', '', $cleanLine)[0]);
            try {
                //skip boring items, but display them
                if (!strstr($orderId, '-')) {
                    $unresolvedPayments[] = $orderId;
                    continue;
                }

                /* @var WC_Order $order */
                $order = wc_get_order(explode('-', $orderId)[1]);
                if (!$order) {
                    $unresolvedPayments[] = $orderId;
                    continue;
                }
                $this->changeOrderStatus($order);
                $resolved[] = $order;
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        echo sprintf('Izmenjeno ukupno %s porudžbina.', count($resolved));

        return $unresolvedPayments;
    }

    protected function changeOrderStatus(WC_Order $order)
    {
        $orderStatus = $order->get_status();
        //finalizovano se ignorishe
        if ($orderStatus === 'finalizovano') {
            return;
        }

        //uplatnicom ide u pripremi placeno
        if ($orderStatus === 'cekaseuplata') {
            $order->update_status('u-pripremiplaceno');
            //pouzecem ide u completed
        } elseif ($order->get_payment_method() === 'cod') {
            $order->update_status('finalizovano');
            //nepoznata ?
        } else {
//            throw new Exception('Porudžbina je vec obrađena' . '</br>');
        }
    }

    protected function parseBankReportData()
    {
        if (isset($_POST['orderId'])) {
            if (!isset($_POST['selected'])) {
                return [];
            }
            return $_POST['selected'];
        } else {
            $data = $this->readBankReportFile();
            $worksheet = $data->getActiveSheet()
                ->rangeToArray(
                    'Y28:Y' . $data->getActiveSheet()->getHighestRow(),
                    null,
                    true,
                    true,
                    false
                );

            return $worksheet;
        }
    }

    protected function readBankReportFile()
    {
//        $file = __DIR__ .'/../'. $_FILES['bankReportFile']['name'];
//        move_uploaded_file($_FILES['bankReportFile']['tmp_name'], $file);
//    $spreadSheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls;
        $reader->setReadDataOnly(true);
        $data = $reader->load($_FILES['bankReportFile']['tmp_name']);

        return $data;
    }
}