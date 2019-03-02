@extends('layouts.app')

@section('title', __('menu.histories'))

@section('content')
<div class="container-fluid">
    @include('alert::bootstrap')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form class="form-row" method="POST" action="{{ route('search_histories') }}">
                @csrf

                <!-- 検索年 -->
                <div class="col-sm-1">
                    <select class="custom-select mr-sm-2" name="year">
                        @foreach($years as $val)
                            <option value="{{ $val }}"
                                @if($val == $year)selected="selected"
                                @endif
                            >
                                {{ $val }} 年
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 検索月 -->
                <div class="col-sm-1">
                    <select class="custom-select mr-sm-2" name="month">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}"
                                @if($i == $month)selected="selected"
                                @endif
                            >
                                {{ $i }} 月
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- 検索ボックス -->
                <div class="col-sm-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control @if(!empty($errors->has('criteria')))is-invalid @endif"
                             placeholder="会社名(代表) or 入館者名(代表)" maxlength="20" name="criteria" value="{{ $criteria ?? "" }}">
                        @include('form.invalid', ['element' => 'criteria'])
                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">{{ __('app.search') }}</button>
                </div>

            </form>

            <hr>

            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">{{ __('app.visit_date') }}</th>
                        <th scope="col">{{ __('app.visit_time') }}</th>
                        <th scope="col">{{ __('app.last_date') }}</th>
                        <th scope="col">{{ __('app.last_time') }}</th>
                        <th scope="col">{{ __('app.company_name') }}</th>
                        <th scope="col">{{ __('app.visitor_name') }}</th>
                        <th scope="col">{{ __('app.reception_user') }}</th>
                        <th scope="col">{{ __('app.approval_user') }}</th>
                        <th scope="col">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                </body>
                    @foreach ($histories as $row)
                    <tr>
                        <td>{{ $row->visit_dt->format('Y年 n月 j日') }}</td>
                        <td>{{ $row->visit_dt->format('G時 i分') }}</td>
                        <td>{{ $row->last_dt ? $row->last_dt->format('Y年 n月 j日') : "" }}</td>
                        <td>{{ $row->last_dt ? $row->last_dt->format('G時 i分') : "" }}</td>
                        <td>{{ $row->company_name }}</td>
                        <td>{{ $row->visitor_name }}</td>
                        <td>{{ $row->reception_user }}</td>
                        <td>{{ $row->approval_user }}</td>
                        <td>
                            <div class="col-auto">
                                <form action="{{ route('history') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block" name="detail">
                                        {{ __('app.detail') }}
                                    </button>
                                    <input type="hidden" name="id" value="{{ $row->id }}">
                                    <input type="hidden" name="bname"  value="{{ __('menu.histories') }}">
                                    <input type="hidden" name="broute" value="search_histories">
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </body>
            </table>

            <input type="hidden" id="edit_index" name="edit_index" value="">

        </div> <!-- .col -->
    </div> <!-- .row -->
</div> <!-- .container -->
@endsection
