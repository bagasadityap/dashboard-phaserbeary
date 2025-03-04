<form id="form-validation-2" class="form" action="{{ route('configuration.role.update', ['id' => $role->id]) }}" method="POST">
    @csrf
    <div class="row" style="margin-bottom: -2rem;">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-0" id="card-body">
                    <div class="mb-2">
                        <input type="text" id="id" name="id" value="{{ $role->id }}" hidden>
                        <label for="name" class="form-label">Nama</label>
                        <input class="form-control" type="text" id="name" placeholder="Masukkan Nama" name="name" value="{{ $role->name }}" required>
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
