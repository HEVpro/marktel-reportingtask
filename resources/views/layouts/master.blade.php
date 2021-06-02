@extends('layouts.app')

@section('content')
    <div class="row ml-2">
        <div class="col-2">
            @include('layouts.includes.sidebar')
        </div>
        <div class="col-10">
            <div class="col-11 p-2" align="center">
                @yield('actions')
            </div>
            <div class="col-12 p-2">
                @yield('page-js-script')
            </div>
        </div>
    </div>

@endsection
