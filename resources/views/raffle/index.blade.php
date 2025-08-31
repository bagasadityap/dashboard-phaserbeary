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
                <div class="row align-items-center">
                    @can('User Create')
                        <div class="text-start ms-2">
                            <button class="btn btn-outline-primary px-2 d-inline-flex align-items-center nowrap" onclick="create()"><i class="iconoir-plus fs-14 me-1"></i>Create</button>
                        </div>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table datatable" id="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Created At</th>
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
                    {data: 'title', name: 'title'},
                    {data: 'description', name: 'description'},
                    {data: 'end_date', name: 'end_date'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: '_', searchable : false, orderable: false, class: 'text-center nowrap'},
                ]
            });
        })

        function create() {
            $.ajax({
                url: '{{ route('raffle.create') }}',
                success: function(response) {
                    bootbox.dialog({
                        title: 'Add Raflle',
                        message: response
                    });
                },
                error: function(response) {
                }
            }).done(function() {
                $('#table').unblock();
            });
        }

        function edit(id) {
            $.ajax({
                url: '{{ route('raffle.edit') }}/'+id,
                success: function(response) {
                    bootbox.dialog({
                        title: 'Edit Raffle',
                        message: response
                    });
                },
                error: function(response) {
                }
            }).done(function() {
                $('#table').unblock();
            });
        }

        function view(id) {
            $.ajax({
                url: '{{ route('raffle.view') }}/'+id,
                success: function(response) {
                    bootbox.dialog({
                        title: 'Detail Raffle',
                        message: response
                    });
                },
                error: function(response) {
                }
            }).done(function() {
                $('#table').unblock();
            });
        }

        function remove(id) {
            bootbox.confirm({
                title: '<span class="text-danger">Perhatian!</span>',
                message: 'Apakah anda yakin menghapus data ini?',
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-danger'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-secondary'
                    }
                },
                callback: function(result) {
                    if (result) {
                        window.location.href = '{{ route('raffle.delete') }}/' + id;
                    }
                }
            });
        }
    </script>
@endpush
