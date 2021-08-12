 @extends('layouts.admin')
 @section('title', 'Update Category')
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
                <h3 class="card-title">Update Category</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="{{url('category/update/'.$category->category_no)}}" method="Post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                   <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Category Name</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control  @error('category_name') is-invalid @enderror" name="category_name" value="{{$category->category_name}}">
                      @error('category_name')
                        <div class="text-danger ">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Category Slug</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control  @error('category_slug') is-invalid @enderror" name="category_slug" value="{{$category->category_slug}}">
                      @error('category_slug')
                        <div class="text-danger ">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Category Image</label>
                    <div class="col-sm-9">
                      <input type="file" class="form-control" name="category_image">
                      <img src="{{asset('public/uploads/'.$category->category_image)}}" style="height: 150px;width: 150px;padding: 20px;">
                    </div>
                    <div>
                      <input type="hidden" name="category_image" value="{{$category->category_image}}" />
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <label for="inputEmail3" class="col-sm-3 col-form-label"></label>
                  <button type="submit" class="btn btn-info">Update Category</button>
                  <a class="btn btn-default" href="{{route('category.all')}}">Cancel</a>
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