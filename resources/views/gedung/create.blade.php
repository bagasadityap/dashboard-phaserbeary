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
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="5" id="deskripsi"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="gambar">Gambar</label>
                        <input type="file" class="form-control" name="gambar[]" id="gambar" accept=".jpg, .jpeg, .png" multiple>
                    </div>
                    <div id="preview-container" class="d-flex flex-wrap gap-2"></div>
                    <div class="mb-3">
                        <label class="form-label" for="gambar_vr">Gambar VR</label>
                        <input type="file" class="form-control" name="gambar_vr" id="gambar_vr" accept=".jpg, .jpeg, .png">
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

<script>
    document.getElementById('gambar').addEventListener('change', function(event) {
        let previewContainer = document.getElementById('preview-container');
        previewContainer.innerHTML = '';

        Array.from(event.target.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let div = document.createElement('div');
                    div.classList.add('position-relative', 'd-inline-block');

                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.width = '100px';
                    img.style.height = '100px';

                    let removeBtn = document.createElement('button');
                    removeBtn.innerHTML = '&times;';
                    removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'position-absolute', 'top-0', 'end-0');
                    removeBtn.style.transform = 'translate(50%, -50%)';
                    removeBtn.onclick = function() {
                        removeImage(index);
                    };

                    div.appendChild(img);
                    div.appendChild(removeBtn);
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });

        function removeImage(index) {
            let fileList = Array.from(event.target.files);
            fileList.splice(index, 1);

            let newFileList = new DataTransfer();
            fileList.forEach(file => newFileList.items.add(file));
            event.target.files = newFileList.files;

            document.getElementById('gambar').dispatchEvent(new Event('change'));
        }
    });
</script>
