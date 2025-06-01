@extends('template.home')

@section('content')
<div class="background text-center" style="margin: 7rem 0 5rem 0">
    <h1 class="display-1 fw-bold">Sistem Pemesanan Gedung
        <div class="text-primary">Universitas Brawijaya</div></h1>
</div>
<div class="row d-flex justify-content-between">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title d-flex align-item-center">Pemesanan Gedung <i class="iconoir-building fs-20 ms-1"></i></h4>
            </div>
            <div class="card-body pt-0">
                <p class="card-text text-muted ">Gunakan layanan pemesanan gedung Universitas Brawijaya untuk mendukung kegiatan akademik maupun non-akademik Anda. Tersedia berbagai pilihan gedung dengan fasilitas lengkap yang dapat disesuaikan dengan kebutuhan acara Anda.</p>
                <a href="{{ route('home.pemesanan-gedung') }}" class="btn btn-primary btn-sm">Pesan</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title d-flex align-item-center">Pemesanan Publikasi Acara <i class="iconoir-post fs-20 ms-1"></i></h4>
            </div>
            <div class="card-body pt-0">
                <p class="card-text text-muted ">Promosikan acara kampus Anda secara resmi melalui kanal publikasi Universitas Brawijaya. Layanan ini membantu menyebarkan informasi acara Anda agar menjangkau audiens yang lebih luas dengan cepat dan efektif.</p>
                <a href="{{ route('home.pemesanan-publikasi') }}" class="btn btn-primary btn-sm">Pesan</a>
            </div>
        </div>
    </div>
</div>
@endsection
