@extends('layouts.app')

@section('title', __('app.exit'))

@section('content')
<div class="container-fluid">
    @include('alert::bootstrap')

    <nav class="navbar-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('entries') }}">{{ __('menu.entries') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('app.exit') }}</li>
        </ol>
        <div class="navbar-breadcrumb-buttons">
            <form action="{{ route('entries') }}" method="GET">
                <button type="submit" class="btn btn-secondary">{{ __('app.back') }}</button>
           </form>
        </div>
    </nav>

    <hr>

    <div class="row">
        <div class="col-sm-12 col-md-8 offset-md-3">
            <h5 class="font-weight-bold mt-3 mb-5">以下の方の退館を受け付けてよろしいでしょうか？<h5>

            <form action="{{ route('exit_all') }}" method="POST">
                @csrf

                <table class="table table-bordered">
                    <thead class="table-secondary">
                        <th>{{ __('app.admission_no') }}</th>
                        <th>{{ __('app.signature') }}</th>
                    </thead>
                    <tbody>
                        @foreach($visitors as $row)
                            <tr>
                                <td>
                                    {{ $row->admission_no }}
                                </td>
                                <td>
                                    <figure class="figure">
                                        <img class="figure-img img-fluid rounded" src="{{ route('signature') }}/{{ $row->id }}">
                                    </figure>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="float-right">
                    <a href="{{ route('entries') }}" class="btn btn-secondary mr-2">{{ __('app.cancel') }}</a>
                    <button type="submit" class="btn btn-danger mr-2">{{ __('app.exit') }}</button>
                </div>

                <input type="hidden" name="id" value="{{ $id }}">
            </form>
        </div>
    </div>

</div>

@endsection
