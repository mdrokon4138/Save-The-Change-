@extends('crudbooster::admin_template')
@section('content')

<div class="container" style="width: 100%; background-color: white;">

<form action="{{ url('users-withdraw-request')}}" method="POST" id="withdraw_form">
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
        <label for="exampleFormControlInput1">Main Balance </label>
        <input type="text" disabled class="form-control"  value="{{$balance->balance}}" placeholder="Amount">
    </div>

    <div class="form-group">
        <label for="exampleFormControlInput1">Amount </label>
        <input type="text" class="form-control" name="amount" id="withdraw" value="" placeholder="Amount">
    </div>
    <small class="text-danger">5% of the money shall be deducted from your account.</small>
    <br>
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
    @if(session()->has('warning'))
        <div class="alert alert-danger">
            {{ session()->get('warning') }}
        </div>
    @endif
  </div>
</div>
</form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>
function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}

$('#withdraw').keyup(delay(function (e) {
    var basic_salary = $('#withdraw').val();
    var balance = "{{ $balance->balance }}";
    if (Number(basic_salary) > Number(balance)) {
        alert('your balance is '+ balance);
        var basic_salary = $('#withdraw').val(balance);
    }
    var defualt_percent = "{{ $percentage->amount }}";
    var default_providents = (Number(defualt_percent)/100)*basic_salary;
    var total = Number(basic_salary) - Number(default_providents);
    $('#withdraw').val(total);
}, 3000));
   
</script>
@endsection 