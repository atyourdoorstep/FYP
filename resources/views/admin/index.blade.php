@extends('layouts.app')


@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
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
                        <h6 class="green-text">Short Name</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Contact Person</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Contact</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Mail</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Registration date</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Status</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Change Status</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text"><strong> Edit Organization</strong></h6>
                    </th>
                </strong>
                </thead>

                <tbody class="font-weight-bold">
                <div class="justify-content-start">
                    @foreach($data as $user)

                        <tr>
                            <td>{{$user->id}} </td>
                            <div class="" style="">
                                <td>{{$user->name}} </td>
                            </div>


                            <td>{{$user->shortName}} </td>

                            <td>{{$user->contactPerson}} </td>
                            <td>{{$user->contact}} </td>
                            <div class=" col-lg-1 col-sm-1 col-xs-12" style="column-gap: 20px;">
                                <td>{{$user->email}} </td>
                            </div>
                            <td>{{$user->regDate}} </td>
                            <td>
                                <h6 id="STATUS{{$user->id}}"
                                    @if($user->isActive)
                                    class="text-success">Activated
                                    @else
                                        class="text-danger">Deactivated
                                    @endif
                                </h6>
                            </td>
                            <td class="text-center ">
                                <div class="form-inline">
                                    @csrf
                                    <button onclick="changeStatus(this,{{$user->id}});"
                                            @if($user->isActive)
                                            class="btn btn-danger">
                                        {{ __('Deactivate') }}
                                        @else
                                            class="btn btn-success">
                                            {{ __('Activate') }}
                                        @endif
                                        {{--                                                                <i class="fa fa-spinner fa-spin"></i> Changing status--}}
                                    </button>

                                </div>
                            </td>
                            <td>
                                <form method="post" action="org/{{$user->id}}/edit">
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
