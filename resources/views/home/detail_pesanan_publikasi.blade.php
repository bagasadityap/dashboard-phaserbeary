@extends('template.home')

@push('css2')
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/block-ui.js') }}"></script>
@endpush

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
    @include('template.alert')
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header" style="background-color: #e9ecef">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Pesanan Publikasi Acara</h4>
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
                            @case(5)
                                <div class="bg-danger-subtle p-2 border-dashed border-danger rounded">
                                    <span class="text-danger fw-semibold">PEMBAYARAN DITOLAK</span>
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
                                    <i class="iconoir-post me-1 fs-20"></i>
                                    <p class="d-inline-block align-middle mb-0">
                                        <span class="d-block align-middle mb-0 product-name text-body fs-14 fw-semibold">{{ $model->judul }}</span>
                                        <span class="text-muted font-13">Tanggal Publikasi:  {{ \Carbon\Carbon::parse($model->tanggal)->translatedFormat('d F Y') }}</span>
                                    </p>
                                </td>
                                @if ($model->totalHarga)
                                    <td class="d-flex justify-content-end">
                                        <div class="col-auto">
                                            <a href="{{ route('pesanan.publikasi.download-invoice', ['id' => $model->id]) }}" class="text-secondary"><i class="fas fa-download me-1"></i> Download Invoice</a>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <div class="bg-white-subtle p-2 border border-secondary rounded my-2">
                        <span class="text-secondary fw-semibold">Deskripsi Acara : </span><br>
                        <span class="text-secondary fw-normal text-break">{!! nl2br(e($model->deskripsiAcara)) !!}</span>
                    </div>
                    <div class="bg-secondary-subtle p-2 border-dashed border-secondary rounded my-2">
                        <span class="text-secondary fw-semibold">Catatan : </span><br>
                        <span class="text-secondary fw-normal text-break">{{ $model->catatan }}</span>
                    </div>
                    @if ($model->status == 4 || $model->status == 5)
                        <div class="bg-danger-subtle p-2 border-dashed border-danger rounded my-2">
                            <span class="text-danger fw-semibold">Alasan Penolakan : </span><br>
                            <span class="text-danger fw-normal text-break">{{ $model->alasanPenolakan }}</span>
                        </div>
                    @endif
                </div>
                <hr class="hr mt-0">
                <div class="mb-2">
                    <div>
                        <div class="d-flex justify-content-between">
                            <p class="text-body fw-semibold">Harga Publikasi Acara :</p>
                            <p class="text-body-emphasis fw-semibold">Rp. {{ $model->hargaPublikasi ? $model->hargaPublikasi : '0' }}</p>
                        </div>
                        @foreach ($opsiTambahan as $opsi)
                            <div class="d-flex justify-content-between">
                                <p class="text-body fw-semibold">{{ $opsi->nama }} :</p>
                                <p class="text-body-emphasis fw-semibold">Rp. {{ $opsi->harga ? $opsi->harga : '0' }}</p>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-between">
                            <p class="text-body fw-semibold">PPN 10% :</p>
                            <p class="text-body-emphasis fw-semibold">Rp. {{ $model->PPN ? $model->PPN : '0' }}</p>
                        </div>
                    </div>
                    <hr class="hr-dashed mt-0">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0">Total :</h5>
                        <h5 class="mb-0">Rp. {{ $model->totalHarga ? $model->totalHarga : '0' }}</h5>
                    </div>
                </div>
                <hr class="hr mb-0">
                @if ($model )
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
                @endif
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
                          <p class="text-body-emphasis fw-semibold">{{ $model->noHP }}</p>
                      </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Dokumen</h4>
                        <button type="button" class="btn rounded-pill btn-primary btn-sm mb-0" onclick="tambahDokumen({{ $model->id }})"><i class="iconoir-plus fs-18"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-secondary fs-20 align-middle me-1"></i>
                            {!! $model->suratPermohonanAcara
                                ? '<a href="' . asset('storage/' . $model->suratPermohonanAcara) . '" target="_blank">Surat Permohonan Acara</a>'
                                : 'Surat Permohonan Acara' !!}
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-secondary fs-20 align-middle me-1"></i>
                            {!! $model->posterAcara
                                ? '<a href="' . $model->posterAcara . '" target="_blank">Poster Acara</a>'
                                : 'Poster Acara' !!}
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-secondary fs-20 align-middle me-1"></i>
                            {!! $model->buktiPembayaran
                                ? '<a href="' . asset('storage/' . $model->buktiPembayaran) . '" target="_blank">Bukti Pembayaran</a>'
                                : 'Bukti Pembayaran' !!}
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-secondary fs-20 align-middle me-1"></i>
                            {!! $model->dokumenOpsional
                                ? '<a href="' . asset('storage/' . $model->dokumenOpsional) . '" target="_blank">Dokumen (Opsional)</a>'
                                : 'Dokumen (Opsional)' !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Dokumen (Admin)</h4>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div>
                    @php
                        $dokumenOperator = json_decode($model->dokumenOperator, true) ?? [];
                    @endphp
                    @if (!$dokumenOperator)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-danger fs-20 align-middle me-1"></i>
                                Tidak ada dokumen yang dikirim
                            </p>
                        </div>
                    @else
                        @foreach ((array)$dokumenOperator as $dokumen)
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-secondary fs-20 align-middle me-1"></i>
                                    <a href="{{ asset('storage/' . $dokumen['file']) }}" target="_blank">{{ $dokumen['nama'] }}</a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('js/bootbox.min.js') }}"></script>
<script>
    function tambahDokumen(id) {
        $.ajax({
            url: '{{ route('home.tambah-dokumen-publikasi') }}/' + id,
            success: function(response) {
                bootbox.dialog({
                    title: 'Tambah Dokumen',
                    message: response,
                });
            },
            error: function(response) {
            }
        }).done(function() {
            $('#table').unblock();
        });
    }
</script>
@endpush
