@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Observation (Lab & Radiology)</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Kunjungan Pasien</p>
            </div>
        </div><!-- .nk-block-head-content -->

    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-block">
    <div class="row g-gs">

        <div class="col-xl-12 col-xxl-12">

            <div class="card card-bordered card-full">
                @if (session('pesan'))
                <x-alert pesan="{{ session('pesan') }}" warna="{{ session('warna','success') }}" />
                @endif
                <div class="card card-preview">
                    <div class="card-inner">
                        <table class="table table-bordered data-table mt-3">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>ID Pasien</th>
                                    <th>Tipe</th>
                                    <th>Nama Pemeriksaan</th>
                                    <th>Nilai</th>
                                    <th>Satuan</th>
                                    <th>Kode Tindakan</th>
                                    <th>Satu Sehat ID</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->
            </div><!-- .card -->
        </div><!-- .col -->
    </div>

</div>
<div class="modal fade" tabindex="-1" role="dialog" id="file-upload">
    <div class="modal-dialog modal-xl  modal-dialog-top" role="document">
        <div class="modal-content ">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-md" id="content-modal">
                {{-- Content Here --}}
                <div class='d-flex justify-content-center ' id='loading-process'>
                    <div class='spinner-border text-primary' role='status'><span class='sr-only'>Loading...</span></div>
                </div>
                {{-- End Content --}}
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
@endsection
@push('script')

<script type="text/javascript">
    $(function () {

      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          stateSave: true,
          language : {
                sLengthMenu: "Show _MENU_"
            },
          ajax: "{{ route('observation-lab') }}",
          columns: [

            //   {data: 'id', name: 'id'},
              {data: 'encounter_original_code', name: 'encounter_original_code'},
              {data: 'subject_reference', name: 'subject_reference'},
              {data: 'type_observation', name: 'type_observation'},
              {data: 'code_display', name: 'code_display'},
              {data: 'quantity_value', name: 'quantity_value'},
              {data: 'quantity_unit', name: 'quantity_unit'},
              {data: 'quantity_code', name: 'quantity_code'},
              {data: 'satusehat_id', name: 'satusehat_id'},
              {data: 'effective_datetime', name: 'effective_datetime'},
              {data: 'status', name: 'status', orderable: false, searchable: false},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });

    });



    function modalResponseSS(id)
    {
        loadingProcess(); //dari custom.js
        var url     = '{{ route("observation-lab-response-ss", ":id") }}';
        url         = url.replace(':id',id);
        $.ajax({
            type:"GET",
            url:url,
            success: function(response)
            {
                $("#content-modal").html("");
                $("#content-modal").html(response);
            }
        })
    }

    function modalKirimSS(uuid)
    {
        loadingProcess(); //dari custom.js

        var url     = '{{ route("observation-lab-modal-kirim-ss", ":uuid") }}';
        url         = url.replace(':uuid',uuid);
        $.ajax({
            type:"GET",
            url:url,
            success: function(response)
            {
                $("#content-modal").html("");
                $("#content-modal").html(response);
            }
        })
    }


    function kirimSatuSehat(uuid)
    {
        // loadingProcess(); //dari custom.js

        $(".btn-action").html('Proses Kirim....')
        $(".btn-action").prop("disabled", true);
        $(".result-message").html('...');
        var url     = '{{ route("observation-lab-kirim-ss", ":uuid") }}';
        url         = url.replace(':uuid',uuid);

        $.ajax({
            type:"POST",
            data: {
                uuid: uuid,
                _token: "{{ csrf_token() }}",

            },
            url:url,
            success: function(response)
            {//resourceType = OperationOutcome
                console.log(response)

                result = JSON.parse(response);
                // console.log(result.resourceType)
                if(result.resourceType == 'OperationOutcome')
                {
                    $(".result-message").html("<i class='text-danger'>Gagal di kirim</i>");
                    $(".btn-action").hide();
                }else
                {
                    $(".result-message").html("<i class='text-success'>Berhasil di kirim</i>");
                    location.reload();
                    $(".btn-action").html('Selesai');
                }

                $("#response_ss").val(response);

            }
        })
    }

</script>

@endpush