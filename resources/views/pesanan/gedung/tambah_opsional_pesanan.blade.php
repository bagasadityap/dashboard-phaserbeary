<form action="{{ route('pesanan.gedung.store-optional', ['id' => $model->id]) }}" method="POST">
    @csrf
    <div id="opsi-container">
        @forelse ($models as $opsi)
        <div class="row opsi-field">
            <div class="col-md-5 mb-2">
                <label class="form-label">Nama Barang atau Layanan</label>
                <input type="text" class="form-control" name="nama[]" value="{{ $opsi->nama }}" required>
            </div>
            <div class="col-md-5 mb-2">
                <label class="form-label">Harga</label>
                <div class="input-group mb-2">
                    <span class="input-group-text">Rp.</span>
                    <input type="number" name="harga[]" class="form-control" value="{{ $opsi->harga }}" required>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="hapusOpsi(this)">-</button>
            </div>
        </div>
        @empty
        <div class="row opsi-field">
            <div class="col-md-5 mb-2">
                <label class="form-label">Nama Barang atau Layanan</label>
                <input type="text" class="form-control" name="nama[]" required>
            </div>
            <div class="col-md-5 mb-2">
                <label class="form-label">Harga</label>
                <div class="input-group mb-2">
                    <span class="input-group-text">Rp.</span>
                    <input type="number" name="harga[]" class="form-control" required>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="hapusOpsi(this)">-</button>
            </div>
        </div>
        @endforelse
    </div>

    <button type="button" class="btn btn-secondary" onclick="tambahOpsi()">+ Tambah Opsi</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<script>
  function tambahOpsi() {
    const container = document.getElementById('opsi-container');

    const newField = document.createElement('div');
    newField.classList.add('row', 'opsi-field');
    newField.innerHTML = `
      <div class="col-md-5 mb-2">
        <label class="form-label">Nama Barang atau Layanan</label>
        <input type="text" class="form-control" name="nama[]" required>
      </div>
      <div class="col-md-5 mb-2">
        <label class="form-label">Harga</label>
        <div class="input-group mb-2">
            <span class="input-group-text">Rp.</span>
            <input type="number" name="harga[]" class="form-control" required>
        </div>
      </div>
      <div class="col-md-2 d-flex align-items-center">
          <button type="button" class="btn btn-danger btn-sm" onclick="hapusOpsi(this)">-</button>
      </div>
    `;

    container.appendChild(newField);
  }

  function hapusOpsi(button) {
    const row = button.closest('.opsi-field');
    row.remove();
  }
</script>
