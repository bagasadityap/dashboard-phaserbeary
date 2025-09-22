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
                    @can('Gedung Create')
                        <div class="col-md-2 text-start ms-2">
                            <button class="btn btn-outline-primary px-2 d-inline-flex align-items-center nowrap" onclick="create()"><i class="iconoir-plus fs-14 me-1"></i>Tambah</button>
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
                            <th>Artist</th>
                            <th>Twitter</th>
                            <th>Description</th>
                            <th>Status</th>
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
                    {data: 'user.username', name: 'user.username'},
                    {data: 'user.twitter_account', name: 'user.twitter_account'},
                    {data: 'description', name: 'description'},
                    {data: 'status', name: 'status'},
                    {data: '_', searchable : false, orderable: false, class: 'text-center dt-nowrap'},
                ]
            });
        })

        function remove(id) {
            bootbox.confirm({
                title: '<span class="text-danger">Warning!</span>',
                message: 'Are you sure you want to delete this data? This action cannot be undone.',
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
                        $.ajax({
                            url: '{{ route('gedung.delete') }}/' + id,
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                window.location.reload();
                                bootbox.hideAll();
                            },
                            error: function() {
                                toastr.error('An error occurred while deleting the data.');
                            }
                        });
                    }
                }
            });
        }

        function confirm(id) {
            $.ajax({
                url: 'arts/confirm-dialog/' + id,
                success: function(response) {
                    bootbox.dialog({
                        title: 'Confirm Art',
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
            window.location.href = "{{ url('art-gallery/arts/view') }}/" + id;
        }

    </script>
@endpush
