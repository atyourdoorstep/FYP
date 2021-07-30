@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div id ="token" class="card-header">{{ __('Login') }}</div>
                    <div id ="csrf_name" class="card-header">{{ __('') }}</div>
                    <div class="card-body">
                        <form method="POST" action="/mobileLogin">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

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
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" >
                                        {{ __('Login') }}
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
        <div class="col-md-8 offset-md-4">
            <button type="" class="btn btn-primary" onclick="func()">
                {{ __('Check') }}
            </button>
        </div>
    </div>
    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <button type="" class="btn btn-primary" onclick="lout()">
                {{ __('log out') }}
            </button>
        </div>
    </div>
    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <button type="" class="btn btn-primary" onclick="mlog()">
                {{ __('get Name') }}
            </button>
        </div>
    </div>
{{--new--}}

@endsection
<script>
function mlog()
    {
        let token=document.getElementById('token').innerText;
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax({
            url: "/mlog",
            type: "post",
            data: {_token:token},
            success: function (response) {
                if (response) {
                    let obj = response;
                    console.log(obj);
                    document.getElementById('csrf_name').innerText=obj['fName']+' '+obj['lName'];
                }
            }
            ,
        });
    }
function func()
{
    //mlog
    let mail=document.getElementById('email').value;
    let pw=document.getElementById('password').value;
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $.ajax({
        url: "/mobileLogin",
        type: "post",
        data: {email: mail,
            password:pw},
        success: function (response) {
            if (response) {
                let obj = response;
                console.log(obj);
                document.getElementById('token').innerText=response['access_token'];
            }
        }
        ,
    });
}
function lout()
{
    let token=document.getElementById('token').innerText;
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $.ajax({
        url: "/logout",
        type: "post",
        data: {_token:token},
        success: function (response) {
            if (response) {
                let obj = response;
                console.log(obj);
            }
        }
        ,
    });
}
</script>
