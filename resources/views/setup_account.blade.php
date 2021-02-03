<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Setup Account | Save The Change </title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
	<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
</head>
<style>
    .form-check-input{
        margin-left: 0;
    }
</style>
@include('nav')

<body>

<div class="container" style="margin-top: 50px; margin-bottom: 50px; width: 100%;">
    <form action="{{ action('CustomRegisterController@account_setup') }}" method="POST">
    @csrf
    <table class="table" style="border: 1px solid black;">
        <thead>
            <tr>
                <th style="padding-left: 50px; width: 50%;"> 
                    <input class="form-check-input" name="account_type" type="checkbox" id="inlineCheckbox1" value="SVA" />
                    <label style="padding-left: 20px;" class="form-check-label" for="inlineCheckbox1"> Saver Account (SVA)</label><br>
                    <span>A SVA is an account that want to save all the change collected from BOA for a specific period of time before they can withdraw it</span>
                </th>
                <th style="padding-left: 20px;">
                    <input class="form-check-input" name="account_type" type="checkbox" id="inlineCheckbox2" value="SPA" />
                    <label style="padding-left: 20px;" class="form-check-label" for="inlineCheckbox2"> Spender Account (SPA) </label>
                    <br>
                    <span>A SPA is an account that want to be able to spend his change at any point in time</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr >
                <td style="padding-left: 50px;">
                    <label style="width: 100px;" for=""> 6 months</label>
                    <input  class="form-check-input" type="radio" name="saving_time" value="6 months"><br>
                    <label style="width: 100px;" for=""> 1 year</label>
                    <input  class="form-check-input" type="radio" name="saving_time" value="1 year"><br>
                    <label style="width: 100px;" for=""> 2 years</label>
                    <input  class="form-check-input" type="radio" name="saving_time" value="2 years"><br>
                    <input type="hidden" name="user_id" value="{{ $info->id }}">
                    <input style="width: 250px; " class="form-check-input form-control" type="text" name="saving_time_manual" placeholder="Input the number of years." value="">
                </td>
                <td>
                    <button style="margin-top: 100px; width: 200px;" type="submit" class="btn btn-primary">Save</button></div>
                </td>
            </tr>
        </tbody>
    </table>
    </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
$('input[type="checkbox"]').on('change', function() {
   $('input[type="checkbox"]').not(this).prop('checked', false);
});
</script>
@include('footer')

</body>
</html>