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
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Status Pesanan</h4>
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
            </div>
        </div>
    </div>
</div>
