@extends('template.home')

@section('content')
<div class="col-12">
    @include('template.alert')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="card-title">Form Pemesanan Publikasi Acara</h4>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <form action="{{ route('pesanan.publikasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Acara</label>
                    <input type="text" class="form-control" name="judul" id="judul" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tanggal" class="form-label">Tanggal Publikasi Acara</label>
                        <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="no_hp" class="form-label">No. HP yang Dapat Dihubungi</label>
                        <input type="text" class="form-control" name="no_hp" id="no_hp" required>
                        <small class="form-text text-muted">Format No. HP: 08XXXXXXXXXX</small>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="surat_permohonan_acara" class="form-label">Surat Permohonan Acara</label>
                    <input type="file" class="form-control" name="surat_permohonan_acara" id="surat_permohonan_acara" required>
                </div>
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan Pesanan</label>
                    <textarea class="form-control" rows="5" name="catatan" id="catatan" required></textarea>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="flexCheckDefaultdemo" required>
                    <label class="form-check-label text-danger" for="flexCheckDefaultdemo">
                        Saya memastikan bahwa data yang saya berikan benar. <br>
                        <small>*Data yang sudah masuk tidak dapat diubah.</small>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-danger">Cancel</button>
            </form>
        </div>
</div>
@endsection
