 @extends('layouts.admin')
 @section('title', 'Create Admin User')
 @section('content')
  <!-- Main content -->
    <section class="content" style="padding-top: 20px;">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-sm"></div>
          <div class="col-md-9">

            <!-- Horizontal Form -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Create Admin User</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="{{route('register.admin')}}" method="Post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                   <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Full Name</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control  @error('admin_name') is-invalid @enderror" name="admin_name" value="{{old('admin_name')}}" placeholder="Full Name">
                      @error('admin_name')
                        <div class="text-danger ">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control  @error('admin_email') is-invalid @enderror" name="admin_email" value="{{old('admin_email')}}" placeholder="Type Email">
                      @error('admin_email')
                        <div class="text-danger ">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Phone (Optional)</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control  @error('admin_phone') is-invalid @enderror" name="admin_phone" value="{{old('admin_phone')}}" placeholder="Type Phone">
                      @error('admin_phone')
                        <div class="text-danger ">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">User Role</label>
                    <div class="col-sm-9">
                      <select class="form-control" name="user_role">
                        <option value="-1" disabled selected>--Select Role--</option>
                          <option value="1">Admin</option>
                          <option value="2">Moderator</option>
                          <option value="2">Normal User</option>
                        </select>
                      @error('user_role')
                        <div class="text-danger ">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Address (Optional)</label>
                    <div class="col-sm-9">
                      <textarea class="form-control  @error('admin_address') is-invalid @enderror" name="admin_address" placeholder="Type Address">{{old('admin_address')}}</textarea>
                      @error('admin_address')
                        <div class="text-danger ">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Password</label>
                    <div class="col-sm-9">
                      <input type="password" class="form-control  @error('password') is-invalid @enderror" name="password" value="{{old('password')}}" placeholder="Type Password">
                      @error('password')
                        <div class="text-danger ">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Confirm Password</label>
                    <div class="col-sm-9">
                      <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="Confirm Password">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Profile Picture</label>
                    <div class="col-sm-9">
                      <input type="file" class="form-control" name="admin_photo">
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <label for="inputEmail3" class="col-sm-3 col-form-label"></label>
                  <button type="submit" class="btn btn-info">Create Admin</button>
                  <a class="btn btn-default" href="{{route('admin.all')}}">Cancel</a>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
            <!-- /.card -->

          </div>
          <div class="col-sm"></div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection