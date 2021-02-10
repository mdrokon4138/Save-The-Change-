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
<form action="{{ action('ChartController@switch') }}" method="POST" style="margin-top: 20px; margin-bottom: 20px;">
  @csrf
    @if ($user->user_type == 'RA')
      
    <label class="checkbox-inline">
      <input type="checkbox" name="swtich" value="BOA">Swtich To Business Account
    </label>
      @else
    <label class="checkbox-inline">
      <input type="checkbox" name="swtich" value="RA">Swtich To Regular Account
    </label>
    @endif 
    <br>
    <br>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>

@endsection 