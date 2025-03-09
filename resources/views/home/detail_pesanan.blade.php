@extends('template.home')

@section('content')
<style>
    .progress-step {
        width: 30px;
        height: 30px;
        position: absolute;
        top: -12px;
        transform: translateX(-50%);
    }

    .step-1 { left: 0%; }
    .step-2 { left: 20%; }
    .step-3 { left: 40%; }
    .step-4 { left: 60%; }
    .step-5 { left: 80%; }
    .step-6 { left: 100%; }

    .btn-sm {
        transform: scale(0.85);
    }
</style>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header" style="background-color: #e9ecef">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Pesanan Gedung</h4>
                        <p class="mb-0 text-muted mt-1">Dibuat {{ \Carbon\Carbon::parse($model->created_at)->translatedFormat('d F Y H:i') }}</p>
                    </div>
                    <div class="col-auto">
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
                        @endswitch
                    </div>
                </div>
            </div>
            <div class="card-body pt-0 mt-1">
                <div class="table-responsive mb-2">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <td>
                                    <i class="iconoir-building me-1 fs-20"></i>
                                    <p class="d-inline-block align-middle mb-0">
                                        <span class="d-block align-middle mb-0 product-name text-body fs-14 fw-semibold">{{ $model->judul }}</span>
                                        @if (!$model->gedung_id)
                                            <span class="text-danger font-13">Silakan pilih gedung ketika sudah diverifikasi oleh admin</span>
                                        @else
                                            <span class="text-muted font-13">{{ $model->gedung->nama }}</span>
                                        @endif
                                        <br><span class="text-muted font-13">Tanggal Acara:  {{ \Carbon\Carbon::parse($model->tanggal)->translatedFormat('d F Y') }}</span>
                                    </p>
                                </td>
                                <td class="d-flex justify-content-end">
                                    <div class="col-auto">
                                        <a href="" class="text-secondary"><i class="fas fa-download me-1"></i> Download Invoice</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr class="hr mt-0">
                <button type="button" class="btn rounded-pill btn-primary mb-2" onclick="pilihGedung({{ $model->id }})" {{ $model->is_verified  && !$model->gedung_id ? '' : 'disabled' }}>Pilih Gedung</button>
                <div class="mb-2">
                    <div>
                        <div class="d-flex justify-content-between">
                            <p class="text-body fw-semibold">Biaya Gedung :</p>
                            <p class="text-body-emphasis fw-semibold">Rp. {{ $model->gedung_id ? $model->gedung->harga : '0' }}</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="text-body fw-semibold">PPN 10% :</p>
                            <p class="text-body-emphasis fw-semibold">Rp. {{ $model->gedung_id ? $model->gedung->harga/100 : '0' }}</p>
                        </div>
                    </div>
                    <hr class="hr-dashed mt-0">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0">Total :</h5>
                        <h5 class="mb-0">Rp. {{ $model->gedung_id ? $model->total_harga : '0' }}</h5>
                    </div>
                </div>
                <hr class="hr mb-0">
                <div>
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title align-items-center d-flex fw-bold">
                                         Metode Pembayaran</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h5 class="accordion-header m-0" id="flush-headingOne">
                                        <button class="accordion-button collapsed fw-semibold d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                            Tranfer Bank <i class="iconoir-data-transfer-both fs-18 ms-1"></i>
                                        </button>
                                    </h5>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon
                                            tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice
                                            lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h5 class="accordion-header m-0" id="flush-headingTwo">
                                        <button class="accordion-button collapsed fw-semibold d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                            Kantor DPKA <i class="iconoir-house-rooms fs-18 ms-1"></i>
                                        </button>
                                    </h5>
                                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon
                                            tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice
                                            lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Detail Customer</h4>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <p class="text-body fw-semibold"><i class="iconoir-profile-circle text-secondary fs-20 align-middle me-1"></i>Nama :</p>
                        <p class="text-body-emphasis fw-semibold">{{ $model->user->name }}</p>
                      </div>
                      <div class="d-flex justify-content-between mb-1">
                          <p class="text-body fw-semibold"><i class="iconoir-handbag text-secondary fs-20 align-middle me-1"></i>Instansi :</p>
                          <p class="text-body-emphasis fw-semibold">{{ $model->user->instansi }}</p>
                      </div>
                      <div class="d-flex justify-content-between mb-1">
                          <p class="text-body fw-semibold"><i class="iconoir-mail text-secondary fs-20 align-middle me-1"></i>Email :</p>
                          <p class="text-body-emphasis fw-semibold">{{ $model->user->email }}</p>
                      </div>
                      <div class="d-flex justify-content-between mb-1">
                          <p class="text-body fw-semibold"><i class="iconoir-phone text-secondary fs-20 align-middle me-1"></i>No HP :</p>
                          <p class="text-body-emphasis fw-semibold">{{ $model->no_hp }}</p>
                      </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Dokumen</h4>
                        <button type="button" class="btn rounded-pill btn-primary btn-sm mb-0"><i class="iconoir-plus fs-18"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-secondary fs-20 align-middle me-1"></i>
                            {!! $model->surat_permohonan_acara
                                ? '<a href="' . asset('storage/' . $model->surat_permohonan_acara) . '" target="_blank">Surat Permohonan Acara</a>'
                                : 'Surat Permohonan Acara' !!}
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-secondary fs-20 align-middle me-1"></i>Bukti Pembayaran</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-secondary fs-20 align-middle me-1"></i>Dokumen (Opsional)</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-secondary fs-20 align-middle me-1"></i>Data Partisipan</p>
                    </div>
                    <p class="text-danger">*Mohon kirimkan data partisipan acara, setelah acara selesai</p>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <div class="col-lg-12">
    @include('home.progress')
</div> --}}
@endsection

@push('script')
<script>
    function pilihGedung(id) {
        window.location.href = '{{ route('home.pilih-gedung') }}/' + id;
    }
</script>
@endpush
