<?php

class NSS_CourierReport
{

    public function changeOrderStatusByIdFromCourierReport()
    {
        $data = $this->parseCourierReportData();
        $unresolvedData = [];
        foreach ($data as $line) {
            try {
                $explodedId = explode('-', $line[0]);
                if (!isset($explodedId[1])) {
                    throw new Exception('ID ' . $line[0] . ' nije pronadjen u bazi' . '</br>');
                }
                $orderId = $explodedId[1];
                $order = wc_get_order($orderId);
                if (!$order) {
                    throw new Exception('Porudžbina ID ' . $line[0] . ' nije pronađena' . '</br>');
                }
                $orderStatus = $order->get_status();
                //@TODO  not complete. change status according to order payment type.
                if ($orderStatus != 'Completed') {
                    $order->update_status('wc-completed');
                } else {
                    throw new Exception('Porudžbina je več obrađema' . '</br>' );
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
//    var_dump($unresolvedData);
        return $unresolvedData;
    }

    /**
     * @return array
     */
    protected function parseCourierReportData()
    {
        $data = $this->readCourierReportFile();
        $worksheet = $data->getActiveSheet()
            ->rangeToArray(
                'G2:G' . $data->getActiveSheet()->getHighestRow(),
                null,
                true,
                true,
                false
            );

        return $worksheet;
    }

    protected function readCourierReportFile()
    {
        move_uploaded_file($_FILES['courierReportFile']['tmp_name'], '/tmp/tmp.xlsx');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $data = $reader->load('/tmp/tmp.xlsx');

        return $data;
    }
}