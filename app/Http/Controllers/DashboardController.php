<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Models\MedicationDispense;
use App\Repositories\CategoryRequest\CategoryRequestInterface;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\Encounter\EncounterInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\GeneralTrait;
use Throwable;

class DashboardController extends Controller
{

    use GeneralTrait;
    private $data_dashboard_repo;
    public function __construct(
        DashboardInterface $dashboardInterface,
    ) {
        $this->data_dashboard_repo = $dashboardInterface;
    }

    public function index()
    {
        try {
            $data_result = [
                "data_current_year" => $this->data_dashboard_repo->getCurrentYear(),
                "data_current_year_success" => $this->data_dashboard_repo->getCurrentYearSucces(),
                "data_current_year_failed" => $this->data_dashboard_repo->getCurrentYearFailed(),
                "data_current_year_waiting" => $this->data_dashboard_repo->getCurrentYearWaiting(),
                "data_year_all" => $this->data_dashboard_repo->getYearAll(),
                "data_year_success_all" => $this->data_dashboard_repo->getYearSuccesAll(),
                "data_year_failed_all" => $this->data_dashboard_repo->getYearFailedAll(),
                "data_year_waiting_all" => $this->data_dashboard_repo->getYearWaitingAll()
            ];

            $data_progress = [
                "encounter_persen" => $this->createPercent($this->data_dashboard_repo->getYearSuccesAll(), $this->data_dashboard_repo->getYearAll()),
                "condition_persen" => $this->createPercent($this->data_dashboard_repo->getConditionSuccessAll(), $this->data_dashboard_repo->getConditionAll()),
                "observation_persen" => $this->createPercent($this->data_dashboard_repo->getObservationSuccessAll(), $this->data_dashboard_repo->getObservationAll()),
                "medication_request_persen" => $this->createPercent($this->data_dashboard_repo->getMedicationRequestSuccessAll(), $this->data_dashboard_repo->getMedicationRequestAll()),
                "medication_dispense_persen" => $this->createPercent($this->data_dashboard_repo->getMedicationDispenseSuccessAll(), $this->data_dashboard_repo->getMedicationDispenseAll()),
                "observation_lab_persen" => $this->createPercent($this->data_dashboard_repo->getObservationLabSuccessAll(), $this->data_dashboard_repo->getObservationLabAll()),

            ];
            return view('pages.dashboard.dashboard', [
                "data_dashboard" => $data_result,
                "data_progress" => $data_progress
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function laporan()
    {
        return view("pages.dashboard.dashboard-laporan");
    }

    public function laporanDownload(Request $request)
    {
        // return date_create_from_format('m/d/Y', $request->tanggal_awal)->format('Y-m-d');
        try {
            $data_export = new LaporanExport(
                $request->all(),
                $this->data_dashboard_repo
            );
            return ($data_export)->download('download_' . $request->resource . '.xlsx');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function runService()
    {
        // Artisan::call('schedule:work');
        Artisan::call('queue:work');

        return response()->json(['message' => 'Schedule Worker started']);
    }
}
