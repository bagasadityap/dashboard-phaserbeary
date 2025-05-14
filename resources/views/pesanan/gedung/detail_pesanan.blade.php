@extends('template.dashboard')

@push('css')
<link href="{{ asset('assets/libs/mobius1-selectr/selectr.min.css') }}" rel="stylesheet" type="text/css" />
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
                                            <span class="text-danger font-13">Belum ada gedung yang dipilih</span>
                                        @else
                                            <span class="text-muted font-13">{{ $model->gedung->nama }}</span>
                                        @endif
                                        <br><span class="text-muted font-13">Tanggal Acara:  {{ \Carbon\Carbon::parse($model->tanggal)->translatedFormat('d F Y') }}</span>
                                        <br><span class="text-muted font-13">Jumlah Peserta: {{ $model->jumlahPeserta }}</span>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <div class="bg-secondary-subtle p-2 border-dashed border-secondary rounded my-2">
                        <span class="text-secondary fw-semibold">Catatan : </span><br>
                        <span class="text-secondary fw-normal text-break">{{ $model->catatan }}</span>
                    </div>
                    @if ($model->isConfirmed && !$model->isPaid)
                        <div class="col my-2 d-flex">
                            <button type="button" class="btn rounded-pill btn-primary" onclick="addOptional({{ $model->id }})">Tambah Opsional Pesanan</button>
                        </div>
                    @endif
                </div>
                <hr class="hr mt-0">
                @if (!$model->isConfirmed)
                    <div class="col my-2 d-flex">
                        <button type="button" class="btn rounded-pill btn-primary" onclick="confirm({{ $model->id }})">Konfirmasi Pesanan</button>
                    </div>
                @elseif(!$model->gedung_id)
                    <form action="{{ route('pesanan.gedung.inputGedung', ['id' => $model->id]) }}" method="POST" class="row mb-4">
                        @csrf
                        <label class="mb-2 text-dark fw-medium">Pilih Gedung</label>
                        <div class="col-md-10">
                            <select id="multiSelect" name="gedungs[]" multiple>
                                @foreach ($gedungs as $gedung)
                                    <option value="{{ $gedung->id }}"
                                        @if(in_array($gedung->id, $selectedGedung)) selected @endif>
                                        {{ $gedung->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn rounded-pill btn-primary">Kirim</button>
                        </div>
                    </form>
                @endif
                <div class="mb-2">
                    <div>
                        <div class="d-flex justify-content-between">
                            <p class="text-body fw-semibold">Harga Gedung :</p>
                            <p class="text-body-emphasis fw-semibold">Rp. {{ $model->hargaGedung ? $model->hargaGedung : '0' }}</p>
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
                @if ($model->gedung_id && !$model->isPaid)
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
                            {!! $model->surat_permohonan_acara
                                ? '<a href="' . asset('storage/' . $model->surat_permohonan_acara) . '" target="_blank">Surat Permohonan Acara</a>'
                                : 'Surat Permohonan Acara' !!}
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-secondary fs-20 align-middle me-1"></i>
                            {!! $model->bukti_pembayaran
                                ? '<a href="' . asset('storage/' . $model->bukti_pembayaran) . '" target="_blank">Bukti Pembayaran</a>'
                                : 'Bukti Pembayaran' !!}
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-secondary fs-20 align-middle me-1"></i>
                            {!! $model->dokumen_opsional
                                ? '<a href="' . asset('storage/' . $model->dokumen_opsional) . '" target="_blank">Dokumen (Opsional)</a>'
                                : 'Dokumen (Opsional)' !!}
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-body fw-semibold mb-0"><i class="iconoir-empty-page text-secondary fs-20 align-middle me-1"></i>
                            {!! $model->data_partisipan
                                ? '<a href="' . asset('storage/' . $model->data_partisipan) . '" target="_blank">Data Partisipan</a>'
                                : 'Data Partisipan' !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('assets/libs/mobius1-selectr/selectr.min.js') }}"></script>
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/libs/imask/imask.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/forms-advanced.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    function confirm(id) {
        bootbox.dialog({
            title: '<span class="text-light">Perhatian!</span>',
            message: 'Apakah anda yakin untuk melakukan konfirmasi/menolak pesanan ini?',
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
                        sendStatus(id, 'tolak');
                    },
                },
            },
        });
    }

    function sendStatus(id, status) {
        let url = "{{ route('pesanan.gedung.confirm') }}/" + id;
        console.log("Generated URL:", url);

        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                status: status
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
        bootbox.confirm({
            title: '<span class="text-light">Perhatian!</span>',
            message: 'Apakah anda yakin mengonfirmasi pembayaran pesanan ini?',
            buttons: {
                confirm: {
                    label: 'Konfirmasi',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'Batal',
                    className: 'btn-secondary'
                }
            },
            callback: function(result) {
                if (result) {
                    $.ajax({
                        url: '{{ route('pesanan.gedung.confirm-payment') }}/' + id,
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            window.location.reload();
                            bootbox.hideAll();
                        },
                        error: function() {
                            toastr.error('Terjadi kesalahan.');
                        }
                    });
                }
            }
        });
    }

    function addOptional(id) {
        $.ajax({
            url: '{{ route('pesanan.gedung.add-optional') }}/' + id,
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
