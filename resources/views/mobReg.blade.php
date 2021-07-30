@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register check api') }}</div>

                    <div class="card-body">
                        <form method="POST" action="/mReg">
                            @csrf

                            <div class="form-group row">
                                <label for="fName" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                                <div class="col-md-6">
                                    <input id="fName" type="text" class="form-control @error('fName') is-invalid @enderror" name="fName" value="{{ old('fName') }}" required autocomplete="fName" autofocus>

                                    @error('fName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="lName" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                                <div class="col-md-6">
                                    <input id="lName" type="text" class="form-control @error('lName') is-invalid @enderror" name="lName" value="{{ old('lName') }}" required autocomplete="lName" autofocus>

                                    @error('lName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{--CNIC--}}
                            <div class="form-group row">
                                <label for="CNIC" class="col-md-4 col-form-label text-md-right">{{ __('CNIC') }}</label>

                                <div class="col-md-6">
                                    <input id="CNIC" type="number" class="form-control @error('CNIC') is-invalid @enderror" name="CNIC" value="{{ old('CNIC') }}" required autocomplete="CNIC">

                                    @error('CNIC')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{--                        contact--}}
                            <div class="form-group row">
                                <label for="contact" class="col-md-4 col-form-label text-md-right">{{ __('Phone number') }}</label>

                                <div class="col-md-6">
                                    <input id="contact" type="tel" class="form-control @error('contact') is-invalid @enderror" name="contact" value="{{ old('contact') }}" required autocomplete="contact">

                                    @error('contact')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{--                        address--}}
                            <div class="form-group row">
                                <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Full Address') }}</label>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required autocomplete="address">

                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{--                        date of birth--}}
                            <div class="form-group row">
                                <label for="date_of_birth" class="col-md-4 col-form-label text-md-right">{{ __('Date of birth') }}</label>

                                <div class="col-md-6">
                                    <input id="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth') }}" required>

                                    @error('date_of_birth')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{--                        email--}}
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button id="btn" type="" class="btn btn-primary" onclick="mobReg()">
                {{ __('Register from api') }}
            </button>
        </div>
    </div>
@endsection

<script>
    const {toJSON} = require("lodash/seq");

    function mobReg()
    {
        //3520277894025
        /*<div class="form-group row mb-0">
    <div class="col-md-6 offset-md-4">
        <button id="btn" type="" class="btn btn-primary" onclick="mobReg()">
            {{ __('Register from api') }}
        </button>
    </div>
</div>
*/
        let fName=document.getElementById('fName').value;
        let lName=document.getElementById('lName').value;
        let CNIC=document.getElementById('CNIC').value;
        let contact=document.getElementById('contact').value;
        let address=document.getElementById('address').value;
        let date_of_birth=document.getElementById('date_of_birth').value;
        let email=document.getElementById('email').value;
        let password=document.getElementById('password').value;
        let password_confirm=document.getElementById('password-confirm').value;
        console.log(fName);
        console.log(lName);
        console.log(contact);
        console.log(address);
        console.log(date_of_birth);
        console.log(password_confirm);
        console.log(CNIC);
        console.log(email);
        console.log(password);
        //return null;
        if(password_confirm!==password)
        {
            return toJSON([error=>'password mismatch']);
        }
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax({
            url: "/mReg",
            type: "post",
            data: {
                fName:fName,
                lName:lName,
                CNIC:CNIC,
                contact:contact,
                address:address,
                date_of_birth:date_of_birth,
                email:email,
                password:password,
            },
            success: function (response) {
                if (response) {
                    let obj = response;
                    console.log(obj);
                }
            }
            ,
            onFailure: function (response)
            {
                if (response) {
                    let obj = response;
                    console.log(obj);
                }
                else
                {console.log('no response');}
            }
        });
    }
</script>
