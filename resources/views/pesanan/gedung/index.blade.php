@extends('template.dashboard')

@push('css')
    @include('template.datatable')
    @include('template.button')
@endpush

@section('content')
<style>
    .dt-button {
        border: none !important;
        background-image: none !important;
        color: inherit !important;
    }
    .btn-excel {
        background-color: #28a745 !important;
        color: white !important;
    }
</style>
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                @include('template.alert')
                <div class="row justify-content-between align-items-center">
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
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: '<i class="fas fa-file-excel me-1"></i> Export Excel',
                        className: 'btn btn-excel',
                        action: function () {
                            window.location.href = '{{ route('pesanan.gedung.download-excel') }}';
                        }
                    }
                ]
            });
        })

        function view(id) {
            window.location.href = '{{ route('pesanan.gedung.view') }}/' + id;
        }

    </script>
@endpush
