@extends('layouts.app')

@section('content')

<div class="container">


    @if (session('alert'))
    <div class="alert alert-{!! session('alert')['class'] !!} text-center">
        {!! session('alert')['html'] !!}
    </div>
    @endif


    <div class="row mb-4">

        <div class="col">
            <notifications-bar></notifications-bar>
        </div>

        <div class="col-auto text-right">
            <add-project-button :inline=true></add-project-button>
            <add-event-button :inline=true></add-event-button>
        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-7">

            <project-list></project-list>

        </div>
        <div class="col-md-5">


            <div class="card mb-4">
                <div class="card-body text-center">
                    <calendar-component></calendar-component>
                </div>
            </div>



            <div class="card mb-4">

                <div class="card-body">

                    <h4>Events</h4>

                    <event-list></event-list>

                </div>

            </div>
        </div>

    </div>

    <add-event-modal :user_id="{{ Auth::id() }}"></add-event-modal>
    <add-project-modal :user_id="{{ Auth::id() }}"></add-project-modal>

    @stop