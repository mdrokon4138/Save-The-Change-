@extends('crudbooster::admin_template')
@section('content')

<div class="container" style="width: 100%; background-color: white;">

<form action="{{ url('users-withdraw-request')}}" method="POST">
@csrf
<div class="row">
  <div class="col-md-6">
    <h2 class="text-left">Withdraw Bank Information </h2>
    <div class="form-group">
        <label for="exampleFormControlInput1">Bank Name</label>
        <input type="text" class="form-control" name="bank_name" id="exampleFormControlInput1" placeholder="Bank Name">
    </div>
    <div class="form-group">
        <label for="exampleFormControlInput1">Account Number </label>
        <input type="text" class="form-control" name="account_number" id="exampleFormControlInput1" placeholder="Account Number">
    </div>
    <div class="form-group">
        <label for="exampleFormControlInput1">Amount </label>
        <input type="text" class="form-control" name="amount" id="exampleFormControlInput1" placeholder="Amount">
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Note</label>
        <textarea class="form-control" name="note" id="exampleFormControlTextarea1" rows="3"></textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success">Submit</button>
    </div>
  </div>
  <div class="col-md-6" style="margin-top: 100px;">
    @if($errors->any())
    {!! implode('', $errors->all('<div class="text-danger">:message</div>')) !!}
    @endif

    @if(session()->has('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div>
    @endif
  </div>
</div>
</form>
</div>

@endsection 