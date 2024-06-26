<?php

namespace App\Repositories\Observation;

interface ObservationInterface
{
    public function getQuery($request = []);
    public function getDataObservationByOriginalCode($original_code);
    public function getDataObservationBundleByOriginalCode($original_code);

    public function updateDataBundleObservationJob($param = []);

    public function getDataObservationFind($id);
    public function updateStatusObservation($id, $satusehat_id, $request, $response);

    public function storeObservation($request =  []);
    public function updateObservation($request =  [], $id);
    public function getDataObservationReadyJob();
}
