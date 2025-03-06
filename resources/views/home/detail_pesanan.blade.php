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
</style>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header" style="background-color: #e9ecef">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Pesanan #2R3NR1NRKN1</h4>
                        <p class="mb-0 text-muted mt-1">Dibuat 1 Januari 2025 pada 07:45</p>
                    </div>
                    <div class="col-auto">
                        {{-- <div class="bg-primary-subtle p-2 border-dashed border-primary rounded">
                            <span class="text-primary fw-semibold">LUNAS</span>
                        </div> --}}
                        <div class="bg-warning-subtle p-2 border-dashed border-warning rounded">
                            <span class="text-warning fw-semibold">BELUM BAYAR</span>
                        </div>
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
                                        <span class="d-block align-middle mb-0 product-name text-body fs-14 fw-semibold">BUMN Goes to Campus Batch 5</span>
                                        <span class="text-danger font-13">Silakan pilih gedung ketika sudah diverifikasi oleh admin</span>
                                        {{-- <span class="text-muted font-13">Auditorium Algoritma Gedung G.2 FILKOM</span> --}}
                                        <br><span class="text-muted font-13">Tanggal Acara:  25 Agustus 2025</span>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr class="hr mt-0">
                <button type="button" class="btn rounded-pill btn-primary" disabled>Pilih Gedung</button>
                {{-- <div>
                    <div class="d-flex justify-content-between">
                      <p class="text-body fw-semibold">Biaya Gedung :</p>
                      <p class="text-body-emphasis fw-semibold">Rp. 20.000.000</p>
                    </div>
                    <div class="d-flex justify-content-between">
                      <p class="text-body fw-semibold">PPN :</p>
                      <p class="text-body-emphasis fw-semibold">Rp. 2.000.000</p>
                    </div>
                </div>
                <hr class="hr-dashed mt-0">
                <div class="d-flex justify-content-between">
                    <h5 class="mb-0">Total :</h5>
                    <h5 class="mb-0">Rp. 22.000.000</h5>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        {{-- <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Order Summary</h4>
                    </div>
                    <div class="col-auto">
                        <span class="badge rounded text-warning bg-warning-subtle fs-10 p-1">Payment pending</span>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div>
                    <div class="d-flex justify-content-between">
                      <p class="text-body fw-semibold">Items subtotal :</p>
                      <p class="text-body-emphasis fw-semibold">$1060</p>
                    </div>
                    <div class="d-flex justify-content-between">
                      <p class="text-body fw-semibold">Discount :</p>
                      <p class="text-danger fw-semibold">-$80</p>
                    </div>
                    <div class="d-flex justify-content-between">
                      <p class="text-body fw-semibold">Tax :</p>
                      <p class="text-body-emphasis fw-semibold">$180.70</p>
                    </div>
                    <div class="d-flex justify-content-between">
                      <p class="text-body fw-semibold">Subtotal :</p>
                      <p class="text-body-emphasis fw-semibold">$1160.70</p>
                    </div>
                    <div class="d-flex justify-content-between">
                      <p class="text-body fw-semibold mb-0">Shipping Cost :</p>
                      <p class="text-body-emphasis fw-semibold mb-0">$20</p>
                    </div>
                </div>
                <hr class="hr-dashed">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Total :</h4>
                    <h4 class="mb-0">$1180.70</h4>
                  </div>
            </div>
        </div> --}}
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
                    <div class="d-flex justify-content-between mb-2">
                        <p class="text-body fw-semibold"><i class="iconoir-profile-circle text-secondary fs-20 align-middle me-1"></i>Nama :</p>
                        <p class="text-body-emphasis fw-semibold">Tom Lembong</p>
                      </div>
                      <div class="d-flex justify-content-between mb-2">
                          <p class="text-body fw-semibold"><i class="iconoir-handbag text-secondary fs-20 align-middle me-1"></i>Instansi :</p>
                          <p class="text-body-emphasis fw-semibold">BUMN</p>
                      </div>
                      <div class="d-flex justify-content-between mb-2">
                          <p class="text-body fw-semibold"><i class="iconoir-mail text-secondary fs-20 align-middle me-1"></i>Email :</p>
                          <p class="text-body-emphasis fw-semibold">tomlembong@gula.com</p>
                      </div>
                      <div class="d-flex justify-content-between mb-2">
                          <p class="text-body fw-semibold"><i class="iconoir-phone text-secondary fs-20 align-middle me-1"></i>No HP :</p>
                          <p class="text-body-emphasis fw-semibold">081234568000</p>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12">
    @include('home.progress')
</div>
@endsection
