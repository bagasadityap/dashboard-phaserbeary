<form action="{{ route('art-gallery.arts.confirm', ['id' => $model['id']]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row" style="margin-bottom: -2rem">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-0">
                    <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="0" {{ $model['status'] == 0 ? 'selected' : '' }}>Need to Review</option>
                        <option value="1" {{ $model['status'] == 1 ? 'selected' : '' }}>Published</option>
                        <option value="2" {{ $model['status'] == 2 ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control" id="comment" name="comment" required>{{ $model['comment'] }}</textarea>
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
