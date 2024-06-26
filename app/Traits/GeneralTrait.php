<?php

namespace App\Traits;

use App\Models\Parameter;
use App\Models\Organization;
use App\Repositories\Parameter\ParameterInterface;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Ramsey\Uuid\Uuid;


use Illuminate\Support\Facades\Http;

trait GeneralTrait
{
    public $result;

    public function autoSeq($param)
    {

        if ($param == 'ORG') {
            $number = Organization::orderBy('id', 'DESC')->first();
            $this->result = ($number == null) ? 'OR-1' : 'OR-' . preg_replace("/[^0-9]/", "", $number->original_code) + 1;
        }

        return $this->result;
    }

    function highlight($text = '', $word = '')
    {
        if (strlen($text) > 0 && strlen($word) > 0) {
            return (str_ireplace($word, "<span class='text-success fw-bold'>{$word}</span>", $text));
        }
        return ($text);
    }

    function colorStatus($param)
    {
        switch ($param) {
            case 1:
                return 'success';
            case  0:
                return 'warning';
            case 2:
                return 'danger';
            case 3:
                return 'danger';
            default:
                return 'danger';
        }
    }
    function descPatient($param)
    {
        switch ($param) {
            case 1:
                return 'Updated';
            case  0:
                return 'Waiting';
            case 2:
                return 'No Completed';
            case 3:
                return 'ID IHS tidak ditemukan';
            case 4:
                return 'Review';
            default:
                return 'No Completed';
        }
    }

    function statusKirim()
    {
        $data = [
            ["kode" => "all", "desc" => "semua"],
            ["kode" => "200", "desc" => "Terkirim ke Satu sehat"],
            ["kode" => "500", "desc" => "Gagal Kirim"],
            ["kode" => "no", "desc" => "Review"]
        ];

        return $data;
    }

    function enc($param)
    {
        return Crypt::encrypt($param);
    }
    function dec($param)
    {
        return Crypt::decrypt($param);
    }

    # mendapatkan nilai numerator dan denominator
    public function split_nominator($param)
    {

        $change_param = $param;
        if (strpos($param, '/mg')) {
            $change_param = str_replace('/mg', 'mg', $param);
        }
        if (strpos($change_param, '/ml')) {
            $change_param = str_replace('/ml', 'ml', $change_param);
        }

        if (strpos($change_param, '/')) {
            $split_kza = explode('/', $change_param);
            $result = [
                'numerator' => explode(' ', $split_kza[0])[0],
                'numerator_satuan' => explode(' ', $split_kza[0])[1],
                'denominator' => explode(' ', $split_kza[1])[0],
                'denominator_satuan' => explode(' ', $split_kza[1])[1],
            ];
        } else {
            $result = [
                'numerator' => explode(' ', $change_param)[0],
                'numerator_satuan' => explode(' ', $change_param)[1],
                'denominator' => 0,
                'denominator_satuan' => '-',
            ];
        }

        # penyesuaian default 0
        $result['denominator_penyesuaian'] = 1;

        if ($result['denominator'] <> 0) {
            $result['denominator_penyesuaian'] = $result['denominator'];
        }

        return $result;
    }

    function createPercent($value1, $value2)
    {
        if ($value1 === null || $value2 === null || $value1 === 0 || $value2 === 0) {
            return 0;
        } else {
            return ($value1 / $value2) * 100;
        }
    }

    function formatDate($tanggal, $jam, $menit)
    {
        return  date('Y-m-d H:i:s', strtotime($tanggal . ' ' . $jam . ':' . $menit));
    }
    function formatDate2($tanggal, $waktu)
    {
        return  date('Y-m-d H:i:s', strtotime($tanggal . ' ' . $waktu));
    }
    function formatDateReverse($tanggal)
    {
        return  date('Y-m-d H:i:s', strtotime($tanggal));
    }



    function jenisRawat()
    {
        $jenis_rawat = [
            [
                "kode" => "AMB",
                "keterangan" => "ambulatory",
                "keterangan2" => "Rawat Jalan",
            ],
        ];

        return  $jenis_rawat;
    }
    function cariJenisRawat($kode)
    {
        $jenis_rawat = [
            [
                "kode" => "AMB",
                "keterangan" => "ambulatory",
                "keterangan2" => "Rawat Jalan",
            ],
        ];

        return  collect($jenis_rawat)->where('kode', $kode)->first();
    }

    function getUUID()
    {
        $uuid4 = Uuid::uuid4();
        return $uuid4->toString();
    }

    function typeObservation()
    {
        $type_observation = [
            [
                "type_observation" => "suhu",
                "code_observation" => "8310-5",
                "code_display" => "Body temperature",
                "quantity_unit" => "C",
                "quantity_code" => "Cel",
            ],
            [
                "type_observation" => "diastole",
                "code_observation" => "8462-4",
                "code_display" => "Diastolic blood pressure",
                "quantity_unit" => "mm[Hg]",
                "quantity_code" => "mm[Hg]",
            ],
            [
                "type_observation" => "sistol",
                "code_observation" => "8480-6",
                "code_display" => "Systolic blood pressure",
                "quantity_unit" => "mm[Hg]",
                "quantity_code" => "mm[Hg]",
            ],
            [
                "type_observation" => "nadi",
                "code_observation" => "8867-4",
                "code_display" => "Heart rate",
                "quantity_unit" => "beats/minute",
                "quantity_code" => "/min",
            ],
            [
                "type_observation" => "pernapasan",
                "code_observation" => "9279-1",
                "code_display" => "Respiratory rate",
                "quantity_unit" => "breaths/minute",
                "quantity_code" => "/min",
            ],
        ];

        return $type_observation;
    }
}
