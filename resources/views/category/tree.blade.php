@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>
                            @if($info ?? '')
                                <div class="card-header text-center">{{ __('Edit category') }}</div>
                            @else
                                <div class="card-header text-center">{{ __('Add category') }}</div>
                            @endif
                        </h2>
                    </div>
                    <div class="card-body justify-content-center">
                        <form method="POST"
                              @if(!($info ?? ''))
                              action="{{ route('/regCategory') }}"
                              @else
                              action="/Cat/{{$info->id}}"
                            @endif>
                            @if($info ??'')
                                @method('PATCH')
                            @endif
                            @csrf
                            <div class="d-inline-flex">
                                <div class="form-group row">
                                    <label for="name"
                                           class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                    <div class="col-md-8">
                                        <input id="name" type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name') ??$info->name??''}}" required
                                               autocomplete="name" autofocus>

                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--description-->
                                <!--new-->
                                <div class="">
                                    <div class="col-md-12 offset-md-2">
                                        <div class="">
                                            <label for="category_id">Select parent category:</label>
                                            <select id="category_id" name="category_id">
                                                <option value="">NONE</option>
                                                @foreach($data as $cat)
                                                    <option value={{$cat->id}}
                                                    @if($info??'')
                                                    @if($cat->id==$info->category_id??'')
                                                        selected='selected'
                                                        @endif
                                                        @endif>{{ucfirst($cat->name)}}</option>
                                                    @foreach($cat->children as $subCat)
                                                        <option value={{$subCat->id}}
                                                        @if($info??'')
                                                        @if($subCat->id==$info->category_id)
                                                            selected='selected'
                                                            @endif
                                                            @endif>--{{ucfirst($subCat->name)}}</option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        {{--                            <div class="card-body justify-content-center">--}}
                        {{--                                <ul class="">--}}
                        {{--                                @if($data??'')--}}
                        {{--                                    {!!$data!!}--}}
                        {{--                                @endif--}}
                        {{--                                </ul>--}}
                        {{--                            </div>--}}
                        <!--new-->
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary justify-content-center">
                                        @if($info->id ?? '')
                                            {{ __('Edit Category') }}
                                        @else
                                            {{ __('Add Category') }}
                                        @endif
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
