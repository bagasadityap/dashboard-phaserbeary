@extends('template.home')

@push('css2')
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/block-ui.js') }}"></script>
    @include('template.datatable')
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="ms-3 mt-3 mb-2">
                <h2>Pilih Gedung</h2>
                <p class="text-danger">*Harga yang tertera belum termasuk PPN 10%</p>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table datatable" id="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Lokasi</th>
                            <th>Kapasitas</th>
                            <th>Harga</th>
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
                    {data: 'nama', name: 'nama'},
                    {data: 'lokasi', name: 'lokasi'},
                    {data: 'kapasitas', name: 'kapasitas'},
                    {data: 'harga', name: 'harga'},
                    {data: '_', searchable : false, orderable: false, class: 'text-center dt-nowrap'},
                ],
                    error: function(xhr, error, code) {
                    console.log(xhr.responseText);
                }
            });
        })

        function pilih(id, id2) {
            bootbox.confirm({
                title: '<span class="text-light">Pilih Gedung?</span>',
                message: 'Gedung yang sudah dipilih tidak dapat diubah.',
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-secondary'
                    }
                },
                callback: function(result) {
                    if (result) {
                        $.ajax({
                            url: '{{ route('home.pilih') }}/' + id + '/' + id2,
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                window.location.href = '{{ route('home.detail-pesanan') }}/' + id;
                                bootbox.hideAll();
                            },
                            error: function() {
                                toastr.error('Terjadi kesalahan.');
                            }
                        });
                    }
                }
            });
        }

        function view(id, role) {
            window.location.href = '{{ route('gedung.view') }}/' + id;
        }

    </script>
    <script src="{{ asset('js/bootbox.min.js') }}"></script>
@endpush
