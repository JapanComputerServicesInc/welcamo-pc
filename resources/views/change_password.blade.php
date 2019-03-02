@extends('layouts.app')

@section('title', __('menu.change_password'))

@section('content')
<div class="container-fluid">
    @include('alert::bootstrap')

    <div class="row">
        <div class="col"></div>

        <div class="col-xs-12 col-sm-8 col-md-6">

            <form action="{{ route('update_password') }}" method="POST">
                @csrf

                <div class="card">

                    <div class="card-body">

                        <h5 class="card-title font-weight-bold mb-5">{{ __('menu.change_password') }}</h5>

                        <div class="form-group">
                            <label for="password">{{ __('app.password') }}</label>
                            <input type="password" class="form-control @if(!empty($errors->has('password')))is-invalid @endif"
                                name="password" maxlength="20">
                            @include('form.invalid', ['element' => 'password'])
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('app.password_confirmation') }}</label>
                            <input type="password" class="form-control"
                                name="password_confirmation" maxlength="20">
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg float-right">{{ __('app.save') }}</button>

                    </div>

                </div>

            </form>

        </div>

        <div class="col"></div>
    </div>

</div>
@endsection
