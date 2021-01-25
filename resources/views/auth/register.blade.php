<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register | Save The Change </title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
	<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
</head>
@include('nav')
<body>

<div class="container register">
        <div class="row">
            <div class="col-md-3 register-left">
                <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt=""/>
                <p> Welcome to save the change" we are about to make your daily transaction life easy</p>
                <a class="btn btn-warning" href="{{ url('/admin') }}">Login</a>
            </div>
            <div class="col-md-9 register-right">
                
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h3 class="register-heading">Quick Register </h3>
                        
                        <form method="POST" action="{{ url('user-registration') }}">
                          @csrf
                          <div class="row register-form">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <input type="text" class="form-control" name="first_name" placeholder="First Name *" value="" />
                                  </div>
                                  <div class="form-group">
                                      <input type="text" class="form-control" name="last_name" placeholder="Last Name *" value="" />
                                  </div>
                                  <div class="form-group">
                                      <input type="password" class="form-control" name="password" placeholder="Password *" value="" />
                                  </div>
                                  <div class="form-group">
                                      <input type="password" class="form-control" name="password_confirmation"  placeholder="Confirm Password *" value="" />
                                  </div>
                                  <div class="form-group">
                                      @foreach ($previlage as $item)
                                          
                                      <div class="maxl">
                                          <label class="radio inline"> 
                                              <input type="radio" name="user_type" value="{{ $item->id }}" checked>
                                              <span> {{ $item->name }} </span> 
                                          </label>
                                         
                                      </div>
                                      @endforeach

                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <input type="email" class="form-control" name="email" placeholder="Your Email *" value="" />
                                  </div>
                                  <div class="form-group">
                                      <input type="text" minlength="10" maxlength="10" name="phone" class="form-control" placeholder="Your Phone *" value="" />
                                  </div>
                                  <div class="form-group">
                                      <input type="text" minlength="10" maxlength="10" name="alt_phone" class="form-control" placeholder="Alternative Phone *" value="" />
                                  </div>
                                  <div class="form-group">
                                      <select class="form-control" name="security_questions">
                                          <option class="hidden"  selected disabled>Please select your Sequrity Question</option>
                                          <option>What is your Birthdate?</option>
                                          <option>What is Your old Phone Number</option>
                                          <option>What is your Pet Name?</option>
                                      </select>
                                  </div>
                                  <div class="form-group">
                                      <input type="text" class="form-control" name="question_answer" placeholder="Enter Your Answer *" value="" />
                                  </div>
                                  <input type="hidden" name="ref_code" value="{{$ref_code}}">
                                  <input type="submit" class="btnRegister"  value="Register"/>

                                  
                              </div>
                              <div  class="col-md-12">
                                     @if (count($errors) > 0)
                                        <ul>
                                              @foreach ($errors->all() as $error)
                                                  <p class="text-danger">{{ $error }}</p>
                                              @endforeach
                                            </ul
                                  @endif
                                  </div>
                          </div>
                        </form>
                    </div>
                   
                </div>
            </div>
        </div>

    </div>

@include('footer')
  </body>
</html>