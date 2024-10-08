@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Lokasi</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Pengaturan atau Master Data</p>
            </div>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">

            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em
                        class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                    <ul class="nk-block-tools g-3">
                        <li><a href="#file-upload" onClick="modalTambah()"
                                class="btn btn-white btn-dim btn-outline-primary" data-toggle="modal"><em
                                    class="icon ni ni-plus"></em><span>Tambah</span></a>
                        </li>
                        <li><a href="#file-upload" onClick="modalKirimMultipleSatuSehat()"
                                class="btn btn-white btn-dim btn-outline-success" data-toggle="modal"><em
                                    class="icon ni ni-send"></em><span>Kirim ke Satu Sehat (Multiple)</span></a>
                        </li>

                    </ul>
                </div><!-- .toggle-expand-content -->
            </div><!-- .toggle-wrap -->
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
                        <table class="data-table table table-bordered">
                            <thead>
                                <tr>
                                    <th>Checked</th>
                                    <th>Kode</th>
                                    <th>Satu Sehat ID</th>
                                    <th>Nama</th>
                                    <th>Tipe</th>
                                    <th>Organisasi</th>
                                    <th>Part Of Code</th>
                                    <th>Part</th>
                                    <th>Status</th>
                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_lokasi as $index => $item_lokasi)
                                <tr>
                                    <td>
                                        <div class="custom-control custom-checkbox flex justify-center items-center">
                                            <input type="checkbox" class="custom-control-input"
                                                onClick="clickCheckBoxSS(event,{{ $item_lokasi->id }})"
                                                id="pilih_{{ $index }}" />
                                            <label class="custom-control-label" for="pilih_{{ $index }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $item_lokasi->original_code }}</td>
                                    <td>{{ $item_lokasi->satusehat_id }}</td>
                                    <td>{{ $item_lokasi->name }}</td>
                                    <td>{{ $item_lokasi->physical_type_display }}</td>
                                    <td>{{ $item_lokasi->r_managing_organization->name ?? '' }}</td>
                                    <td>{{ $item_lokasi->partof_code}}</td>
                                    <td>{{ $item_lokasi->r_partof_name->name ?? ''}}</td>
                                    <td
                                        class="{{ (!empty($item_lokasi->r_status->status) ?? 1  == 1) ? 'text-success' : 'text-warning'  }}">
                                        {{ $item_lokasi->r_status->description ?? '' }}</td>
                                    <td>
                                        <div class="drodown">
                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    @if($item_lokasi->satusehat_send != 1)
                                                    <li><a onClick="return modalUbah('{{ Crypt::encrypt($item_lokasi->id) }}')"
                                                            href="#file-upload" data-toggle="modal"><em
                                                                class="icon ni ni-edit"></em><span>Ubah</span></a></li>
                                                    <li><a onClick="return modalHapus('{{ Crypt::encrypt($item_lokasi->id) }}')"
                                                            href="#file-upload" data-toggle="modal"><em
                                                                class="icon ni ni-trash"></em><span>Hapus</span></a>
                                                    </li>
                                                    <li><a onClick="return modalKirimSatuSehat('{{ Crypt::encrypt($item_lokasi->id) }}')"
                                                            href="#file-upload" data-toggle="modal"><em
                                                                class="icon ni ni-send"></em><span>Kirim ke Satu
                                                                Sehat</span></a>
                                                    </li>
                                                    @endif
                                                    @if($item_lokasi->satusehat_send == 1)
                                                    <li><a onClick="return modalResponseSS('{{ Crypt::encrypt($item_lokasi->satusehat_id) }}')"
                                                            href="#file-upload" data-toggle="modal"><em
                                                                class="icon ni ni-eye"></em><span>Response Satu
                                                                Sehat</span></a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->
            </div><!-- .card -->
        </div><!-- .col -->
    </div>

</div>
<div class="modal fade" tabindex="-1" role="dialog" id="file-upload">
    <div class="modal-dialog modal-lg modal-dialog-top" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-md" id="content-modal">
                {{-- Content Here --}}
                <div class='d-flex justify-content-center' id='loading-process'>
                    <div class='spinner-border text-primary' role='status'><span class='sr-only'>Loading...</span></div>
                </div>

                {{-- End Content --}}
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
@endsection

@push('script')

<script>
    $('.data-table').DataTable({
        stateSave: true,
          language : {
                sLengthMenu: "Show _MENU_"
            },
        });



    var tampung_id = [];


    function clickCheckBoxSS(event,param){

        if(event.target.checked == true){
            tampung_id.push(param)
        }else{
            tampung_id = tampung_id.filter(item => item !== param)

        }
    }


    function modalTambah()
    {
        loadingProcess(); //dari custom.js
        $.ajax({
            type:"GET",
            url:"{{ route('lokasi-tambah') }}",
            success: function(response)
            {
                $("#content-modal").html("");
                $("#content-modal").html(response);
            }
        })
    }
    function modalHapus(id)
    {

        loadingProcess(); //dari custom.js
        var url     = '{{ route("lokasi-hapus", ":id") }}';
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
    function modalUbah(id)
    {
        loadingProcess(); //dari custom.js
        var url     = '{{ route("lokasi-ubah", ":id") }}';
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
    function modalResponseSS(id)
    {
        loadingProcess(); //dari custom.js
        var url     = '{{ route("lokasi-response-ss", ":id") }}';
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


    function modalKirimSatuSehat(id)
    {
        loadingProcess(); //dari custom.js

        var url     = '{{ route("lokasi-modal-kirim-ss", ":id") }}';
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
        var url     = '{{ route("lokasi-kirim-ss", ":id") }}';
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
                console.log(result.resourceType)
                if(result.resourceType === 'OperationOutcome')
                {
                    $(".result-message").html("<i class='text-danger'>Gagal di kirim</i>");
                    $(".btn-action").hide();
                }else
                {
                    $(".result-message").html("<i class='text-success'>Berhasil di kirim</i>");
                    location.reload();
                    $(".btn-action").html('Selesai');
                }


                // $("#response_ss").val(JSON.stringify(response));
                $("#response_ss").val(response);

            }
        })
    }
    function modalKirimMultipleSatuSehat()
    {
        loadingProcess(); //dari custom.js

        var url     = '{{ route("lokasi-modal-kirim-multiple-ss") }}';
        $.ajax({
            type:"GET",
            url:url,
            data: {
                data:JSON.stringify(tampung_id),
            },
            success: function(response)
            {
                $("#content-modal").html("");
                $("#content-modal").html(response);
            }
        })
    }
    function kirimMultipleSatuSehat()
    {
        // loadingProcess(); //dari custom.js

        $(".btn-action").html('Proses Kirim....')
        $(".btn-action").prop("disabled", true);
        $(".result-message").html('...');
        var url     = '{{ route("lokasi-multiple-kirim-ss") }}';

        $.ajax({
            type:"POST",
            data: {
                _token: "{{ csrf_token() }}",
                data:JSON.stringify(tampung_id),
            },
            url:url,
            success: function(response)
            {//resourceType = OperationOutcome

                    console.log(response)

                    if(response.resourceType === 'OperationOutcome')
                    {
                        $(".result-message").html("<i class='text-danger'>Gagal di kirim</i>");
                        $(".btn-action").hide();
                    }else
                    {
                        $("#response_ss").val(response.data);
                        setTimeout(() => {
                            $(".result-message").html("<i class='text-success'>Berhasil di kirim</i>");
                            $(".btn-action").html('Selesai');
                        }, 2000);
                            location.reload();
                    }

            }
        })
    }
</script>
@endpush