@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Encounter</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Kunjungan Pasien</p>
            </div>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">

            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em
                        class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                    <ul class="nk-block-tools g-3">
                        <li><a href="{{ route('encounter-tambah') }}"
                                class=" btn btn-white btn-dim btn-outline-primary"><em
                                    class="icon ni ni-plus"></em><span>Tambah</span></a>
                        </li>

                    </ul>
                </div><!-- .toggle-expand-content -->
            </div><!-- .toggle-wrap -->
        </div>

    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-block">
    <div class="row g-gs">

        <div class="col-xl-12 col-xxl-12">
            <div class="card card-bordered card-full">
                <div class="row m-2">
                    <div class="col-md-2  mb-3">
                        <div class="form-group">
                            <label class="form-label text-secondary" for="default-06">Status</label>
                            <div class="form-control-wrap">
                                <div class="form-control-select">
                                    <div class="form-icon form-icon-left">
                                        <em class="icon ni ni-plus"></em>
                                    </div>
                                    <select class="form-control form-control-sm" id="class_code">
                                        <option value="AMB">
                                            AMB (Rawat Jalan)
                                        </option>
                                        <option value="IMP">
                                            IMP (Rawat Inap)
                                        </option>
                                        <option value="EMER">
                                            EMER (IGD)
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2  mb-3">
                        <div class="form-group">
                            <label class="form-label text-secondary" for="default-06">Status</label>
                            <div class="form-control-wrap">
                                <div class="form-control-select">
                                    <div class="form-icon form-icon-left">
                                        <em class="icon ni ni-report"></em>
                                    </div>
                                    <select class="form-control form-control-sm" id="status_kirim">
                                        <option value="success">
                                            Terkirim
                                        </option>
                                        <option value="failed">
                                            Gagal Terkirim
                                        </option>
                                        <option value="waiting">
                                            Menunggu
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2  mb-3">
                        <div class="form-group">
                            <label class="form-label">Tanggal Awal</label>
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-left">
                                    <em class="icon ni ni-calendar"></em>
                                </div>
                                <input type="text" class="form-control form-control-sm date-picker"
                                    data-date-format="yyyy-mm-dd" id="tanggal_awal" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="form-group">
                            <label class="form-label">Tanggal Akhir</label>
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-left">
                                    <em class="icon ni ni-calendar"></em>
                                </div>
                                <input type="text" class="form-control form-control-sm date-picker"
                                    data-date-format="yyyy-mm-dd" id="tanggal_akhir" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 align-content-end mb-3">
                        <button id="btn-filter" class="btn btn-sm btn-round btn-secondary text-center"><em
                                class="icon ni ni-search"></em><span></span> Search</button>
                    </div>
                </div>
                @if (session('pesan'))
                <x-alert pesan="{{ session('pesan') }}" warna="{{ session('warna','success') }}" />
                @endif
                <div class="card card-preview">
                    <div class="card-inner">
                        <table class="table table-bordered data-table mt-3">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Satu Sehat ID</th>
                                    <th>Nama Pasien</th>
                                    <th>Nama Praktisi</th>
                                    <th>Poli</th>
                                    <th>Kode Kelas</th>
                                    <th>Kelas</th>
                                    <th>Tanggal</th>
                                    <th>Response</th>
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
    function load_datatable(p_class_code = null,p_status_kirim = null, p_tanggal_awal = null, p_tanggal_akhir=null){

        //mendapatkan status param URL sebagai filter saat kirim data
        const getParams = new URLSearchParams(window.location.search);

        const par_class_code = getParams.get('class_code');
        p_class_code = par_class_code;
        document.getElementById('class_code').value = par_class_code;

        const par_status_kirim = getParams.get('status_kirim');
        p_status_kirim = par_status_kirim;
        document.getElementById('status_kirim').value = par_status_kirim;

        const par_tanggal_awal = getParams.get('tanggal_awal');
        p_tanggal_awal = par_tanggal_awal;
        document.getElementById('tanggal_awal').value = par_tanggal_awal;

        const par_tanggal_akhir = getParams.get('tanggal_akhir');
        p_tanggal_akhir = par_tanggal_akhir;
        document.getElementById('tanggal_akhir').value = par_tanggal_akhir;

      var table = $('.data-table').DataTable({
         responsive: true,
          processing: true,
          stateSave: true,
          serverSide: true,

          language : {
                sLengthMenu: "Show _MENU_"
            },
          ajax: {
            url : "{{ route('encounter') }}",
            data : {
                class_code:  p_class_code,
                status_kirim:  p_status_kirim,
                tanggal_awal:  p_tanggal_awal,
                tanggal_akhir:  p_tanggal_akhir,
            }
          },

          columns: [
            //   {data: 'id', name: 'id'},
              {data: 'original_code', name: 'original_code'},
              {data: 'satusehat_id', name: 'satusehat_id'},
              {data: 'subject_display', name: 'subject_display'},
              {data: 'participant_individual_display', name: 'participant_individual_display'},
              {data: 'location_display', name: 'location_display'},
              {data: 'class_code', name: 'class_code'},
              {data: 'class_room_display', name: 'class_room_display'},
              {data: 'period_start', name: 'period_start'},
              {data: 'satusehat_statuscode', name: 'satusehat_statuscode'},
              {data: 'status', name: 'status', orderable: true, searchable: true},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });

    };

    load_datatable();

    $("#btn-filter").click(function(){

        const class_code = ($("#class_code").val() === '') ? '' : $("#class_code").val();
        const status_kirim = ($("#status_kirim").val() === '') ? '' : $("#status_kirim").val();
        const tanggal_awal = ($("#tanggal_awal").val() === '') ? '' : $("#tanggal_awal").val();
        const tanggal_akhir =($("#tanggal_akhir").val() === '') ? '' : $("#tanggal_akhir").val();

        //menangani filter
        const url = new URL(window.location);
        const params = new URLSearchParams(url.search);
        window.history.replaceState({}, '', `${url.pathname}?class_code=${class_code}&status_kirim=${status_kirim}&tanggal_awal=${tanggal_awal}&tanggal_akhir=${tanggal_akhir}`);

        $('.data-table').DataTable().destroy();
        load_datatable(class_code,status_kirim, tanggal_awal, tanggal_akhir);
    })

    function modalResponseSS(id)
    {
        loadingProcess(); //dari custom.js
        var url     = '{{ route("encounter-response-ss", ":id") }}';
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


    function modalDetail(original_code)
    {
        loadingProcess(); //dari custom.js
        var url     = '{{ route("encounter-detail", ":original_code") }}';
        url         = url.replace(':original_code',original_code);

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

    function modalKirimSS(id)
    {
        loadingProcess(); //dari custom.js

        var url     = '{{ route("encounter-modal-kirim-ss", ":id") }}';
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


    function kirimSatuSehat(id)
    {
        // loadingProcess(); //dari custom.js

        $(".btn-action").html('Proses Kirim....')
        $(".btn-action").prop("disabled", true);
        $(".result-message").html('...');
        var url     = '{{ route("encounter-kirim-ss", ":id") }}';
        url         = url.replace(':id',id);

        $.ajax({
            type:"POST",
            data: {
                id: id,
                _token: "{{ csrf_token() }}",

            },
            url:url,
            success: function(response)
            {//resourceType = OperationOutcome

                result = JSON.parse(response);
                console.log(result)
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

    function modalUpdateSS(id)
    {
        loadingProcess(); //dari custom.js

        var url     = '{{ route("encounter-modal-update-ss", ":id") }}';
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


    function updateSatuSehat(id)
    {
        // loadingProcess(); //dari custom.js

        $(".btn-action").html('Proses Update....')
        $(".result-message").html('...');
        var url     = '{{ route("encounter-update-ss", ":id") }}';
        url         = url.replace(':id',id);

        $.ajax({
            type:"POST",
            data: {
                id: id,
                _token: "{{ csrf_token() }}",

            },
            url:url,
            success: function(response)
            {//resourceType = OperationOutcome
// console.log(response)
                result = JSON.parse(response);
                // console.log(result)
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