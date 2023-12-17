<div class="nk-upload-form">
    <h5 class="title mb-3">Hapus Organisasi</h5>
</div>
<div class="nk-upload-list">
    <form action="{{ route('organisasi-hapus-data') }}" method="POST" id="form-action">
        @csrf
        <div class="row g">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="hidden" name="id_hapus" value="{{ Crypt::encrypt($data_organization->id) }}">
                    <label class="form-label" for="original_code">Nama</label>
                    <div class="form-control-wrap">
                        <input name="name" readonly value="{{ $data_organization->name }}" type="text"
                            class="form-control form-control-sm" id="name" required>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-danger btn-sm btn-action">Hapus</button>
                </div>
            </div>
        </div>
    </form>
</div>
{{-- <div class="nk-modal-action justify-end">
    <ul class="btn-toolbar g-4 align-center">
        <li><button data-dismiss="modal" class="link link-primary">Cancel</button></li>
        <li><button class="btn btn-primary">Add Files</button></li>
    </ul>
</div> --}}

<script>
    $(document).ready(function() {

        $('.btn-action').on("click", function(e) {
            e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Anda akan Menghapus Data?",
                    showCancelButton: true,
                    confirmButtonColor: "#2c3782",
                    confirmButtonText: 'Lanjut',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        $('form#form-action').submit();
                    }
                });

        });
    });
</script>