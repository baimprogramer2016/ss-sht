<?php

namespace App\Repositories\ServiceRequest;

use App\Models\ServiceRequest;
use Carbon\Carbon;

class ServiceRequestRepository implements ServiceRequestInterface
{
    private $model;
    public function __construct(
        ServiceRequest $serviceRequestModel
    ) {
        $this->model = $serviceRequestModel;
    }

    public function getQuery($request = [])
    {
        $q = $this->model->query()->where('procedure', 'lab');

        //FILTER
        $q->when($request['status_kirim'] != '', function ($query) use ($request) {
            switch ($request['status_kirim']) {
                case 'waiting':
                    $query->whereNull('satusehat_statuscode');
                    break;
                case 'failed':
                    $query->where('satusehat_statuscode', '500');
                    break;
                default:
                    $query->where('satusehat_statuscode', '200');
            }
            return $query;
        });
        $q->when($request['tanggal_awal'] != '', function ($query) use ($request) {

            $query->whereBetween('authored_on', [
                Carbon::createFromFormat('Y-m-d', $request['tanggal_awal'])->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $request['tanggal_akhir'])->endOfDay(),
            ]);

            return $query;
        });
        return $q;
    }


    public function getDataServiceRequestByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->where('procedure', 'lab')->orderBy('id', 'asc')->get();
    }
    public function getDataServiceRequestBundleByOriginalCode($original_code)
    {
        return $this->model->whereNull('satusehat_id')->where('encounter_original_code', $original_code)->where('procedure', 'lab')->orderBy('id', 'asc')->get();
    }


    public function updateDataBundleServiceRequestJob($param = [])
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

    public function getDataServiceRequestFind($id)
    {
        return $this->model->find($id);
    }
    public function updateStatusServiceRequest($id, $satusehat_id, $request, $response)
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

    public function getDataServiceRequestReadyJob()
    {
        return $this->model->join('ss_encounter', 'ss_service_request.encounter_original_code', 'ss_encounter.original_code')
            ->whereNotNull('ss_encounter.satusehat_id')
            ->take(env('MAX_RECORD')) //ambil hanya 100 saja
            ->where('ss_service_request.procedure', 'lab')
            ->where('ss_encounter.satusehat_send', '=', 1)
            ->where('ss_encounter.satusehat_statuscode', '=', '200')
            ->where('ss_service_request.satusehat_send', '!=', 1)
            ->whereNull('ss_service_request.satusehat_statuscode')
            // ->whereIn('original_code', ['A112306380'])
            ->select('ss_service_request.*')
            ->get();
    }
}
