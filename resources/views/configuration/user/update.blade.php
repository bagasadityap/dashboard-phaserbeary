<form id="form-validation-2" class="form" action="{{ route('configuration.user.update', ['id' => $user->id]) }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-0" id="card-body">
                    <div class="mb-2">
                        <input type="text" id="id" name="id" value="{{ $user->id }}" hidden>
                        <label for="name" class="form-label">Name</label>
                        <input class="form-control" type="text" id="name" placeholder="Masukkan nama" name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-2">
                        <label for="username" class="form-label">Username</label>
                        <input class="form-control" type="text" id="username" placeholder="Masukkan Username" name="username" value="{{ $user->username }}" required>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control" type="text" id="email" placeholder="Masukkan email" name="email" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-2">
                        <label for="instansi" class="form-label">Instansi</label>
                        <input class="form-control" type="text" id="instansi" placeholder="Masukkan instansi" name="instansi" value="{{ $user->instansi }}" required>
                    </div>
                    <div class="mb-2">
                        <label for="password" class="form-label">Password</label>
                        <input class="form-control" type="password" id="password" placeholder="Masukkan password" name="password">
                    </div>
                    <div class="mb-2">
                        <label for="name" class="form-label">Name</label>
                        <select class="form-control" id="role" name="role" required>
                            <option disabled selected hidden>Pilih role</option>
                            @foreach ($datas as $data)
                                <option value="{{ $data->name }}"
                                    {{ $user->hasRole($data->name) ? 'selected' : '' }}>
                                    {{ $data->name }}
                                </option>
                            @endforeach
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
