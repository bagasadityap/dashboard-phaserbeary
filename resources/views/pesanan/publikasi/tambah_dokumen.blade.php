<form action="{{ route('pesanan.publikasi.store-dokumen', ['id' => $model->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div id="dokumen-container">
        <p class="text-danger">*File dokumen lama tidak perlu diunggah ulang jika tidak ada perubahan.</p>
        @php
            $dokumenOperator = json_decode($model->dokumenOperator, true) ?? [];
        @endphp
        @forelse ((array)$dokumenOperator as $dokumen)
        <div class="row dokumen-field">
            <div class="col-md-5 mb-2">
                <label class="form-label">Nama Dokumen</label>
                <input type="text" class="form-control" name="nama[]" value="{{ $dokumen['nama'] }}">
            </div>
            <div class="col-md-5 mb-2">
                <label class="form-label">File</label>
                <div class="input-group mb-2">
                    <input type="file" name="file[]" class="form-control" value="">
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="hapusOpsi(this)">-</button>
            </div>
        </div>
        @empty
        <div class="row dokumen-field">
            <div class="col-md-5 mb-2">
                <label class="form-label">Nama Dokumen</label>
                <input type="text" class="form-control" name="nama[]" required>
            </div>
            <div class="col-md-5 mb-2">
                <label class="form-label">File</label>
                <div class="input-group mb-2">
                    <input type="file" name="file[]" class="form-control">
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="hapusOpsi(this)">-</button>
            </div>
        </div>
        @endforelse
    </div>

    <button type="button" class="btn btn-secondary" onclick="tambahOpsi()">+ Tambah Dokumen</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<script>
    function tambahOpsi() {
      const container = document.getElementById('dokumen-container');
      const newField = document.createElement('div');
      newField.classList.add('row', 'dokumen-field');
      newField.innerHTML = `
        <div class="col-md-5 mb-2">
          <label class="form-label">Nama Dokumen</label>
          <input type="text" class="form-control" name="nama[]" required>
        </div>
        <div class="col-md-5 mb-2">
          <label class="form-label">File</label>
          <div class="input-group mb-2">
              <input type="file" name="file[]" class="form-control">
          </div>
        </div>
        <div class="col-md-2 d-flex align-items-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="hapusOpsi(this)">-</button>
        </div>
      `;
      container.appendChild(newField);
    }
    function hapusOpsi(button) {
      const row = button.closest('.dokumen-field');
      row.remove();
    }
</script>
