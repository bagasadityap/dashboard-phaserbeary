<form id="form-validation-2" class="form" action="{{ route('raffle.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row" style="margin-bottom: -2rem">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-0">
                    <div class="mb-2">
                        <label for="title" class="form-label">Title</label>
                        <input class="form-control" type="text" id="title" name="title" required>
                    </div>
                    <div class="mb-2">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label for="image" class="form-label">Image</label>
                        <input class="form-control" type="file" id="image" name="image" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date & Time</label>
                        <input
                        type="datetime-local"
                        class="form-control"
                        id="end_date"
                        name="end_date"
                        value="{{ old('end_date') }}"
                        required
                        >
                        <label class="form-label text-danger small">*Input time uses WIB (UTC+7). Please subtract 7 hours; system stores it in UTC.</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
    </div>
</form>
