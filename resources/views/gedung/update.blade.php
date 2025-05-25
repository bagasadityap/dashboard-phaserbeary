<form id="form-validation-2" class="form" action="{{ route('gedung.update', ['id' => $model->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-0" id="card-body">
                    <div class="mb-2">
                        <label for="nama" class="form-label">Nama</label>
                        <input class="form-control" type="text" id="nama" placeholder="Masukkan Nama" name="nama" value="{{ $model->nama }}" required>
                    </div>
                    <div class="mb-2">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input class="form-control" type="text" id="lokasi" placeholder="Masukkan Lokasi" name="lokasi" value="{{ $model->lokasi }}" required>
                    </div>
                    <div class="row">
                        <div class="mb-2 col-md-4">
                            <label for="kapasitas" class="form-label">Kapasitas</label>
                            <input class="form-control" type="number" id="kapasitas" placeholder="Masukkan Kapasitas" name="kapasitas" value="{{ $model->kapasitas }}" required>
                        </div>
                        <div class="mb-2 col-md-8">
                            <label for="harga" class="form-label">Harga</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Rp.</span>
                                <input type="number" name="harga" class="form-control" value="{{ $model->harga }}">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="5" id="deskripsi">{{ $model->deskripsi }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="gambar">Gambar</label>
                        <input type="file" class="form-control" name="gambar[]" id="gambar" accept=".jpg, .jpeg, .png" multiple>
                    </div>
                    <div id="old-images-container" class="d-flex flex-wrap gap-2">
                        @foreach (json_decode($model->gambar, true) as $image)
                            <div class="position-relative d-inline-block">
                                <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" style="width: 100px; height: 100px;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" style="transform: translate(50%, -50%);" onclick="removeOldImage('{{ $image }}')">
                                    &times;
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <div id="preview-container" class="d-flex flex-wrap gap-2 mb-2"></div>
                    <input type="hidden" name="deleted_images" id="deleted-images" value="">
                    <div class="mb-3">
                        <label class="form-label" for="gambarVR">Gambar VR <p class="text-danger mb-0">*tidak perlu menambahkan gambar VR ulang jika tidak ada perubahan</p></label>
                        <input type="file" class="form-control" name="gambarVR" id="gambarVR" accept=".jpg, .jpeg, .png">
                    </div>
                    <hr>
                    <label class="form-label" for="gambarVR">Fasilitas Gedung</label>
                    <div class="fasilitas-container mb-2" id="fasilitas-container">
                        @php
                            $fasilitas = json_decode($model->fasilitas, true) ?? [];
                        @endphp
                        @if (!empty($fasilitas))
                            @foreach ((array)$fasilitas as $f)
                                <div class="row fasilitas-field">
                                    <div class="col-md-8">
                                        <label class="form-label">Fasilitas</label>
                                        <input type="text" class="form-control" name="fasilitas[]" value="{{ $f }}" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger" onclick="hapusFasilitas(this)">-</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="tambahFasilitas()">+ Tambah Fasilitas</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/exifreader@4.12.0/dist/exif-reader.min.js"></script>
<script>
    async function check360Image(file) {
        const metadata = await file.arrayBuffer();
        const dataView = new DataView(metadata);
        const str = new TextDecoder("utf-8").decode(dataView);
        console.log(str);
        return str.includes("ProjectionType=equirectangular");
    };

    $(document).ready(function () {
        $('#gambarVR').change(async function(e) {
            const povFile = e.target.files[0];
            const reader = new FileReader();

            const tags = await ExifReader.load(povFile)
            console.log(tags);
            if ((typeof tags['ProjectionType'] !== 'undefined' && tags['ProjectionType'] !==
                    null) && tags['ProjectionType'].value === 'equirectangular') {
                selectedBooth.gambarVR = povFile;
                console.log(selectedBooth);
            } else {
                $('#gambarVR').val('');
                alert('File yang Anda unggah bukan gambar 360 derajat. Mohon unggah file yang sesuai.');
            }
        });
    });

    function tambahFasilitas() {
        const container = document.getElementById('fasilitas-container');

        const newField = document.createElement('div');
        newField.classList.add('row', 'fasilitas-field');
        newField.innerHTML = `
        <div class="col-md-8">
            <label class="form-label">Fasilitas</label>
            <input type="text" class="form-control" name="fasilitas[]" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger" onclick="hapusFasilitas(this)">-</button>
        </div>
        `;

        container.appendChild(newField);
    }

    function hapusFasilitas(button) {
        const row = button.closest('.fasilitas-field');
        row.remove();
    }

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
                        removeNewImage(index);
                    };

                    div.appendChild(img);
                    div.appendChild(removeBtn);
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    });

    function removeNewImage(index) {
        let fileInput = document.getElementById('gambar');
        let fileList = Array.from(fileInput.files);
        fileList.splice(index, 1);

        let newFileList = new DataTransfer();
        fileList.forEach(file => newFileList.items.add(file));
        fileInput.files = newFileList.files;
        fileInput.dispatchEvent(new Event('change'));
    }

    function removeOldImage(imagePath) {
        let deletedImagesInput = document.getElementById('deleted-images');
        let deletedImages = deletedImagesInput.value ? JSON.parse(deletedImagesInput.value) : [];

        if (!deletedImages.includes(imagePath)) {
            deletedImages.push(imagePath);
        }

        deletedImagesInput.value = JSON.stringify(deletedImages);
        let oldImagesContainer = document.getElementById('old-images-container');
        let imageElements = oldImagesContainer.querySelectorAll('img');

        imageElements.forEach(img => {
            if (img.getAttribute('src').includes(imagePath)) {
                img.parentElement.remove();
            }
        });
        return deletedImages;
    }
</script>
