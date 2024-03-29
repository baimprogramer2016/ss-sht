<div class="nk-upload-form">
    <h5 class="title mb-3">Ubah Parameter</h5>
</div>
<div class="nk-upload-list">
    <form action="{{ route('parameter-update') }}" method="POST" id="form-action">
        @csrf
        <div class="row g">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="corporate">Corporate</label>
                    <div class="form-control-wrap">
                        <input name="corporate" type="text" value="{{ $data_parameter->corporate }}"
                            class="form-control form-control-sm" id="corporate" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="client_id">Client ID</label>
                    <div class="form-control-wrap">
                        <input name="client_id" type="text" value="{{ $data_parameter->client_id }}"
                            class="form-control form-control-sm" id="client_id" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="client_secret">Secret ID</label>
                    <div class="form-control-wrap">
                        <input name="client_secret" type="text" value="{{ $data_parameter->client_secret }}"
                            class="form-control form-control-sm" id="client_secret" required>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="auth_url">Auth URL</label>
                    <div class="form-control-wrap">
                        <input name="auth_url" type="text" value="{{ $data_parameter->auth_url }}"
                            class="form-control form-control-sm" id="auth_url" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="base_url">Base URL</label>
                    <div class="form-control-wrap">
                        <input name="base_url" type="text" value="{{ $data_parameter->base_url }}"
                            class="form-control form-control-sm" id="base_url" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="consent_url">Consent URL</label>
                    <div class="form-control-wrap">
                        <input name="consent_url" type="text" value="{{ $data_parameter->consent_url }}"
                            class="form-control form-control-sm" id="consent_url" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="generate_token_url">Hit URL</label>
                    <div class="form-control-wrap">
                        <input name="generate_token_url" type="text" value="{{ $data_parameter->generate_token_url }}"
                            class="form-control form-control-sm" id="generate_token_url" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="organization_id">Organization ID</label>
                    <div class="form-control-wrap">
                        <input name="organization_id" type="text" value="{{ $data_parameter->organization_id }}"
                            class="form-control form-control-sm" id="organization_id" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="access_token">Token</label>
                    <div class="form-control-wrap">
                        <input name="access_token" type="text" value="{{ $data_parameter->access_token }}"
                            class="form-control form-control-sm" id="access_token" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="expiry_token">Expiry Token</label>
                    <div class="form-control-wrap">
                        <input name="expiry_token" readonly type="text" value="{{ $data_parameter->expiry_token }}"
                            class="form-control form-control-sm" id="expiry_token" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="username">Username (Hit)</label>
                    <div class="form-control-wrap">
                        <input name="username" type="text" value="{{ $data_parameter->username }}"
                            class="form-control form-control-sm" id="username" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="pass">Password (Hit)</label>
                    <div class="form-control-wrap">
                        {{-- <a href="#" class="form-icon form-icon-right passcode-switch" data-target="pass">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a> --}}
                        <input name="pass" type="password" value="{{ $data_parameter->pass }}"
                            class="form-control form-control-sm" id="pass" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="farmasi_id">Org Farmasi ID</label>
                    <div class="form-control-wrap">
                        <input name="farmasi_id" type="text" value="{{ $data_parameter->farmasi_id }}"
                            class="form-control form-control-sm" id="farmasi_id" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="location_farmasi_id">Lokasi Farmasi ID</label>
                    <div class="form-control-wrap">
                        <input name="location_farmasi_id" type="text" value="{{ $data_parameter->location_farmasi_id }}"
                            class="form-control form-control-sm" id="location_farmasi_id" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="laboratory_id">Org Laboratory ID</label>
                    <div class="form-control-wrap">
                        <input name="laboratory_id" type="text" value="{{ $data_parameter->laboratory_id }}"
                            class="form-control form-control-sm" id="laboratory_id" required>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-primary btn-sm btn-action">Ubah</button>
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
    $('.btn-action').on("click", function(e){
        e.preventDefault();
        Swal.fire({
                    title: 'Konfirmasi',
                    text: "Anda akan mengubah Data?",
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