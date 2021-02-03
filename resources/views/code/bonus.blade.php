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
                <form method="POST" action="{{ action('CodeController@generate_bonus_code') }}">
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
        @if(session()->has('warning'))
            <div class="alert alert-warning">
                {{ session()->get('warning') }}
            </div>
        @endif
        <div class="box">
            <div class="box-header with-border">
                {{-- <h3 class="box-title">{{ __('Bonus Balance') }} ({{$bonus->bonus}})</h3> --}}

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                @if($user_info->status != 0)
                <div  class="col-md-6">
                    {{-- <a data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i>{{ __(' Generate Code') }}</a> --}}
                </div>
                @endif 
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
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Created At') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php ($sl = 1)
                            @foreach($codes as $code)
                            <tr>
                                <td>{{ $sl++ }}</td>
                                <td>{{ $code->code }}</td>
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
                               
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a   onclick="printDiv('printMe')" class="btn btn-info" href="http://">Print </a>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <div style="display: none;"  id="printMe">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($codes as $code)
                    <tr>
                       <td>{{ $code->code }}</td>
                       <td>{{ $code->am }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
<script>
		function printDiv(divName){
			var printContents = document.getElementById(divName).innerHTML;
			// var originalContents = document.body.innerHTML;

			document.body.innerHTML = printContents;

			window.print();

			// document.body.innerHTML = originalContents;

		}
	</script>
@endsection