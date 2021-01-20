@extends('crudbooster::admin_template')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap.min.css">
<div class="container" style="width: 100%;">
<div class="card">

<div class="col-md-12" style="background-color: white;">
    @php
        $i = 1;
    @endphp
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th>SL.No.</th>
                <th>Plans</th>
                <th>Created At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user_plan as $item)
                
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $item->name }}</td>
                <td>{{  date('d-M-Y',strtotime($item->created_at))  }}</td>
                @if($item->stripe_status == 1)
                <td><h5 style="color: green;">Active </h5></td>
                @else 
                <td><h5 style="color: red;">Deactivated</h5></td>
                @endif 
            </tr>
            @endforeach
           
        </tbody>
    </table>
  </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
@endsection