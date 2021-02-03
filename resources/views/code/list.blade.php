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
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<!-- Modal HTML Markup -->
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
      @if (session('msg'))
        <div class="alert alert-warning">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('msg') }}
        </div>
    @endif
<div id="exampleModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title">Code Generate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            
            <div class="modal-body">
                <form method="POST" action="{{ action('CodeController@generate_code') }}">
                    @csrf
                    <div class="form-group">
                        <div>
                            <input type="hidden" class="form-control input-lg" name="plan_id" value="{{$plan->pid}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Generate Code For </label>
                        <div>
                            <input type="text" class="form-control input-lg" name="code_for">
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn btn-info btn-block">Generate Code </button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="col-md-12" style="background-color: white;">
   <div class="box-header with-border">
                 @if($user_info->status != 0)
                <div  class="col-md-6">
                    <a data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i>{{ __(' Generate Code') }}</a>
                </div>
                @endif 

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>

    <br>
    <div class="col-md-12">
    <table  class="table table-bordered sample text-center" id="dataTable" style="margin-bottom: 100px; width:100%;">
     <thead>
    <tr>
        <th>{{ __('SL#') }}</th>
        <th>{{ __('Codes') }}</th>
        <th>{{ __('Created By') }}</th>
        <th>{{ __('Plan Name') }}</th>
        <th>{{ __('Amount') }}</th>
        <th>{{ __('Status') }}</th>
        <th>{{ __('Created At') }}</th>
        <th class="text-center">{{ __('Actions') }}</th>
    </tr>
  </thead>
  <tbody>
    @php ($sl = 1)
        @foreach($codes as $code)
        <tr>
            <td>{{ $sl++ }}</td>
            <td>{{ $code->code }}</td>
            <td>{{ $code->first_name }} {{ $code->last_name }}</td>
            <td>{{ $code->name }}</td>
            <td>{{ $code->am }}</td>

            @if($code->status == 0) 
            <td>
                <p class="text-warning">Inactive</p>
            </td>
            @endif 
            
            @if($code->status == 1) 
            <td>
                <p class="text-success">Active</p>
            </td>
            @endif 
            
            <td>{{ date("d F Y", strtotime($code->created_at)) }}</td>
            <td class="text-center">
                @if($code->status == '1')
                <a onclick="return confirm('Are you sure you want to inactive this item?');"  href="{{ url('/codes/inactive/' . $code->cid) }}"><i class="icon fa fa-undo text-danger"></i> {{ __('InActive') }}</a>
                @else 
                <a onclick="return confirm('Are you sure you want to active this item?');"  href="{{ url('/codes/active/' . $code->cid) }}"><i class="icon fa fa-check text-danger"></i> {{ __('Active') }}</a>
                @endif 
                
            </td>
        </tr>
        @endforeach
  </tbody>
  </table>
</div>


  </div>

<script>

var table = $('#dataTable').DataTable( {
    dom: 'Bfrtip',
    buttons: [
            {
                extend: 'print',
                text: 'Print all',
                exportOptions: {
                    modifier: {
                        selected: null
                    }
                }
            },
            {
                extend: 'print',
                text: 'Print selected'
            }
        ],
        "iDisplayLength": 15,
        select: true
        
} );

</script>
@endsection