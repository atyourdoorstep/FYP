@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div id ="" class="card-header">
                        <input id="search" type="text" class="form-control" name="search" onkeyup="search(this)" onchange="search(this)">
                    </div>
                    <div id ="csrf_name" class="card-header">{{ __('') }}</div>
                    <div class="card-body">
{{--                        <form method="post" enctype="multipart/form-data" action="/test">--}}
{{--                            @csrf--}}

{{--                            <strong><label class="d-inline" for="image"--}}
{{--                                           class="col-md-4 col-form-label text-md-right">{{ __('Add an Image') }}</label></strong>--}}
{{--                            <input class="d-inline" type="file" accept="image/*"--}}
{{--                                   @if($info??'')--}}
{{--                                   value="/storage/{{($info->image??'')}}"--}}
{{--                                   @endif--}}
{{--                                   class="form-control-file" id="image" name="image">--}}
{{--                            @error('image')--}}
{{--                            <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                            @enderror--}}

{{--                        </form>--}}
                        @foreach(\App\Models\ServiceRequest::all() as $req)
                            <strong><label class="d-inline" for="" class="col-md-4 col-form-label text-md-right">{{ __(\App\Models\User::find($req->user_id)->fName) }}</label></strong>
                            <div>
                                <input id="email" type="text"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') ??__(\App\Models\User::find($req->user_id)->email) ??''}}" required
                                       disabled autofocus>
                            </div>
                    </div>
                    <!--description-->
                    <div class="col-md-12 offset-md-2">
                            <textarea rows="4" cols="50"
                                      name="description" placeholder="" style="outline-width: 2px;outline-color: #1b1e21; border-width: 2px;
border-color: #1d2124;border-radius: 10px">{{ucfirst(__(\Storage::disk('google')->get($req->path))??'')}}</textarea>
                    </div>

                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                {{--new--}}

@endsection
<script>
    function search(tag)
    {
        console.log( tag.value);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax({
            url: "/api/searchSeller",
            type: "get",
            data: {
                'search':tag.value,
                },
            success: function (response) {
                if (response) {
                    let obj = response;
                    console.log(obj);
                }
            }
            ,
        });
    }
function mlog()
    {
        let token=document.getElementById('token').innerText;
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax({
            url: "/mlog",
            type: "post",
            data: {token:token},
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
                document.getElementById('token').innerText=('meta[name="csrf-token"]').attr('content');
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
        url: "/mobileLogOut",
        type: "post",
        data: {token:token},
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
