<?php

namespace App\Http\Controllers;

use App\Repositories\Practitioner\PractitionerInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Traits\GeneralTrait;
use Throwable;


class PracticionerController extends Controller
{
    use GeneralTrait;
    public $practitioner_repo;
    public function __construct(PractitionerInterface $practitionerRepository)
    {
        $this->practitioner_repo = $practitionerRepository;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = $this->practitioner_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($item_practitioner) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_practitioner . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    if ($item_practitioner->satusehat_process == 1) {
                        $li_update = '';
                        $li_update_ihs = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_practitioner->nik) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_update = "<li><a href='#file-upload' data-toggle='modal' onClick=modalUbah('" . $this->enc($item_practitioner->id) . "')><em class='icon ni ni-edit'></em><span>Ubah</span></a></li>";
                        $li_update_ihs = "<li><a href='#file-upload' data-toggle='modal' onClick=modalUpdateIhs('" . $this->enc($item_practitioner->id) . "')><em class='icon ni ni-send'></em><span>Update ID IHS</span></a></li>";
                        $li_response_ss = '';
                    }
                    $action_update = ' <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                            ' .
                        $li_update .
                        $li_update_ihs .
                        $li_response_ss
                        . '
                            </ul>
                        </div>';

                    return $action_update;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // return $this->practitioner->getAll();
        return view('pages.practitioner.practitioner');
    }


    public function responseSS(Request $request, $id)
    {
        try {
            $endpoint = 'Practitioner?identifier=https://fhir.kemkes.go.id/id';
            $nik = $this->enc('nik|' . $this->dec($id));
            $response_satusehat  = $this->api_response_ss($endpoint, $nik);
            return view('pages.practitioner.practitioner-response-ss', [
                "data_response" => $response_satusehat
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function ubah(Request $request, $id)
    {
        try {
            return view('pages.practitioner.practitioner-ubah', [
                "data_practitioner" => $this->practitioner_repo->getDataPractitionerFind($this->dec($id)),
            ]);
        } catch (Throwable $e) {
            return $e;
        }
    }
    public function update(Request $request)
    {
        try {
            # update
            $this->practitioner_repo->updatePractitioner($request->all(), $this->dec($request->id_ubah));

            return redirect('praktisi')
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }


    public function ubahIHS(Request $request, $id)
    {
        try {
            $id_ihs = 0;
            $color_ihs = 'danger';
            $nama_ihs = 'ID belum tersedia';

            $data_practitioner = $this->practitioner_repo->getDataPractitionerFind($this->dec($id));

            if (!empty($data_practitioner['nik'])) {
                $endpoint = 'Practitioner?identifier=https://fhir.kemkes.go.id/id';
                $nik = $this->enc('nik|' . $data_practitioner['nik']);
                $response_satusehat  = json_decode($this->api_response_ss($endpoint, $nik));


                if ($response_satusehat->total > 0) {
                    $id_ihs = $response_satusehat->entry[0]->resource->id;
                    $nama_ihs = $response_satusehat->entry[0]->resource->name[0]->text;
                    $color_ihs = 'success';
                }
            }

            return view('pages.practitioner.practitioner-ubah-ihs', [
                "data_practitioner" => $data_practitioner,
                "id_ihs" => $id_ihs,
                "nama_ihs" => $nama_ihs,
                'color_ihs' => $color_ihs
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
    public function updateIHS(Request $request)
    {
        try {
            # update
            $this->practitioner_repo->updateIhsPractitioner($request->all(), $this->dec($request->id_ubah));

            return redirect('praktisi')
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}
