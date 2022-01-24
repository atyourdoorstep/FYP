@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-sm-6 flex-column flex-shrink-0 p-3 text-white bg-red pb-0 " style="width: 280px; height:1000px;margin-top:-25px;">
                <a href="#" class="d-flex-block align-items-baseline mb-3 mb-md-0 me-md-auto text-white text-decoration-none align-text-center\" >
                    <svg class="bi me-2" width="40" height="32">
                        <use xlink:href="#bootstrap"></use>
                    </svg>
                    <span class="fs-4">{{ config('app.name', 'AtYourDoorStep') }}</span>
                </a>
                <hr>
                {{--        <ul class="nav nav-pills flex-column mb-auto">--}}
                {{--            <li>--}}
                {{--                <a href="#" class="nav-link text-white">--}}
                {{--                    <svg class="bi me-2" width="16" height="16">--}}
                {{--                        <use xlink:href="#speedometer2"></use>--}}
                {{--                    </svg>--}}
                {{--                    Services--}}
                {{--                </a>--}}
                {{--            </li>--}}
                {{--        </ul>--}}
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false" v-pre>
                            Services
                        </a>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"
                                 style="background-color: #1b1e21;text-decoration-color: white">
                                <div class="text-primary">
                                    <a class="dropdown-item text-primary" href="/addCategory">
                                        Add
                                    </a>
                                    <a class="dropdown-item text-primary" href="/catList">
                                        Service List
                                    </a>
                                </div>
                            </div>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false" v-pre>
                            Sellers
                        </a>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"
                                 style="background-color: #1b1e21;text-decoration-color: white">
                                <div class="text-primary">
                                    <a class="dropdown-item text-primary" href="/sellerList">
                                        List Sellers
                                    </a>
                                </div>
                            </div>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false" v-pre>
                            Requests
                        </a>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"
                                 style="background-color: #1b1e21;text-decoration-color: white">
                                <div class="text-primary">
                                    <a class="dropdown-item text-primary" href="/request">
                                        Requests List
                                    </a>
                                </div>
                            </div>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false" v-pre>
                            Feedbacks
                        </a>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"
                                 style="background-color: #1b1e21;text-decoration-color: white">
                                <div class="text-primary">
                                    <a class="dropdown-item text-primary" href="/feedback">
                                        FeedBack List
                                    </a>
                                </div>
                            </div>
                        </ul>
                    </li>

                </ul>
                <hr>

            </div>
            <div class="col-lg-2 col-sm-6">
                <div class="card-box bg-red" style="margin: 18px 0px 0px 144px; height:200px; width:300px">
                    <div class="inner">
                        <h3></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
                <div class="card-box bg-red" style="margin: 100px 0px 0px 144px; height:200px; width:300px">
                    <div class="inner">
                        <h3></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6">
                <div class="card-box bg-red" style="margin: 18px 0px 0px 450px; height:200px; width:300px">
                    <div class="inner">
                        <h3></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
                <div class="card-box bg-red" style="margin: 100px 0px 0px 450px; height:200px; width:300px">
                    <div class="inner">
                        <h3></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
@endsection
