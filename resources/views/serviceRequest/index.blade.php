@extends('layouts.app')


@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if ($parent??'')
        <h1>
            <div class="card-header text-center">
                {{$parent->name}}
            </div>
        </h1>

    @endif

{{--    {{dd($data)}}--}}
{{--    {{dd($data[1]->all()[])}}--}}
{{--    {{$data->currentPage()}}--}}
    <table class="table table-hover text-center">
        <div class="row focuses">
            <div class="table-responsive">
                <thead class="thead-dark font-weight-bold">
                <strong>
                    {{--                    <th scope="col">--}}
                    {{--                        <h6 class="" style="text-align:center">id</h6>--}}
                    {{--                    </th>--}}
                    <th scope="col">
                        <h6 class="green-text">Name</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">CNIC</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Date</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">view</h6>
                    </th>
                </strong>
                </thead>

                <tbody class="font-weight-bold ">
                <div class="justify-content-start">
                    @foreach($data as $req)

                        <tr>
                            {{--                            <td>{{$category->id}} </td>--}}
                            <div class="" style="">
                                <td>{{ucfirst($req->user->fName).' '.ucfirst($req->user->lName)}} </td>
                            </div>


                            <td>{{$req->user->CNIC}} </td>
                            <td>{{$req->created_at}} </td>
                            <td>
                                <form method="post"
                                      @if($check??'')
                                      action="/requestDetails/{{$req->id}}">
                                    @else
                                        action="/feedBackDetails/{{$req->id}}">
                                    @endif
                                    @csrf
                                    <button type="submit"
                                            class="btn green-button"
                                            style="padding-right: 35px;padding-left: 35px;border-radius: 5%;border-style: none;background-color: #17a2b8">
                                        {{ __('View') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </div>

                </tbody>
            </div>
        </div>
    </table>

    <h4 class="text-center justify-content">
        Showing {{($data->currentPage()-1)* $data->perPage()+($data->total() ? 1:0)}} to
        {{($data->currentPage()-1)*$data->perPage()+count($data)}} of
        {{$data->total()}} Results
    </h4>
    <div class="ml- 50 justify-content">
        {{$data->links()}}
    </div>


@endsection
