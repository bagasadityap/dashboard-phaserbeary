@extends('template.dashboard')

@push('css')
    @include('template.datatable')
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                @include('template.alert')
                <div class="row justify-content-between align-items-center">
                    {{-- @can('Gedung  Create')
                        <div class="col-md-2 text-start ms-2">
                            <button class="btn btn-outline-primary px-2 d-inline-flex align-items-center nowrap" onclick="create()"><i class="iconoir-plus fs-14 me-1"></i>Tambah</button>
                        </div>
                    @endcan --}}
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table datatable" id="table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Customer</th>
                            <th>Tanggal Dibuat</th>
                            <th>Tanggal Acara</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        var dataTable;
        $(function() {
            dataTable = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '',
                columns: [
                    {data: 'judul', name: 'judul'},
                    {data: 'user.name', name: 'user.name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'tanggal', name: 'tanggal'},
                    {data: '_', searchable : false, orderable: false, class: 'text-center dt-nowrap'},
                ]
            });
        })

        function view(id) {
            window.location.href = '{{ route('pesanan.gedung.view') }}/' + id;
        }

    </script>
@endpush
