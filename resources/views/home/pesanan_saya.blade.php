@extends('template.home')

@section('content')
<h2 class="mb-3">Pesanan Saya</h2>
<div class="col-10">
    @for ($i = 0; $i < 5; $i++)
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between py-2" style="background-color: #e9ecef">
            <h4 class="card-title d-flex align-items-center">Pesanan Gedung
                <span class="badge rounded-pill bg-success-subtle text-success ms-2">Success</span>
                {{-- <span class="badge rounded-pill bg-danger-subtle text-danger ms-2">Danger</span> --}}
            </h4>
            <i class="fa-solid fa-ellipsis fs-20 me-1"></i>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card-body d-flex justify-content-between">
                    <div class="row col-11">
                        <h5 class="d-flex align-items-center">
                            Lorem, ipsum dolor sit amet consectetur adipisicing.
                        </h5>                        
                        <div class="d-flex align-item-center">
                            <i class="iconoir-building fs-18"></i>
                            <p class="card-text mb-0">Auditorium Algoritma Filkom G.2</p>
                        </div>
                        <p class="card-text mb-0"><small class="text-muted">1 Januari 2025</small></p>
                    </div>
                    <div class="col-1 d-flex align-items-center justify-content-end">
                        <button type="button" onclick="detail()" class="btn btn-sm btn-outline-success">Detail</button>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    @endfor
</div>
@endsection

@push('script')
    <script>
        function detail() {
            window.location.href = '{{ route('home.detail-pesanan') }}';
        }
    </script>
@endpush