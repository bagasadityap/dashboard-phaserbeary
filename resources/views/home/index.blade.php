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
                <p class="card-text text-muted ">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint natus maiores reprehenderit laborum. Aut expedita neque molestiae debitis, officiis possimus?</p>
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
                <p class="card-text text-muted ">Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique voluptas natus officiis soluta facilis, suscipit eos eum minima nostrum repellat.
                    content.</p>
                <a href="{{ route('home.pemesanan-publikasi') }}" class="btn btn-primary btn-sm">Pesan</a>
            </div>                           
        </div>
    </div>
</div>
@endsection