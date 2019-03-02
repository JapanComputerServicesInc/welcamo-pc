@extends('layouts.app')

@section('title', __('menu.schedules'))

@section('content')
<div class="container-fluid">
    @include('alert::bootstrap')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form class="form-row" method="POST" action="{{ route('store_schedule') }}">
                @csrf

                <!-- 入館予定日 -->
                <div class="col-sm-2">
                    <input type="date" class="form-control @if(!empty($errors->has('schedule_date')))is-invalid @endif"
                        name="schedule_date" placeholder="{{ __('app.schedule_date') }}" maxlength="10" value="{{ old('schedule_date') }}">
                    @include('form.invalid', ['element' => 'schedule_date'])
                </div>

                <!-- 会社名（代表） -->
                <div class="col-sm-3">
                  <input type="text" class="form-control @if(!empty($errors->has('company_name')))is-invalid @endif"
                      name="company_name" placeholder="{{ __('app.company_name') }}" maxlength="60" value="{{ old('company_name') }}">
                    @include('form.invalid', ['element' => 'company_name'])
                </div>

                <!-- 入館者名（代表） -->
                <div class="col-sm-3">
                    <input type="text" class="form-control @if(!empty($errors->has('visitor_name')))is-invalid @endif"
                        name="visitor_name" placeholder="{{ __('app.visitor_name') }}" maxlength="20" value="{{ old('visitor_name') }}">
                    @include('form.invalid', ['element' => 'visitor_name'])
                </div>

                <!-- SUBMIT -->
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">{{ __('app.add') }}</button>
                </div>

            </form>

            <hr>

            <form name="edit-form">
                @csrf

                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">{{ __('app.schedule_date') }}</th>
                            <th scope="col">{{ __('app.company_name') }}</th>
                            <th scope="col">{{ __('app.visitor_name') }}</th>
                            <th scope="col">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    </body>
                        @foreach ($schedules as $row)
                        <tr>
                            <td>{{ $row->schedule_date->format('Y年 n月 j日') }}</td>
                            <td>{{ $row->company_name }}</td>
                            <td>{{ $row->visitor_name }}</td>
                            <td>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-danger btn-block" name="delete"
                                        onclick="appjs.submitWithConfirm(
                                            'edit-form', 
                                            '{{ route('delete_schedule') }}',
                                            'POST',
                                            '{{ $loop->index }}',
                                            '削除確認',
                                            '入館予定',
                                            '{{ $row->schedule_date->format('n月 j日') }} : {{ $row->company_name }}')">
                                        {{ __('app.delete') }}
                                    </button>
                                    <input type="hidden" name="id[]" value="{{ old('id.'.$loop->index, $row->id) }}">
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </body>
                </table>

                <input type="hidden" id="edit_index" name="edit_index" value="">
            </form>

        </div> <!-- .col -->
    </div> <!-- .row -->
</div> <!-- .container -->

@include('modal.confirm')

@endsection
