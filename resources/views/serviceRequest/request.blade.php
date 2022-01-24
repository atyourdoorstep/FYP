@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="card-header text-center">
                                @if ($check??'')
                                    {{ __('Request') }}
                                @else
                                    {{ __('Feedback') }}
                                @endif
                                {{ __('details') }}</div>
                        </h2>
                    </div>
                    <div class="card-body justify-content-center">

                        <form method="POST"
                              @if(!($info ?? ''))
                              action=""
                              @else
                              action=""
                            @endif>
                            @if($info ??'')
                                @method('PATCH')
                            @endif
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-6 col-form-label text-md-right">{{ __('Name') }}
                                    {{': '.ucfirst($data->user->fName).' '.ucfirst($data->user->lName)}}
                                </label>
                            </div>
                            <!--description-->
                            <div class="col-md-12 offset-md-2">
                                 <textarea rows="10" cols="70" readonly
                                           name="description" placeholder="Description max 200 characters." style="outline-width: 2px;outline-color: #1b1e21; border-width: 2px;
border-color: #1d2124;border-radius: 10px">{{ucfirst($description??'')}}
                            </textarea>
                            <textarea rows="6" cols="70"
                                      name="description" placeholder="Description max 200 characters." style="outline-width: 2px;outline-color: #1b1e21; border-width: 2px;
border-color: #1d2124;border-radius: 10px">{{ucfirst($info->description??'')}}
                            </textarea>
                            </div>
                            <!--new-->
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary justify-content-center">
                                            {{ __('Send Response') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
