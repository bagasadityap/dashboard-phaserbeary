@extends('template.home')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">                      
                    <h4 class="card-title">Form Pemesanan Gedung</h4>                      
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <form>
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Acara</label>
                    <input type="text" class="form-control" name="judul" id="judul" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Pelaksanaan</label>
                    <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label">No. HP yang Dapat Dihubungi</label>
                    <input type="text" class="form-control" name="no_hp" id="no_hp" required>
                    <small class="form-text text-muted">Format No. HP: 08XXXXXXXXXX</small>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" required>
                </div>
                <div class="mb-3">
                    <label for="dokumen" class="form-label">Dokumen</label>
                    <input type="file" class="form-control" name="dokumen" id="dokumen" required>
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