@extends('crudbooster::admin_template')
@section('content')
<style type="text/css">
    table.sample tfoot
    {
       border-top: 2px solid black;
    }
    .dataTables_length {
    text-align: right;
    display:inline-block;
    vertical-align: top;
    width: 100%;
}

</style>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>

<div class="col-md-12" style="background-color: white;">
   <h3 class="text-center">{{ __('User Bonus Sent') }}</h3>
@if(session()->has('msg'))
    <div class="alert alert-success">
        {{ session()->get('msg') }}
    </div>
@endif

    <br>
    <div class="col-md-12">
    <form action="{{ url('sent-bonus') }}" method="POST">
    @csrf
    <table  class="table table-bordered sample" id="dataTable" style="margin-bottom: 100px; width:100%;">
     <thead>
    <tr>
        <th><input type="checkbox" id="checkAll"> Check All </th>
        <th>{{ __('sl#') }}</th>
        <th>{{ __('Name') }}</th>
        <th>{{ __('Phone') }}</th>
        <th>{{ __('Alternative Phone') }}</th>
        <th>{{ __('User Type') }}</th>
    </tr>
  </thead>
  <tbody>
     @php
     $sl = 1; 
     @endphp
      @foreach($users as $data)
      <tr>
        <td><input type="checkbox" name="users[]" value="{{$data->user_id }}"></td>
        <td>{{ $sl++ }}</td>
        <td >{{ $data->first_name }} {{ $data->last_name }}</td>
        <td> {{ $data->phone}}</td>
        <td> {{ $data->alt_phone}}</td>
        <td>
          {{ $data->user_type }}
        </td>        
      </tr>
     
      @endforeach
  </tbody>
  </table>
  <br>
  <div class="col-md-6">
      @if($errors->any())
            {!! implode('', $errors->all('<div class="text-danger">:message</div>')) !!}
        @endif
      <label for="">Bonus Amount</label>
    <input type="text" placeholder="Enter bonus amount" name="bonus" id="foo" class="form-control">
     <input  type="hidden" name="bonus_amount" id="debug">
    <br>
    <br>
    <button class="btn btn-info" type="submit">Make Payment </button>
  </div>
  </form>
</div>


  </div>
  <script type="text/javascript">
        $(document).ready(function() {
            $('#foo').keyup(function(e) {
                var v = $('#foo').val();
                $('#debug').val(v);
            })
        });
    </script>

<script>

var table = $('#dataTable').DataTable( {
    dom: 'Bfrtip',
    buttons: [
            {
                extend: 'print',
                title: '',
                customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            ''
                        );
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                }
            },
            {
              extend: 'pdf',
              title: '',
              
            },
            {
              extend: 'excel',
              title: '',
            }
        ]
   
} );

 $("#checkAll").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
</script>
@endsection