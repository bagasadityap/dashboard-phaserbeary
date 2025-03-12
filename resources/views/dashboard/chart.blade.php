<div class="row justify-content-center">
    <div class="col-md-6 col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Grafik Pesanan</h4>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="overview" class="apex-charts"></div>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="text-dark mb-0 fw-semibold fs-14">New Visitors</p>
                        <h2 class="mt-0 mb-0 fw-bold">1,282</h2>
                    </div>
                <div id="visitors_report" class="apex-charts mb-2"></div>
                <button type="button" class="btn btn-primary w-100 btn-lg fs-14">More
                    Detail <i class="fa-solid fa-arrow-right-long"></i>
                </button>
            </div>
        </div>
    </div> --}}
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Status Barang</h4>
                    </div>
                    <div class="col-auto">
                        <div class="text-center">
                            <h6 class="text-uppercase text-danger mt-1 m-0"><span class="fs-16 fw-semibold">{{ $chartData[0] }} </span> Menunggu</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="status" class="apex-charts mt-n2"></div>
                <div class="text-center">
                    <button type="button" class="btn btn-primary mx-auto">More Detail <i class="fa-solid fa-arrow-right-long"></i> </button>
                </div>
            </div>
        </div>
    </div>
</div>
