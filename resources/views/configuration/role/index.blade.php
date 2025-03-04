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
                    @can('Role Create')
                        <div class="text-start ms-2">
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
                            <th>Nama</th>
                            {{-- <th>Status</th> --}}
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
                {{-- @include('configuration.user.update') --}}
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
                    {data: 'name', name: 'roles.name'},
                    // {data: 'created_at', name: 'roles.created_at'},
                    // {data: 'active', searchable : false, orderable: false, class: 'column-wrap text-center'},
                    {data: '_', searchable : false, orderable: false, class: 'text-center nowrap'},
                ]
            });
        })

        // $(document).ready(function() {
        // function edit(id) {
        //     $.ajax({
        //         url: '{{ route('configuration.user.edit') }}/'+id,
        //         success: function(response) {
        //             window.location.href = '{{ route('configuration.user.edit') }}/'+id;
        //         },
        //     })
        // }

        function create() {
            $.ajax({
                url: '{{ route('configuration.role.create') }}',
                success: function(response) {
                    bootbox.dialog({
                        title: 'Buat Role',
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
                url: '{{ route('configuration.role.edit') }}/'+id,
                success: function(response) {
                    bootbox.dialog({
                        title: 'Edit Role',
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
                        $.ajax({
                            url: '{{ route('configuration.role.delete') }}/' + id,
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

        function view(id) {
            $.ajax({
                url: '{{ route('configuration.role.view') }}/'+id,
                success: function(response) {
                    bootbox.dialog({
                        title: 'Detail Role',
                        message: response
                    });
                },
                error: function(response) {
                }
            }).done(function() {
                $('#table').unblock();
            });
        }

        function initializeCheckboxes() {
            $('#selectAll').off('change').on('change', function() {
                const isChecked = this.checked;
                $('.form-check-input').each(function() {
                    $(this).prop('checked', isChecked);
                });
            });

            const totalCheckboxes = $('.form-check-input').not('#selectAll').length;
            const checkedCheckboxes = $('.form-check-input:checked').not('#selectAll').length;
            $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
        }

        function setting(id) {
            $.ajax({
                url: '{{ route('configuration.role.setting') }}/'+id,
                success: function(response) {
                    bootbox.dialog({
                        title: 'Edit Role Permisssion',
                        message: response,
                        size: 'large',
                        onShown: function() {
                            initializeCheckboxes();
                        }
                    });
                },
                error: function(response) {
                }
            }).done(function() {
                $('#table').unblock();
            });
        }
        </script>
@endpush
