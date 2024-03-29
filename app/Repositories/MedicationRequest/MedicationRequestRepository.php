<?php

namespace App\Repositories\MedicationRequest;

use App\Models\MedicationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MedicationRequestRepository implements MedicationRequestInterface
{
    private $model;
    public function __construct(MedicationRequest $medicationRequestModel)
    {
        $this->model = $medicationRequestModel;
    }

    # untuk mendapatkan keseluruhan data
    public function getDataMedicationRequestByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->get();
    }

    public function getQuery()
    {
        return $this->model->query();
    }

    public function getDataMedicationRequestFind($id)
    {
        return $this->model->find($id);
    }

    public function getDataMedicationRequestByOriginalCodeReady($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }

    # update medication
    public function updateDataBundleMedicationJob($param = [])
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            ->whereNull('satusehat_id_medication')
            ->orderBy('id', 'asc')
            ->first();
        if (!empty($data)) {
            $data->satusehat_id_medication = $param['satusehat_id'];
            $data->update();
        }
        return $data;
    }

    public function updateDataBundleMedicationRequestJob($param = [])
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            ->whereNull('satusehat_id')
            ->orderBy('id', 'asc')
            ->first();
        if (!empty($data)) {
            $data->satusehat_id = $param['satusehat_id'];
            $data->satusehat_send = $param['satusehat_send'];
            $data->satusehat_date = $param['satusehat_date'];
            $data->satusehat_statuscode = $param['satusehat_statuscode'];
            $data->satusehat_request = $param['satusehat_request'];
            $data->satusehat_response = $param['satusehat_response'];
            $data->update();
        }
        return $data;
    }


    public function updateStatusMedication($id, $satusehat_id, $request, $response)
    {
        $data = $this->model->where('id', $id)
            ->update([
                'satusehat_id_medication' => $satusehat_id
            ]);


        return $data;
    }
    public function updateStatusMedicationRequest($id, $satusehat_id, $request, $response)
    {
        $data = $this->model->where('id', $id)
            ->update([
                'satusehat_id' => $satusehat_id,
                'satusehat_request' => $request,
                'satusehat_response' => $response,
                'satusehat_send' => ($satusehat_id != null) ? 1 : 0,
                'satusehat_statuscode' => ($satusehat_id != null) ? '200' : '500',
                'satusehat_date' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);


        return $data;
    }
    public function getDataMedicationRequestIdentifier($identifier_1, $identifier_2)
    {
        return $this->model->where('identifier_1', $identifier_1)->where('identifier_2', $identifier_2)->first();
    }
}
