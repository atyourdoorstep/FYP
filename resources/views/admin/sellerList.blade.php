{{--@extends('layouts.app')--}}

{{--@section('content')--}}

{{--@foreach($data as $x)--}}
{{--    {{$x->getSellerRatingAvg()}}--}}
{{--@endforeach--}}

{{--@endsection--}}

@extends('layouts.app')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                        <h6 class="green-text">User Name</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Category</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Email</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Rating</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Status</h6>
                    </th>
                    <th scope="col">
                        <h6 class="green-text">Change Status</h6>
                    </th>
                </strong>
                </thead>

                <tbody class="font-weight-bold">
                <div class="justify-content-start">
                    @foreach($data as $seller)

                        <tr>
                            <td>{{$seller->id}} </td>
                            <div class="" style="">
                                <td>{{$seller->user->fName}} </td>
                            </div>
                            <div class="" style="">
                                <td>{{$seller->user_name}} </td>
                            </div>
                            <div class="" style="">
                                <td>{{$seller->category->name}} </td>
                            </div>
                            <div class=" col-lg-1 col-sm-1 col-xs-12" style="column-gap: 20px;">
                                <td>{{$seller->user->email}} </td>
                            </div>
                            <div class="" style="">
{{--                            @php $r=$seller->getSellerRatingAvg() @endphp--}}
                                <td>{{$seller->getSellerRatingAvg()}}
                                    <span class="fa fa-star"></span>
                                </td>
                            </div>
                            <td>
                                <h6 id="STATUS{{$seller->id}}"
                                    @if($seller->is_active)
                                    class="text-success">Activated
                                    @else
                                        class="text-danger">Deactivated
                                    @endif
                                </h6>
                            </td>
                            <td class="text-center ">
                                <div class="form-inline">
                                    @csrf
                                    <button onclick="changeStatus(this,{{$seller->id}},{{\Illuminate\Support\Facades\Auth::user()->id}},{{\Illuminate\Support\Facades\Auth::user()->appAdmin[0]->id}});"
                                            @if($seller->is_active)
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
    function changeStatus(tag, $id,$user_id,$adminId) {
        tag.disabled = true;
        tag.innerText = "Changing status";
        // $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax({

            url: "https://atyourdoorstep-pk.herokuapp.com/api/changeSellerStatus/",
            type: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                "seller_id":$id,
                "user_id":$user_id,
                "admin_id":$adminId,
            },
            success: function (data) {
                tag.disabled = false;
                console.log(data.success);
                if (data.success==true) {
                    let x=document.getElementById("STATUS"+$id);
                    console.log(x);
                    if(tag.classList =="btn btn-danger")
                    {
                        x.classList="text text-danger";
                        x.innerText="Deactivated";
                        tag.classList = "btn btn-success";
                        tag.innerText = "Activate";
                    }
                    else {
                        x.classList="text text-success";
                        x.innerText="Activated";
                        tag.classList = "btn btn-danger";
                        tag.innerText = "Deactivate";
                    }
                }
            }
        });
    }
</script>

