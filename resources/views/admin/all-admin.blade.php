 @extends('layouts.admin')
 @section('title', 'All Admin Users')
 @section('content')
  <!-- Main content -->
    <section class="content" style="padding-top: 20px;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm"></div>
          <div class="col-md-12">

            <div class="card card-info">
              <div class="card-header">
                <div class="row">
                  <div class="col">
                    <h3 class="card-title">All Admin Users</h3>
                  </div>
                  <div class="col text-right">
                    <a href="{{route('admin.reg')}}"><i class="fas fa-plus"></i> Add Admin User</a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div style="overflow: auto;">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Sl</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>User Role</th>
                    <th>Address</th>
                    <th>Photo</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Manage</th>
                  </tr>
                  </thead>
                  <tbody>
                    
                    @php($i=1)
                    @foreach($get_admin as $row)

                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$row->admin_name}}</td>
                        <td>{{$row->admin_email}}</td>
                        <td>{{$row->admin_phone}}</td>
                        <td><span class="badge badge-success">{{$row->user_role == 0 ? "Super Admin": ($row->user_role == 1 ?"Admin": ($row->user_role == 2 ?"Moderator":"Normal User"))}}</span></td>
                        <td>{{$row->admin_address}}</td>
                        <td style="text-align: center;"><a href="{{asset('public/uploads/'.$row->admin_photo)}}" target="_blank"><img src="{{asset('public/uploads/'.$row->admin_photo)}}" class="img-responsive"></a></td>
                        <td><span class="badge {{$row->is_active == 1 ?'badge-info':'badge-danger'}}">{{$row->is_active == 1 ?"Active":"Inactive"}}</span></td>
                        <td>{{$row->created_at}}</td>
                         <td class="text-center">
                          <?php if($row->is_active == 1): ?>
                            <a href="{{url('block-admin/'.$row->admin_no)}}" class="btn btn-danger" id="inactive" style="width: 120px;"><i class="fas fa-lock"></i> Inactivate</a>
                          <?php else: ?>
                            <a href="{{url('active-admin/'.$row->admin_no)}}" class="btn btn-info" id="active"><i class="fas fa-lock-open"></i> Activate</a>
                          <?php endif ?>
                         </td>
                      </tr>
                    @endforeach
                  </tbody>                
                </table>
              </div>
            </div>
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