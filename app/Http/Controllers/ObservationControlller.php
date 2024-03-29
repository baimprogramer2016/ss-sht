<?php

namespace App\Http\Controllers;

use App\Traits\JsonTrait;
use Illuminate\Http\Request;

use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use App\Models\Observation;
use App\Repositories\Observation\ObservationInterface;
use Yajra\DataTables\Facades\Datatables;
use Throwable;

class ObservationControlller extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;
    private $observation_repo;

    public function __construct(
        ObservationInterface $observationInterface,
    ) {
        $this->observation_repo = $observationInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->observation_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($item_observation) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_observation->satusehat_send == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_observation->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('action', function ($item_observation) {


                    if ($item_observation->satusehat_send == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_observation->satusehat_id) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal'  onClick=modalKirimSS('" . $this->enc($item_observation->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
                        $li_response_ss = '';
                    }
                    $action_update = ' <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                            ' .
                        $li_kirim_ss .
                        $li_response_ss
                        . '
                            </ul>
                        </div>';

                    return $action_update;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view("pages.observation.observation");
    }

    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('/Observation', $id);
            return view('pages.observation.observation-response-ss', [
                "data_response" => $response_satusehat
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function modalKirimSS(Request $request, $id)
    {
        try {
            return view('pages.observation.observation-kirim-ss', [
                "data_observation" => $this->observation_repo->getDataObservationFind($this->dec($id)),
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }


    public function kirimSS(Request $request)
    {
        try {
            $data_observation = $this->observation_repo->getDataObservationFind($this->dec($request->id));

            if (empty($data_observation['r_encounter']['satusehat_id'])) {
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_encounter_no')
                ];
                return json_encode($result);
            } else {

                $payload_observation = $this->bodyManualObservation($data_observation);

                $response = $this->post_general_ss('/Observation', $payload_observation);
                $body_parse = json_decode($response->body());

                $satusehat_id = null;
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id = null;
                    } else {
                        $satusehat_id = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->observation_repo->updateStatusObservation($this->dec($request->id), $satusehat_id, $payload_observation, $response);
                    }
                }
                # update status ke database
                return $response;
            }
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}
