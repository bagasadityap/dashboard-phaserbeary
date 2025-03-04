<form class="row g-3" action="{{ route('configuration.role.update-permission', ['id' => $role->id]) }}" method="POST">
  @csrf
  <div class="col-12">
    <div class="table-responsive">
        <table class="table table-flush-spacing">
            <tbody>
            <tr>
                <td class="text-nowrap fw-medium">Administrator Access</td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll" />
                        <label class="form-check-label" for="selectAll">
                        Select All
                        </label>
                    </div>
                </td>
            </tr>
            @foreach ($permissions as $permission)
                <tr class="table-primary">
                    <td class="text-nowrap fw-medium fs-6"><strong>{{ $permission->name }}</strong></td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="{{ $permission->name }}" id="{{ $permission->name }}" value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} />
                            <label class="form-check-label" for="{{ $permission->name }}"></label>
                        </div>
                    </td>
                </tr>

                @foreach ($permissionsChild as $permissionChild)
                    @if (Str::contains($permissionChild->name, $permission->name))
                        @php
                            $split = explode("-", $permissionChild->name);
                            $permissionChildName = $split[0];
                            $menus = ['Barang', 'User'];
                            $menus2 = ['Carline', 'Conveyor', 'Equipment', 'Role'];
                        @endphp
                        <tr>
                            <td class="text-nowrap fw-medium">{{ $permissionChildName }}</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="{{ $permissionChild->name }}" id="{{ $permissionChild->name }}" value="{{ $permissionChild->name }}" {{ $role->hasPermissionTo($permissionChild->name) ? 'checked' : '' }} />
                                    <label class="form-check-label" for="{{ $permissionChild->name }}"></label>
                                </div>
                            </td>
                        </tr>
                        @if (!Str::contains($permissionChildName, 'Part') && Str::contains($permissionChildName, $menus))
                            <tr>
                                <td class="text-nowrap fw-medium"></td>
                                <td>
                                    <div class="d-flex">
                                        <div class="form-check col-md-2 mr-3 mr-lg-5">
                                            <input class="form-check-input" type="checkbox" name="{{ $permissionChildName . ' Create' }}" id="{{ $permissionChildName . ' Write' }}" value="{{ $permissionChildName . ' Create' }}" {{ $role->hasPermissionTo($permissionChildName . ' Create') ? 'checked' : '' }} />
                                            <label class="form-check-label me-3 mb-1" for="{{ $permissionChildName . ' Write' }}">Create</label>
                                        </div>
                                        <div class="form-check col-md-2 mr-3 mr-lg-5">
                                            <input class="form-check-input" type="checkbox" name="{{ $permissionChildName . ' Edit' }}" id="{{ $permissionChildName . ' Edit' }}" value="{{ $permissionChildName . ' Edit' }}" {{ $role->hasPermissionTo($permissionChildName . ' Edit') ? 'checked' : '' }} />
                                            <label class="form-check-label me-3 mb-1" for="{{ $permissionChildName . ' Edit' }}">Update</label>
                                        </div>
                                        <div class="form-check col-md-2">
                                            <input class="form-check-input" type="checkbox" name="{{ $permissionChildName . ' Delete' }}" id="{{ $permissionChildName . ' Delete' }}" value="{{ $permissionChildName . ' Delete' }}" {{ $role->hasPermissionTo($permissionChildName . ' Delete') ? 'checked' : '' }}/>
                                            <label class="form-check-label me-3 mb-1" for="{{ $permissionChildName . ' Delete' }}">Delete</label>
                                        </div>
                                        <div class="form-check col-md-2 mr-3 mr-lg-5">
                                            <input class="form-check-input" type="checkbox" name="{{ $permissionChildName . ' Read' }}" id="{{ $permissionChildName . ' Read' }}" value="{{ $permissionChildName . ' Read' }}" {{ $role->hasPermissionTo($permissionChildName . ' Read') ? 'checked' : '' }} />
                                            <label class="form-check-label me-3 mb-1" for="{{ $permissionChildName . ' Read' }}">Read</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        @if (!Str::contains($permissionChildName, 'Part') && Str::contains($permissionChildName, $menus2))
                            <tr>
                                <td class="text-nowrap fw-medium"></td>
                                <td>
                                    <div class="d-flex">
                                        <div class="form-check col-md-2 mr-3 mr-lg-5">
                                            <input class="form-check-input" type="checkbox" name="{{ $permissionChildName . ' Create' }}" id="{{ $permissionChildName . ' Write' }}" value="{{ $permissionChildName . ' Create' }}" {{ $role->hasPermissionTo($permissionChildName . ' Create') ? 'checked' : '' }} />
                                            <label class="form-check-label me-3 mb-1" for="{{ $permissionChildName . ' Write' }}">Create</label>
                                        </div>
                                        <div class="form-check col-md-2 mr-3 mr-lg-5">
                                            <input class="form-check-input" type="checkbox" name="{{ $permissionChildName . ' Edit' }}" id="{{ $permissionChildName . ' Edit' }}" value="{{ $permissionChildName . ' Edit' }}" {{ $role->hasPermissionTo($permissionChildName . ' Edit') ? 'checked' : '' }} />
                                            <label class="form-check-label me-3 mb-1" for="{{ $permissionChildName . ' Edit' }}">Update</label>
                                        </div>
                                        <div class="form-check col-md-2">
                                            <input class="form-check-input" type="checkbox" name="{{ $permissionChildName . ' Delete' }}" id="{{ $permissionChildName . ' Delete' }}" value="{{ $permissionChildName . ' Delete' }}" {{ $role->hasPermissionTo($permissionChildName . ' Delete') ? 'checked' : '' }}/>
                                            <label class="form-check-label me-3 mb-1" for="{{ $permissionChildName . ' Delete' }}">Delete</label>
                                        </div>
                                        @if (Str::contains($permissionChildName, 'Role'))
                                            <div class="form-check col-md-2 mr-3 mr-lg-5">
                                                <input class="form-check-input" type="checkbox" name="{{ $permissionChildName . ' Setting' }}" id="{{ $permissionChildName . ' Setting' }}" value="{{ $permissionChildName . ' Setting' }}" {{ $role->hasPermissionTo($permissionChildName . ' Setting') ? 'checked' : '' }} />
                                                <label class="form-check-label me-3 mb-1" for="{{ $permissionChildName . ' Setting' }}">Setting</label>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endif
                @endforeach
            @endforeach
        </tbody>
      </table>
    </div>
    <!-- Permission table -->
  </div>
  <div class="col-12 text-center">
    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
    <button type="reset" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
  </div>
</form>
