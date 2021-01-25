@extends('crudbooster::admin_template')
@section('content')
<div class="container mt-5 mb-5" style="background-color: white; width: 100%;">
<div class="form-group" style=" margin-top: 50px; margin-bottom: 50px;">
    <label for="exampleInputEmail1">Your Referral link </label>
    <input type="text" readonly value="http://save.test/register-ref={{$user->referral}}" class="form-control" id="myInput" aria-describedby="emailHelp">
    <small id="emailHelp" class="form-text text-muted">Share this link to get referral bonus. </small>
   <br>
   <br>
   <button onclick="myFunction()">Copy text</button>
  
</div>
</div>

<script>
function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("myInput");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

  /* Copy the text inside the text field */
  document.execCommand("copy");

  /* Alert the copied text */
  alert("Copied the text: " + copyText.value);
}
</script>
@endsection 

