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
<form action="{{ action('TransactionController@bonus_sent_money') }}" method="POST" style="margin-top: 20px; margin-bottom: 20px;">
  @csrf
  <div class="form-group">
    <label for="exampleInputEmail1">Enter Amount</label>
    <input type="text" name="amount" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    <small id="emailHelp" class="form-text text-muted">Transfer amount </small>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Receiver User Secret Code </label>
    <input type="text" name="secret" class="form-control" id="exampleInputPassword1" placeholder="">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>

@endsection 