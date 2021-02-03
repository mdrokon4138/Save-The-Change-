@extends('crudbooster::admin_template')
@section('content')

@if(!CRUDBooster::isSuperadmin())
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
<div class="container" style="width: 100%;">
    <form action="{{ action('CodeController@subscription') }}" method="POST">
    @csrf
    <div class="row">
        @foreach ($plans as $item)
            <div class="col-xs-12 col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ $item->name}}
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="the-price">
                        <h1>
                            ${{round($item->price)}}<span class="subscript">/Bundle</span></h1>
                    </div>
                    <table class="table">
                        <tr>
                            <td>
                                {{ round($item->signup_fee)}}/ Naira
                            </td>
                        </tr>
                        <tr class="active">
                            <td>
                              {!! $item->description !!}
                            </td>
                        </tr>
                        
                    </table>
                </div>
                <div class="panel-footer">
                    <label style="padding-right: 5px; margin-bottom: 10px;" for=""> Active </label>
                    <input id="plans" onclick="onlyOne(this)"  type="checkbox" name="plans" value="{{ $item->id }}">
                </div>
            </div>
        </div>
        @endforeach
        <input type="hidden" id="id" name="plan_id">
        <div class="col-md-12">
            <button class="btn btn-primary" type="submit">Subscribe Now </button>
        </div>
    </div>
    </form>
</div>

@endsection
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script id="qtScript" type="text/javascript" src="https://sandbox.interswitchng.com/quickteller.js/v1" onload="Quickteller.initialize()"></script>


<script>
function onlyOne(checkbox) {
    var checkboxes = document.getElementsByName('plans')
    checkboxes.forEach((item) => {
        if (item !== checkbox) item.checked = false
        
    });
    var id = checkbox.value;
    // alert(id);
    $('#id').val(id);
}

</script>
@endif