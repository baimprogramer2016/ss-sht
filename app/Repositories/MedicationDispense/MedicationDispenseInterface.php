<?php

namespace App\Repositories\MedicationDispense;

interface MedicationDispenseInterface
{
    public function getQuery($request = []);
    public function getDataMedicationDispenseByOriginalCode($original_code);
    public function getDataMedicationDispenseBundleByOriginalCode($original_code);

    public function updateDataBundleMedicationDispenseJob($param = []);
    public function getDataMedicationDispenseFind($id);
    public function updateStatusMedicationDispense($id, $satusehat_id, $request, $response);

    public function getDataMedicationDispenseReadyJob();
}
