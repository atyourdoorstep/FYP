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

    <table class="table table-hover text-center">
        <div class="row focuses">
            <div class="table-responsive">
                <thead class="thead-dark font-weight-bold">
                <strong>
                    <th scope="col">
                        <h6 class="" style="text-align:center">id</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Name</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Description</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Details</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Update</h6>
                    </th>
                </strong>
                </thead>

                <tbody class="font-weight-bold ">
                <div class="justify-content-start">
                    @foreach($data as $category)

                        <tr>
                            <td>{{$category->id}} </td>
                            <div class="" style="">
                                <td>{{$category->name}} </td>
                            </div>


                            <td>{{$category->description}} </td>
                            <td>
                                @if(count($category->children))
                                    <form method="get" action="/catList/{{$category->id}}">
                                        @csrf
                                        <button class="btn-toggle-nav">
                                            view sub services
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td>
                                <form method="post" action="/cat/{{$category->id}}/edit">
                                    @csrf
                                    <button type="submit"
                                            class="btn green-button"
                                            style="padding-right: 35px;padding-left: 35px;border-radius: 5%;border-style: none;background-color: #17a2b8">
                                        {{ __('Edit') }}
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
<script type="text/javascript">
    function changeStatus(tag, $id) {
        return '';
        tag.disabled = true;
        tag.innerText = "Changing status";
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax({

            url: "/org/" + $id + "/changeStatus",
            type: "post",
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function (data) {
                tag.disabled = false;
                //console.log("success");
                if (data) {
                    tag.classList = "btn btn-danger";
                    tag.innerText = "Deactivate";
                } else {
                    tag.classList = "btn btn-success";
                    tag.innerText = "Activate";
                }
            }
        });
    }
</script>


{{--                    subCat--}}

{{--                        @foreach($category->children as $subCat)--}}

{{--                            <tr>--}}
{{--                                <td>---{{$subCat->id}} </td>--}}
{{--                                <div class="" style="">--}}
{{--                                    <td>{{$subCat->name}} </td>--}}
{{--                                </div>--}}


{{--                                <td>{{$subCat->description}} </td>--}}

{{--                                <td>--}}
{{--                                    <form method="post" action="cat/{{$subCat->id}}/edit">--}}
{{--                                        @csrf--}}
{{--                                        <button type="submit"--}}
{{--                                                class="btn green-button"--}}
{{--                                                style="padding-right: 35px;padding-left: 35px;border-radius: 5%;border-style: none;background-color: #17a2b8">--}}
{{--                                            {{ __('Edit') }}--}}
{{--                                        </button>--}}
{{--                                    </form>--}}
{{--                                </td>--}}

{{--                            @foreach($subCat->children as $end)--}}

{{--                                <tr>--}}
{{--                                    <td>------{{$end->id}} </td>--}}
{{--                                    <div class="" style="">--}}
{{--                                        <td>{{$end->name}} </td>--}}
{{--                                    </div>--}}


{{--                                    <td>{{$end->description}} </td>--}}

{{--                                    <td>--}}
{{--                                        <form method="post" action="cat/{{$end->id}}/edit">--}}
{{--                                            @csrf--}}
{{--                                            <button type="submit"--}}
{{--                                                    class="btn green-button"--}}
{{--                                                    style="padding-right: 35px;padding-left: 35px;border-radius: 5%;border-style: none;background-color: #17a2b8">--}}
{{--                                                {{ __('Edit') }}--}}
{{--                                            </button>--}}
{{--                                        </form>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                @endforeach--}}
{{--                                </tr>--}}

{{--                                @endforeach--}}
