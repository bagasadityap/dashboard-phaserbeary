<form action="{{ route('raffle.update', ['id' => $model['id']]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row" style="margin-bottom: -2rem">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-0">
                    <div class="mb-2">
                        <label for="title" class="form-label">Title</label>
                        <input class="form-control" type="text" id="title" name="title" value="{{ $model['title'] }}" required>
                    </div>
                    <div class="mb-2">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required>{{ $model['description'] }}</textarea>
                    </div>
                    <div class="mb-2">
                        <label for="image" class="form-label">Image</label>
                        <input class="form-control" type="file" id="image" name="image">
                        <label class="form-label text-danger small">*Leave this field empty if you do not want to change the current image</label>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date & Time</label>
                        <input
                            type="datetime-local"
                            class="form-control"
                            id="end_date"
                            name="end_date"
                            value="{{ \Carbon\Carbon::parse($model['end_date'])->setTimezone('UTC')->format('Y-m-d\TH:i') }}"
                            required
                        >
                        <label class="form-label text-danger small">*Input time uses WIB (UTC+7). Please subtract 7 hours; system stores it in UTC.</label>
                    </div>
                    <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="0" {{ $model['status'] == 0 ? 'selected' : '' }}>Draft</option>
                        <option value="1" {{ $model['status'] == 1 ? 'selected' : '' }}>Published</option>
                        <option value="2" {{ $model['status'] == 2 ? 'selected' : '' }}>Completed</option>
                        <option value="3" {{ $model['status'] == 3 ? 'selected' : '' }}>Closed</option>
                        <option value="4" {{ $model['status'] == 4 ? 'selected' : '' }}>Cancelled</option>
                    </select>
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
