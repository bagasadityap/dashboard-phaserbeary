@extends('template.home')

@section('content')
<h2 class="mb-3">Pesanan Saya</h2>
<div class="col-10">
    @if ($models->count() > 0)
        @foreach ($models as $model)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between py-2" style="background-color: #e9ecef">
                    <h4 class="card-title d-flex align-items-center">{{ $model->type == 'gedung' ? 'Pesanan Gedung' : 'Pesanan Publikasi' }}
                        @switch($model->status)
                            @case(0)
                                <div class="bg-warning-subtle p-2 border-dashed border-warning rounded">
                                    <span class="text-warning fw-semibold">MENUNGGU DIKONFIRMASI</span>
                                </div>
                                @break
                            @case(1)
                                <div class="bg-blue-subtle p-2 border-dashed border-blue rounded">
                                    <span class="text-blue fw-semibold">DIKONFIRMASI</span>
                                </div>
                                @break
                            @case(2)
                                <div class="bg-warning-subtle p-2 border-dashed border-warning rounded">
                                    <span class="text-warning fw-semibold">BELUM BAYAR</span>
                                </div>
                                @break
                            @case(3)
                                <div class="bg-success-subtle p-2 border-dashed border-success rounded">
                                    <span class="text-success fw-semibold">LUNAS</span>
                                </div>
                                @break
                            @case(4)
                                <div class="bg-danger-subtle p-2 border-dashed border-danger rounded">
                                    <span class="text-danger fw-semibold">DITOLAK</span>
                                </div>
                                @break
                            @case(5)
                                <div class="bg-danger-subtle p-2 border-dashed border-danger rounded">
                                    <span class="text-danger fw-semibold">PEMBAYARAN DITOLAK</span>
                                </div>
                                @break
                        @endswitch
                    </h4>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-body d-flex justify-content-between">
                            <div class="row col-11">
                                <h5 class="d-flex align-items-center">
                                    {{ $model->judul }}
                                </h5>
                                <div class="d-flex align-item-center">
                                @if ($model->type == 'gedung')
                                        <i class="iconoir-building fs-18 me-1"></i>
                                    @if (!$model->gedungId)
                                        <p class="card-text text-danger mb-0">*Belum ada gedung yang dipilih</p>
                                    @else
                                        <p class="card-text mb-0">{{ $model->gedung->name }}</p>
                                    @endif
                                @endif
                                </div>
                                <p class="card-text mb-0"><small class="text-muted">{{ \Carbon\Carbon::parse($model->created_at)->translatedFormat('d F Y H:i') }}</small></p>
                            </div>
                            <div class="col-1 d-flex align-items-center justify-content-end">
                                @if($model->type == 'gedung')
                                    <button type="button" onclick="detailGedung({{ $model->id }})" class="btn btn-sm btn-outline-success">Detail</button>
                                @else
                                    <button type="button" onclick="detailPublikasi({{ $model->id }})" class="btn btn-sm btn-outline-success">Detail</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center mt-4">
            <p class="fs-16">Mohon maaf, pesanan Anda belum tersedia. Silakan ajukan pemesanan terlebih dahulu melalui menu pemesanan.</p>
        </div>
    @endif
</div>
@endsection

@push('script')
    <script>
        function detailGedung(id) {
            window.location.href = '{{ url('detail-pesanan-gedung') }}/' + id;
        }
        function detailPublikasi(id) {
            window.location.href = '{{ url('detail-pesanan-publikasi') }}/' + id;
        }
    </script>
@endpush
