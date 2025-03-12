<form id="form-validation-2" class="form" action="{{ route('home.store-dokumen', ['id' => $model->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-0">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="mb-3">
                        <label class="form-label" for="surat_permohonan_acara">Surat Permohonan Acara</label>
                        <input type="file" class="form-control" name="surat_permohonan_acara" id="surat_permohonan_acara" accept=".pdf">
                    </div>
                    @if ($type == 'publikasi')
                        <div class="mb-3">
                            <label class="form-label" for="poster_acara">Poster Acara</label>
                            <input type="file" class="form-control" name="poster_acara" id="poster_acara" accept="">
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label" for="bukti_pembayaran">Bukti Pembayaran</label>
                        <input type="file" class="form-control" name="bukti_pembayaran" id="bukti_pembayaran" accept=".jpg, .jpeg, .png, .heic">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="dokumen_opsional">Dokumen (Opsional)</label>
                        <input type="file" class="form-control" name="dokumen_opsional" id="dokumen_opsional" accept=".pdf">
                    </div>
                    @if ($type == 'gedung')
                        <div class="mb-3">
                            <label class="form-label" for="data_partisipan">Data Partisipan</label>
                            <input type="file" class="form-control" name="data_partisipan" id="data_partisipan" accept=".xls, .xlsx">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
    </div>
</form>
