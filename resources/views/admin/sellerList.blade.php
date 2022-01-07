@extends('layouts.app')

@section('content')

@foreach($data as $x)
    {{$x->getSellerRatingAvg()}}
@endforeach

@endsection
