<?php

namespace App\Repositories\Specimen;

use App\Models\Specimen;
use Carbon\Carbon;

class SpecimenRepository implements SpecimenInterface
{
    private $model;
    public function __construct(
        Specimen $specimenModel
    ) {
        $this->model = $specimenModel;
    }

    public function getQuery($request = [])
    {
        $q = $this->model->query()->where('procedure', 'lab');

        //FILTER
        $q->when($request['status_kirim'] != '', function ($query) use ($request) {
            switch ($request['status_kirim']) {
                case 'waiting':
                    $query->whereNull('satusehat_statuscode_specimen');
                    break;
                case 'failed':
                    $query->where('satusehat_statuscode_specimen', '500');
                    break;
                default:
                    $query->where('satusehat_statuscode_specimen', '200');
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

    public function getDataSpecimenByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->where('procedure', 'lab')->orderBy('id', 'asc')->get();
    }
    public function getDataSpecimenBundleByOriginalCode($original_code)
    {
        return $this->model->whereNull('satusehat_id_specimen')->where('encounter_original_code', $original_code)->where('procedure', 'lab')->orderBy('id', 'asc')->get();
    }


    public function updateDataBundleSpecimenJob($param = [])
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            ->where('procedure', 'lab')
            ->whereNull('satusehat_id_specimen')
            ->orderBy('id', 'asc')
            ->first();
        if (!empty($data)) {
            $data->satusehat_id_specimen = $param['satusehat_id'];
            $data->satusehat_send_specimen = $param['satusehat_send'];
            $data->satusehat_date_specimen = $param['satusehat_date'];
            $data->satusehat_statuscode_specimen = $param['satusehat_statuscode'];
            $data->satusehat_request_specimen = $param['satusehat_request'];
            $data->satusehat_response_specimen = $param['satusehat_response'];
            $data->update();
        }
        return $data;
    }

    public function getDataSpecimenFind($id)
    {
        return $this->model->find($id);
    }
    public function updateStatusSpecimen($id, $satusehat_id, $request, $response)
    {
        $data = $this->model->where('id', $id)
            ->update([
                'satusehat_id_specimen' => $satusehat_id,
                'satusehat_request_specimen' => $request,
                'satusehat_response_specimen' => $response,
                'satusehat_send_specimen' => ($satusehat_id != null) ? 1 : 0,
                'satusehat_statuscode_specimen' => ($satusehat_id != null) ? '200' : '500',
                'satusehat_date_specimen' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);


        return $data;
    }

    public function getDataSpecimenReadyJob()
    {
        return $this->model->join('ss_encounter', 'ss_service_request.encounter_original_code', 'ss_encounter.original_code')
            ->whereNotNull('ss_encounter.satusehat_id')
            ->take(env('MAX_RECORD')) //ambil hanya 100 saja
            ->where('ss_service_request.procedure', 'lab')
            ->where('ss_encounter.satusehat_send', '=', 1)
            ->where('ss_encounter.satusehat_statuscode', '=', '200')

            ->whereNotNull('ss_service_request.satusehat_id')
            ->where('ss_service_request.satusehat_send', '=', 1)
            ->where('ss_service_request.satusehat_statuscode', '=', '200')

            ->where('ss_service_request.satusehat_send_specimen', '!=', 1)
            ->whereNull('ss_service_request.satusehat_id_specimen')
            ->whereNull('ss_service_request.satusehat_statuscode_specimen')
            // ->whereIn('original_code', ['A112306380'])
            ->select('ss_service_request.*')
            ->get();
    }
}
