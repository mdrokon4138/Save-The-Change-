@extends('crudbooster::admin_template')
@section('content')

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                {{-- <h3 class="box-title">{{ __('Bonus Balance') }} ({{$bonus->bonus}})</h3> --}}

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div  class="col-md-6">
                    {{-- <a data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i>{{ __(' Generate Code') }}</a> --}}
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
                                <th>{{ __('Reffered By') }}</th>
                                <th>{{ __('Refferal User') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php ($sl = 1)
                            @foreach($reffers as $code)
                            <tr>
                                <td>{{ $sl++ }}</td>
                                <td>{{ $code->referral_link_id }}</td>
                                <td>{{ $code->uname }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
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