@extends('crudbooster::admin_template')
@section('content')
 @if (count($errors) > 0)
         <div class = "alert alert-danger">
            <ul>
               @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
      @endif
      @if (session('status'))
        <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status') }}
        </div>
      @endif
      @if (session('warning'))
        <div class="alert alert-warning">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('warning') }}
        </div>
      @endif
<div class="container" style="background-color: white; width: 100%; ">
<form action="{{ action('TransactionController@send_change_code') }}" method="POST" style="margin-top: 20px; margin-bottom: 20px;">
  @csrf
  <div class="form-group">
    <label for="exampleInputEmail1">Enter your naira code</label>
    <input type="text" name="code" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    <small id="emailHelp" class="form-text text-muted">Codes from your generated codes. </small>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>

@endsection 