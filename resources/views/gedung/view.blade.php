@php
    $layout = auth()->user()->hasRole('Customer') ? 'template.home' : 'template.dashboard';
@endphp

@extends($layout)

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
                            <p class="fs-14 text-muted" style="text-align: justify;">{!! nl2br(e($model->deskripsi)) !!}</p>
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
                                @foreach (json_decode($model->gambar, true) as $index => $image)
                                    <button type="button" data-bs-target="#carouselExampleIndicators"
                                        data-bs-slide-to="{{ $index }}"
                                        class="{{ $index == 0 ? 'active' : '' }}"
                                        aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach (json_decode($model->gambar) as $index => $image)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image) }}" class="d-block w-100" alt="Slide {{ $index + 1 }}">
                                    </div>
                                @endforeach
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
