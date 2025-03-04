@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-theme-white-2 rounded-pill" role="alert">
        <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-success rounded-circle mx-auto me-1">
            <i class="fas fa-check align-self-center mb-0 text-white "></i>
        </div>
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif (session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-theme-white-2 rounded-pill" role="alert">
        <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-danger rounded-circle mx-auto me-1">
            <i class="fas fa-check align-self-center mb-0 text-white "></i>
        </div>
        <strong>Gagal!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
