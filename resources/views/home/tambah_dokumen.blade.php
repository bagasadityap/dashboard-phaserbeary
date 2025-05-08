<form id="form-validation-2" class="form" action="{{ route('home.store-dokumen', ['id' => $model->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-0">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="mb-3">
                        <label class="form-label" for="suratPermohonanAcara">Surat Permohonan Acara</label>
                        <input type="file" class="form-control" name="suratPermohonanAcara" id="suratPermohonanAcara" accept=".pdf">
                    </div>
                    @if ($type == 'publikasi')
                        <div class="mb-3">
                            <label class="form-label" for="posterAcara">Poster Acara</label>
                            <input type="file" class="form-control" name="posterAcara" id="posterAcara" accept="">
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label" for="buktiPembayaran">Bukti Pembayaran</label>
                        <input type="file" class="form-control" name="buktiPembayaran" id="buktiPembayaran" accept=".jpg, .jpeg, .png, .heic">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="dokumenOpsional">Dokumen (Opsional)</label>
                        <input type="file" class="form-control" name="dokumenOpsional" id="dokumenOpsional" accept=".pdf">
                    </div>
                    @if ($type == 'gedung')
                        <div class="mb-3">
                            <label class="form-label" for="dataPartisipan">Data Partisipan</label>
                            <input type="file" class="form-control" name="dataPartisipan" id="dataPartisipan" accept=".xls, .xlsx">
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
