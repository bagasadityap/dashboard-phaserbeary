<form id="form-validation-2" class="form" action="{{ route('configuration.role.store') }}" method="POST">
    @csrf
    <div class="row" style="margin-bottom: -2rem">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-0">
                    <div class="mb-2">
                        <label for="name" class="form-label">Nama</label>
                        <input class="form-control" type="text" id="name" placeholder="Masukkan Nama" name="name" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
    </div>
</form>
