<?php

namespace App\Repositories\ServiceRequestRadiology;

interface ServiceRequestRadiologyInterface
{
    public function getQuery($request = []);

    public function getDataServiceRequestRadiologyByOriginalCode($original_code);
    public function getDataServiceRequestRadiologyBundleByOriginalCode($original_code);
    public function updateDataBundleServiceRequestRadiologyJob($param = []);

    public function getDataServiceRequestRadiologyFind($id);

    public function updateStatusServiceRequestRadiology($id, $satusehat_id, $request, $response);
    public function getDataServiceRequestRadiologyReadyJob();
}
