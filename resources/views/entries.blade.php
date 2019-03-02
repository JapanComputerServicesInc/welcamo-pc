@extends('layouts.app')

@section('title', __('menu.entries'))

@section('content')
<div class="container-fluid">
    @include('alert::bootstrap')
    <div class="row justify-content-center">
        <div class="col-md-12">

            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">{{ __('app.visit_date') }}</th>
                        <th scope="col">{{ __('app.visit_time') }}</th>
                        <th scope="col">{{ __('app.company_name') }}</th>
                        <th scope="col">{{ __('app.visitor_name') }}</th>
                        <th scope="col">{{ __('app.reception_user') }}</th>
                        <th scope="col">{{ __('app.entry_count') }}</th>
                        <th scope="col">{{ __('app.exit_count') }}</th>
                        <th scope="col">{{ __('app.detail') }}</th>
                        <th scope="col">{{ __('app.exit') }}</th>
                    </tr>
                </thead>
                </body>
                    @foreach ($entries as $row)
                    <tr>
                        <td>{{ $row->visit_dt->format('Y年 n月 j日') }}</td>
                        <td>{{ $row->visit_dt->format('G時 i分') }}</td>
                        <td>{{ $row->company_name }}</td>
                        <td>{{ $row->visitor_name }}</td>
                        <td>{{ $row->short_name }}</td>
                        <td>{{ $row->entry_count ?? 0 }}</td>
                        <td>{{ $row->exit_count ?? 0 }}</td>
                        <td>
                            <form action="{{ route('history') }}" method="POST">
                                @csrf
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success btn-block" name="detail">
                                        {{ __('app.detail') }}
                                    </button>
                                    <input type="hidden" name="id" value="{{ $row->id }}">
                                    <input type="hidden" name="bname"  value="{{ __('menu.entries') }}">
                                    <input type="hidden" name="broute" value="entries">
                                </div>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('show_exit_all') }}" method="POST">
                                @csrf
                                    <button type="submit" class="btn btn-danger btn-block" name="exit">
                                        {{ __('app.exit') }}
                                    </button>
                                    <input type="hidden" name="id" value="{{ $row->id }}">
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </body>
            </table>

        </div> <!-- .col -->
    </div> <!-- .row -->
</div> <!-- .container -->
@endsection
