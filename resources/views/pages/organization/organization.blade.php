@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Organisasi</h3>
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
                        <li><a href="#file-upload" onClick="modalStruktur()" data-toggle="modal"
                                class="btn btn-white btn-dim btn-outline-primary"><em
                                    class="icon ni ni-eye"></em><span>struktur</span></a>
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
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Satu Sehat ID</th>
                                    <th>Nama</th>
                                    <th>Bagian</th>
                                    <th>Status</th>
                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_organisasi as $item_organisasi)
                                <tr>
                                    <td>{{ $item_organisasi->original_code }}</td>
                                    <td>{{ $item_organisasi->satusehat_id }}</td>
                                    <td>{{ $item_organisasi->name }}</td>
                                    <td>{{ $item_organisasi->r_partof->name ?? '' }}</td>
                                    <td
                                        class="{{ optional($item_organisasi->r_status)->status != 1 ? 'text-warning' : 'text-success' }}">

                                        {{ $item_organisasi->r_status->description ?? '' }}</td>
                                    <td>
                                        <div class="drodown">
                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    @if($item_organisasi->satusehat_send != 1)
                                                    <li><a onClick="return modalUbah('{{ Crypt::encrypt($item_organisasi->id) }}')"
                                                            href="#file-upload" data-toggle="modal"><em
                                                                class="icon ni ni-edit"></em><span>Ubah</span></a></li>
                                                    <li><a onClick="return modalHapus('{{ Crypt::encrypt($item_organisasi->id) }}')"
                                                            href="#file-upload" data-toggle="modal"><em
                                                                class="icon ni ni-trash"></em><span>Hapus</span></a>
                                                    </li>
                                                    <li><a onClick="return modalKirimSatuSehat('{{ Crypt::encrypt($item_organisasi->id) }}')"
                                                            href="#file-upload" data-toggle="modal"><em
                                                                class="icon ni ni-send"></em><span>Kirim ke Satu
                                                                Sehat</span></a>
                                                    </li>
                                                    @endif
                                                    @if($item_organisasi->satusehat_send == 1)
                                                    <li><a onClick="return modalResponseSS('{{ Crypt::encrypt($item_organisasi->satusehat_id) }}')"
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
    <div class="modal-dialog modal-xl  modal-dialog-top" role="document">
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
    var table =  $('.data-table').DataTable({
         stateSave: true,
          language : {
                sLengthMenu: "Show _MENU_"
            },
    });

    function modalStruktur()
    {
        loadingProcess(); //dari custom.js
        $.ajax({
            type:"GET",
            url:"{{ route('organisasi-struktur') }}",
            success: function(response)
            {
                $("#content-modal").html("");
                $("#content-modal").html(response);
            }
        })
    }

    function modalTambah()
    {
        loadingProcess(); //dari custom.js
        $.ajax({
            type:"GET",
            url:"{{ route('organisasi-tambah') }}",
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
        var url     = '{{ route("organisasi-hapus", ":id") }}';
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
        var url     = '{{ route("organisasi-ubah", ":id") }}';
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

        var url     = '{{ route("organisasi-response-ss", ":id") }}';
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

        var url     = '{{ route("organisasi-modal-kirim-ss", ":id") }}';
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
        var url     = '{{ route("organisasi-kirim-ss", ":id") }}';
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