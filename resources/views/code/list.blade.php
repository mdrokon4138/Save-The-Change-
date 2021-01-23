@extends('crudbooster::admin_template')
@section('content')
<!-- Modal HTML Markup -->
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
    <section class="content">
        <!-- Default box -->
        @if(session()->has('msg'))
            <div class="alert alert-success">
                {{ session()->get('msg') }}
            </div>
        @endif
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ __('Manage Codes') }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div  class="col-md-6">
                    <a data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i>{{ __(' Generate Code') }}</a>
                </div>
                <div  class="col-md-6">
                    <input type="text" id="myInput" class="form-control" placeholder="{{ __('Search..') }}">
                </div>
                <!-- Notification Box -->
                
                <!-- /.Notification Box -->
                <div  class="col-md-12 table-responsive">
                    <table  class="table table-bordered table-striped">
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
                        <tbody id="myTable">
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
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
    <script src="https://code.jquery.com/jquery-3.5.1.js">
    
</script>
<script>
$(document).ready(function(){
    $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
    });
});
</script>
<script type="text/javascript">
    function printDiv(printable_area) {
     var printContents = document.getElementById(printable_area).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
 }
</script>

@endsection