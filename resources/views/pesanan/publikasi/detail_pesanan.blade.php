@extends('template.dashboard')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
</style>
<div class="row">
    @include('template.alert')
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header" style="background-color: #e9ecef">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Pesanan Publikasi</h4>
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
                    @if ($model->isConfirmed && !$model->isPaid)
                        <div class="col my-2 d-flex">
                            <button type="button" class="btn rounded-pill btn-primary" onclick="addOptional({{ $model->id }})">Tambah Harga Pesanan</button>
                        </div>
                    @endif
                </div>
                <hr class="hr mt-0">
                @if (!$model->isConfirmed)
                    <div class="col my-2 d-flex">
                        <button type="button" class="btn rounded-pill btn-primary" onclick="confirm({{ $model->id }})">Konfirmasi Pesanan</button>
                    </div>
                @endif
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
                @if ($model->totalHarga && !$model->isPaid)
                    <div class="col mt-3 d-flex justify-content-end">
                        <button type="button" class="btn rounded-pill btn-primary" onclick="confirmPayment({{ $model->id }})">Konfirmasi Pembayaran</button>
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
                    <div class="col">
                        <h4 class="card-title">Dokumen</h4>
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
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Dokumen (Admin)</h4>
                        <button type="button" class="btn rounded-pill btn-primary btn-sm mb-0" onclick="tambahDokumen({{ $model->id }})"><i class="iconoir-plus fs-18"></i></button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    function confirm(id) {
        bootbox.dialog({
            title: '<span class="text-light">Perhatian!</span>',
            message: `
                <p>Apakah Anda yakin ingin mengonfirmasi/menolak pesanan ini?</p>
                <div class="form-group">
                    <label for="alasanPenolakan">Alasan Penolakan (hanya untuk penolakan pesanan)</label>
                    <input type="text" id="alasanPenolakan" name="alasanPenolakan" class="form-control">
                </div>
            `,
            buttons: {
                confirm: {
                    label: 'Konfirmasi',
                    className: 'btn-success',
                    callback: function() {
                        sendStatus(id, 'konfirmasi');
                    }
                },
                reject: {
                    label: 'Tolak',
                    className: 'btn-danger',
                    callback: function() {
                        const alasan = document.getElementById('alasanPenolakan').value;
                        sendStatus(id, 'tolak', alasan);
                    },
                },
            },
        });
    }

    function sendStatus(id, status, alasan) {
        let url = "{{ route('pesanan.publikasi.confirm') }}/" + id;
        console.log("Generated URL:", url);

        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                status: status,
                alasanPenolakan: alasan
            },
            success: function(response) {
                console.log("Response:", response);
                window.location.reload();
                bootbox.hideAll();
            },
            error: function(xhr, status, error) {
                toastr.error("Terjadi kesalahan.");
            }
        });
    }

    function confirmPayment(id) {
        bootbox.dialog({
            title: '<span class="text-light">Perhatian!</span>',
            message: `
                <p>Apakah Anda yakin ingin mengonfirmasi/menolak pembayaran pesanan ini?</p>
                <div class="form-group">
                    <label for="alasanPenolakan">Alasan Penolakan (hanya untuk penolakan pesanan)</label>
                    <input type="text" id="alasanPenolakan" name="alasanPenolakan" class="form-control">
                </div>
            `,
            buttons: {
                confirm: {
                    label: 'Konfirmasi',
                    className: 'btn-success',
                    callback: function() {
                        sendStatusPayment(id, 'konfirmasi');
                    }
                },
                reject: {
                    label: 'Tolak',
                    className: 'btn-danger',
                    callback: function() {
                        const alasan = document.getElementById('alasanPenolakan').value;
                        sendStatusPayment(id, 'tolak', alasan);
                    },
                },
            },
        });
    }

    function sendStatusPayment(id, status, alasan) {
        let url = "{{ route('pesanan.publikasi.confirm-payment') }}/" + id;
        console.log("Generated URL:", url);

        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                status: status,
                alasanPenolakan: alasan
            },
            success: function(response) {
                console.log("Response:", response);
                window.location.reload();
                bootbox.hideAll();
            },
            error: function(xhr, status, error) {
                toastr.error("Terjadi kesalahan.");
            }
        });
    }

    function tambahDokumen(id) {
        $.ajax({
            url: '{{ route('pesanan.publikasi.tambah-dokumen') }}/' + id,
            success: function(response) {
                bootbox.dialog({
                    title: 'Tambah Dokumen',
                    message: response,
                    size: 'large',
                });
            },
            error: function(response) {
            }
        }).done(function() {
            $('#table').unblock();
        });
    }

    function addOptional(id) {
        $.ajax({
            url: '{{ route('pesanan.publikasi.add-order-cost') }}/' + id,
            success: function(response) {
                bootbox.dialog({
                    title: 'Tambah Opsional Pesanan',
                    message: response,
                    size: 'large',
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
