@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        You are logged in!
                    </div>


                    @if(Auth::user()->hasRole('s3'))
                    <!-- The Current User Can Update The Post -->
                        <div class="panel-body">
                            admin is logged in
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
