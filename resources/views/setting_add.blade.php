<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@section('content')
<div class="container" style="background-color: white; width: 100%;">
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
</div>
@endif

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ url('save-settings') }}" method="POST" enctype="multipart/form-data">
@csrf
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail4">logo</label>
      <input type="file" name="logo" value="{{ $old->logo }}" class="form-control" id="inputEmail4" placeholder="logo">
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword4">Banner</label>
      <input type="file" name="banner" value="{{ $old->banner }}" class="form-control" id="inputPassword4" placeholder="banner">
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-3">
      <label for="inputCity">Phone</label>
      <input type="text" name="phone" value="{{ $old->phone }}" class="form-control" placeholder="Enter Phone " id="inputCity">
    </div>
    <div class="form-group col-md-4">
      <label for="inputState">Email</label>
      <input type="email" name="email" value="{{ $old->email }}" class="form-control" placeholder="Enter Email">
    </div>
    <div class="form-group col-md-5">
      <label for="inputZip">Address</label>
      <input type="text" class="form-control" value="{{ $old->address }}" name="address" placeholder="Enter Address" id="inputZip">
    </div>
    <div class="form-group col-md-2">
        <button type="submit" class="btn btn-primary">Save Setting</button>
    </div>
  </div>
</form>
</div>
@endsection