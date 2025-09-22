@php
    $layout = auth()->user()->hasRole('Customer') ? 'template.home' : 'template.dashboard';
@endphp

@extends($layout)

@section('content')
<style>
    .carousel-item img {
        width: 100%;
        height: 30rem;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .carousel-item img {
            height: 15rem;
        }
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5 mb-2">
                        <div class="px-3">
                            <h1 class="my-4 font-weight-bold"> {{ $model['title'] }} </h1>
                            <p class="fs-14 text-muted" style="text-align: justify;">{!! nl2br(e($model['description'])) !!}</p>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="align-middle" style="width: 8rem">
                                        <p class="mb-0 d-flex align-items-center">
                                            <i class="iconoir-clipboard-check me-1" style="font-size: 1.8rem;"></i> Status
                                        </p>
                                    </td>
                                    <td class="align-middle fw-bold">
                                        @if ((int) $model['status'] === 0)
                                            <span class="badge rounded-pill bg-warning p-1">Need to Review</span>
                                        @elseif ((int) $model['status'] === 1)
                                            <span class="badge rounded-pill bg-primary p-1">Published</span>
                                        @else
                                            <span class="badge rounded-pill bg-danger p-1">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle" style="width: 8rem">
                                        <p class="mb-0 d-flex align-items-center">
                                            <i class="iconoir-user me-1" style="font-size: 1.8rem;"></i> Artist
                                        </p>
                                    </td>
                                    <td class="align-middle fw-bold">{{$model['user']->username}}</td>
                                </tr>
                                <tr>
                                    <td class="align-middle" style="width: 8rem">
                                        <p class="mb-0 d-flex align-items-center">
                                            <i class="iconoir-twitter me-1" style="font-size: 1.8rem;"></i> Twitter
                                        </p>
                                    </td>
                                    <td class="align-middle fw-bold">
                                        <a href="https://x.com/{{$model['user']->twitter_account}}">@{{$model['user']->twitter_account}}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle" style="width: 8rem">
                                        <p class="mb-0 d-flex align-items-center">
                                            <i class="iconoir-instagram me-1" style="font-size: 1.8rem;"></i> Instagram
                                        </p>
                                    </td>
                                    <td class="align-middle fw-bold">
                                        <a href="https://instagram.com/{{$model['user']->instagram}}">@{{$model['user']->instagram}}</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-7 text-center mt-4">
                        <img src="https://api.phaserbeary.xyz/storage/{{ $model['image'] }}"
                            alt="Art Image"
                            class="img-fluid"
                            style="max-width: 100%; height: auto;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        function view_360(id) {
            window.open('{{ route('gedung.view-360') }}/' + id);
        }
    </script>
@endpush
