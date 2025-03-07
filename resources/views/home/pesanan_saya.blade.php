@extends('template.home')

@section('content')
<h2 class="mb-3">Pesanan Saya</h2>
<div class="col-10">
    @foreach ($models as $model)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between py-2" style="background-color: #e9ecef">
                <h4 class="card-title d-flex align-items-center">Pesanan Gedung
                    @switch($model->status)
                        @case(0)
                            <span class="badge rounded-pill bg-warning-subtle text-warning ms-2">Pending</span>
                            @break
                        @case(1)
                            <span class="badge rounded-pill bg-blue-subtle text-blue ms-2">Dikonfirmasi</span>
                            @break
                        @case(2)
                            <span class="badge rounded-pill bg-warning-subtle text-warning ms-2">Belum Bayar</span>
                            @break
                        @case(3)
                            <span class="badge rounded-pill bg-success-subtle text-success ms-2">Lunas</span>
                            @break
                        @case(4)
                            <span class="badge rounded-pill bg-danger-subtle text-danger ms-2">Ditolak</span>
                            @break
                    @endswitch
                </h4>
                <i class="fa-solid fa-ellipsis fs-20 me-1"></i>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-body d-flex justify-content-between">
                        <div class="row col-11">
                            <h5 class="d-flex align-items-center">
                                {{ $model->judul }}
                            </h5>
                            <div class="d-flex align-item-center">
                                <i class="iconoir-building fs-18 me-1"></i>
                                @if (!$model->gedung_id)
                                    <p class="card-text text-danger mb-0">*Belum ada gedung yang dipilih</p>
                                @else
                                    <p class="card-text mb-0">{{ $model->gedung->name }}</p>
                                @endif
                            </div>
                            <p class="card-text mb-0"><small class="text-muted">{{ \Carbon\Carbon::parse($model->created_at)->translatedFormat('d F Y H:i') }}</small></p>
                        </div>
                        <div class="col-1 d-flex align-items-center justify-content-end">
                            <button type="button" onclick="detail({{ $model->id }})" class="btn btn-sm btn-outline-success">Detail</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('script')
    <script>
        function detail(id) {
            window.location.href = '{{ url('detail-pesanan') }}/' + id;
        }
    </script>
@endpush
