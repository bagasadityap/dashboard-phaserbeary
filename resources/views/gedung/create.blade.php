<form id="form-validation-2" class="form" action="{{ route('gedung.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-0">
                    <div class="mb-2">
                        <label for="nama" class="form-label">Nama</label>
                        <input class="form-control" type="text" id="nama" placeholder="Masukkan Nama" name="nama" required>
                    </div>
                    <div class="mb-2">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input class="form-control" type="text" id="lokasi" placeholder="Masukkan Lokasi" name="lokasi" required>
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-4">
                            <label for="kapasitas" class="form-label">Kapasitas</label>
                            <input class="form-control" type="number" id="kapasitas" placeholder="Masukkan Kapasitas" name="kapasitas" required>
                        </div>
                        <div class="mb-2 col-md-8">
                            <label for="harga" class="form-label">Harga</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Rp.</span>
                                <input type="number" name="harga" class="form-control" required>
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="gambar">Gambar</label>
                        <input type="file" class="form-control" name="gambar" id="gambar">
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="gambar_vr">Gambar VR</label>
                        <input type="file" class="form-control" name="gambar_vr" id="gambar_vr">
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
