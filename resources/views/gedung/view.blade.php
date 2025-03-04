@if (!auth()->user()->hasRole('Customer'))
    @extends('template.dashboard')
@endif

@section('content')
<style>
    .carousel-item img {
        width: 100%;
        height: 30rem;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .carousel-item img {
            height: 15rem;
        }
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5 mb-2">
                        <div class="px-3">
                            <h1 class="my-4 font-weight-bold"> {{ $model->nama }} </h1>
                            <p class="fs-14 text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Corrupti, iusto eveniet vero aliquid, sapiente quibusdam autem, distinctio id voluptatum omnis labore aliquam unde mollitia odit temporibus nulla inventore praesentium cupiditate.
                            </p>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="align-middle" style="width: 8rem">
                                        <p class="mb-0 d-flex align-items-center">
                                            <i class="iconoir-community me-1" style="font-size: 1.8rem;"></i> Kapasitas
                                        </p>
                                    </td>
                                    <td class="align-middle fw-bold">{{ $model->kapasitas }}</td>
                                </tr>
                                <tr>
                                    <td class="align-middle" style="width: 8rem">
                                        <p class="mb-0 d-flex align-items-center">
                                            <i class="iconoir-money-square me-1" style="font-size: 1.8rem;"></i> Harga
                                        </p>
                                    </td>
                                    <td class="align-middle fw-bold">
                                        Rp. {{ number_format($model->harga, 0, ',', '.') }}
                                    </td>                                    
                                </tr>
                                <tr>
                                    <td class="align-middle" style="width: 8rem">
                                        <p class="mb-0 d-flex align-items-center">
                                            <i class="iconoir-map-pin me-1" style="font-size: 1.8rem;"></i> Lokasi
                                        </p>
                                    </td>
                                    <td class="align-middle fw-bold">{{ $model->lokasi }}</td>
                                </tr>
                            </table>                                                       
                            <button onclick="view_360({{ $model->id }})" class="btn btn-outline-primary px-2 me-1 d-inline-flex align-items-center"><i class="iconoir-view-360 fs-20 me-1"></i>Lihat Tampilan 360</button>
                        </div>
                    </div>
                    <div class="col-lg-7 text-center mt-4">
                        <div id="carouselExampleIndicators" class="carousel slide">
                            <div class="carousel-indicators">
                              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjt4_LMrGTfxkhOXdhfMc-fg1SkRcoFahtp6KQ4islNriREee-SrQUV1tK6PGKjdfapRkTbhooVsXO6OdMRcRsR585zioqhi9QiJooJmHqaT-ctkR2xHcp3PF0A4VGT_GaNh73lR6le5IhD/s1600/13116750_812514478878639_361392773_n.jpg" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.genpi.co/resize/1080x720-100/uploads/jatim/arsip/normal/2021/11/21/gedung-rektorat-universitas-brawijaya-antaraendang-sukare-vsku.webp" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://assets.promediateknologi.id/crop/0x0:0x0/750x500/webp/photo/2022/01/26/1461115235.jpg" class="d-block w-100" alt="...">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Next</span>
                            </button>
                        </div>                                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        function view_360(id) {
            window.location.href = '{{ route('gedung.view-360') }}/' + id;
        }
    </script>
@endpush