 @extends('layouts.admin')
 @section('title', 'Update Subcategory')
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
                <h3 class="card-title">Update Subcategory</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="{{url('area/update/'.$area->area_no)}}" method="Post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">City</label>
                    <div class="col-sm-9">
                      <select class="form-control" name="city_no">
                        <option value="-1" disabled selected>--Select City--</option>
                        @foreach($get_city as $data)
                          <option value="{{$data->city_no}}" <?php if ($area->city_no==$data->city_no) echo "selected";?>>{{$data->city_name}}</option>
                        @endforeach
                      </select>
                      @error('city_no')
                        <div class="text-danger ">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                   <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Area Name</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control  @error('area_name') is-invalid @enderror" name="area_name" value="{{$area->area_name}}">
                      @error('area_name')
                        <div class="text-danger ">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Area Slug</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control  @error('area_slug') is-invalid @enderror" name="area_slug" value="{{$area->area_slug}}">
                      @error('area_slug')
                        <div class="text-danger ">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <label for="inputEmail3" class="col-sm-3 col-form-label"></label>
                  <button type="submit" class="btn btn-info">Update Area</button>
                  <a class="btn btn-default" href="{{route('area.all')}}">Cancel</a>
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