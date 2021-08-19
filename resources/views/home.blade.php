@extends('layouts.app')

@section('content')
    <div class=" flex-column flex-shrink-0 p-3 text-white bg-dark pb-0 " style="width: 280px; border-radius: 50px">
        <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <svg class="bi me-2" width="40" height="32">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4">{{ config('app.name', 'Laravel') }}</span>
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
                    Services
                </a>
                <ul class="nav nav-pills flex-column mb-auto">
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"
                         style="background-color: #1b1e21;text-decoration-color: white">
                        <div class="text-primary">
                            <a class="dropdown-item text-primary" href="#">
                                Add
                            </a>
                            <a class="dropdown-item text-primary" href="#">
                                List
                            </a>
                        </div>
                    </div>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false" v-pre>
                    Services
                </a>
                <ul class="nav nav-pills flex-column mb-auto">
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"
                         style="background-color: #1b1e21;text-decoration-color: white">
                        <div class="text-primary">
                            <a class="dropdown-item text-primary" href="#">
                                Add
                            </a>
                            <a class="dropdown-item text-primary" href="#">
                                List
                            </a>
                        </div>
                    </div>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false" v-pre>
                    Services
                </a>
                <ul class="nav nav-pills flex-column mb-auto">
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"
                         style="background-color: #1b1e21;text-decoration-color: white">
                        <div class="text-primary">
                            <a class="dropdown-item text-primary" href="#">
                                Add
                            </a>
                            <a class="dropdown-item text-primary" href="#">
                                List
                            </a>
                        </div>
                    </div>
                </ul>
            </li>
        </ul>
        <hr>

    </div>

@endsection
