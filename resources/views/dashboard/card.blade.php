<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                    <div class="col-9">
                        <p class="text-dark mb-0 fw-semibold fs-18">Pesanan Gedung</p>
                        <h3 class="mt-2 mb-0 fw-bold">{{ $sumPesananGedung }}</h3>
                    </div>
                    <div class="col-3 align-self-center">
                        <div
                            class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                            <i
                                class="iconoir-building h1 align-self-center mb-0 text-secondary"></i>
                        </div>
                    </div>
                </div>
                    <p class="mb-0 text-truncate text-muted mt-3 fs-14"><span class="text-danger fw-bold">{{ $unconfirmedPesananGedung }} </span> Pesanan Belum Dikonfirmasi</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                    <div class="col-9">
                        <p class="text-dark mb-0 fw-semibold fs-18">Pesanan Publikasi Acara</p>
                        <h3 class="mt-2 mb-0 fw-bold">{{ $sumPesananPublikasi }}</h3>
                    </div>
                    <div class="col-3 align-self-center">
                        <div
                            class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                            <i
                                class="iconoir-post h1 align-self-center mb-0 text-secondary"></i>
                        </div>
                    </div>
                </div>
                <p class="mb-0 text-truncate text-muted mt-3 fs-14"><span class="text-danger fw-bold">{{ $unconfirmedPesananPublikasi }} </span> Pesanan Belum Dikonfirmasi</p>
            </div>
        </div>
    </div>
</div>
