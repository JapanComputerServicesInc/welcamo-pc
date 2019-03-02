@extends('layouts.app')

@section('title', __('menu.user'))

@section('content')
<div class="container-fluid">
    @include('alert::bootstrap')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form class="form-row" method="POST" action="{{ route('store_user') }}">
                @csrf

                <!-- Emailアドレス -->
                <div class="col-sm-2">
                    <input type="text" class="form-control @if(!empty($errors->has('email')))is-invalid @endif" 
                        name="email" placeholder="{{ __('app.email') }}" maxlength="255" value="{{ old('email') }}">
                    @include('form.invalid', ['element' => 'email'])
                </div>

                <!-- ユーザー名 -->
                <div class="col-sm-2">
                    <input type="text" class="form-control @if(!empty($errors->has('user_name')))is-invalid @endif"
                        name="user_name" placeholder="{{ __('app.user_name') }}" maxlength="20" value="{{ old('user_name') }}">
                    @include('form.invalid', ['element' => 'user_name'])
                </div>

                <!-- 略称 -->
                <div class="col-sm-2">
                    <input type="text" class="form-control @if(!empty($errors->has('short_name')))is-invalid @endif"
                        name="short_name" placeholder="{{ __('app.user_short_name') }}" maxlength="10" value="{{ old('short_name') }}">
                    @include('form.invalid', ['element' => 'short_name'])
                </div>

                <!-- 役割 -->
                <div class="col-sm-1">
                    <select class="custom-select mr-sm-2" name="role">
                        @foreach($roles as $role)
                            <option value="{{ $role->kbn_val }}"
                                @if(!empty(old('role')) && old('role') == $role->kbn_val)selected="selected"
                                @endif
                            >
                                {{ $role->kbn_nm1 }}
                            </option>
                        @endforeach 
                    </select>
                </div>

                <!-- 受付 -->
                <div class="col-sm-1">
                    <select class="custom-select mr-sm-2" name="reception">
                        @foreach($receptions as $reception)
                            <option value="{{ $reception->kbn_val }}"
                                @if(!empty(old('reception')) && old('reception') == $reception->kbn_val)selected="selected"
                                @endif
                            >
                                {{ $reception->kbn_nm1 }}
                            </option>
                        @endforeach 
                    </select>
                </div>

                <!-- パスワード -->
                <div class="col-sm-2">
                    <input type="password" class="form-control @if(!empty($errors->has('password')))is-invalid @endif"
                        name="password" placeholder="{{ __('app.password') }}" maxlength="20">
                    @include('form.invalid', ['element' => 'password'])
                </div>

                <!-- SUBMIT -->
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">{{ __('app.add') }}</button>
                </div>

            </form>

            <hr>

            <form id="serach-form" class="form-row" method="POST" action="{{ route('users') }}">
                @csrf

                <!-- 検索ボックス -->
                <div class="col-sm-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="" maxlength="20" name="criteria" value="{{ old('criteria') ?? $criteria }}">
                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">{{ __('app.search') }}</button>
                </div>
            </form>

            <br>

            <form name="edit-form">
                @csrf

                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">{{ __('app.email') }}</th>
                            <th scope="col">{{ __('app.user_name') }}</th>
                            <th scope="col">{{ __('app.user_short_name') }}</th>
                            <th scope="col">{{ __('app.role') }}</th>
                            <th scope="col">{{ __('app.reception') }}</th>
                            <th scope="col">{{ __('app.password_change') }}</th>
                            <th scope="col">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    </body>
                        @foreach ($users as $row)
                        <tr>
                            <td>
                                <input type="text" class="form-control @if(!empty($errors->has('e_email.'.$loop->index)))is-invalid @endif"
                                    name="e_email[]" value="{{ old('e_email.'.$loop->index, $row->email) }}" maxlength="255">
                                @include('form.invalid', ['element' => 'e_email.'.$loop->index])
                            </td>
                            <td>
                                <input type="text" class="form-control @if(!empty($errors->has('e_user_name.'.$loop->index)))is-invalid @endif"
                                    name="e_user_name[]" value="{{ old('e_user_name.'.$loop->index, $row->user_name) }}" maxlength="20">
                                @include('form.invalid', ['element' => 'e_user_name.'.$loop->index])
                            </td>
                            <td>
                                <input type="text" class="form-control @if(!empty($errors->has('e_short_name.'.$loop->index)))is-invalid @endif"
                                    name="e_short_name[]" value="{{ old('e_short_name.'.$loop->index, $row->short_name) }}" maxlength="10">
                                @include('form.invalid', ['element' => 'e_short_name.'.$loop->index])
                            </td>
                            <td>
                                <select class="custom-select" name="e_role[]">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->kbn_val }}" 
                                            @if(!empty(old('e_role')) && old('e_role') == $role->kbn_val)selected="selected"
                                            @elseif($row->role == $role->kbn_val)selected="selected"
                                            @endif
                                        >
                                            {{ $role->kbn_nm1 }}
                                        </option>
                                    @endforeach 
                                </select>
                            </td>
                            <td>
                                <select class="custom-select" name="e_reception[]">
                                    @foreach($receptions as $reception)
                                        <option value="{{ $reception->kbn_val }}"
                                            @if(!empty(old('e_reception')) && old('e_reception') == $reception->kbn_val)selected="selected"
                                            @elseif($row->reception == $reception->kbn_val)selected="selected"
                                            @endif
                                        >
                                            {{ $reception->kbn_nm1 }}
                                        </option>
                                    @endforeach 
                                </select>
                            </td>
                            <td>
                                <input type="password" class="form-control @if(!empty($errors->has('e_password.'.$loop->index)))is-invalid @endif"
                                    name="e_password[]" maxlength="20">
                                @include('form.invalid', ['element' => 'e_password.'.$loop->index])
                            </td>
                            <td>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" name="save"
                                        onclick="appjs.submitForm('edit-form', '{{ route('update_user') }}', 'POST', '{{ $loop->index }}')">
                                        {{ __('app.save') }}
                                    </button>
                                     @if($row->id != '1' && $row->role != config('welcamo.admin'))
                                    <button type="button" class="btn btn-danger" name="delete"
                                        onclick="appjs.submitWithConfirm(
                                            'edit-form', 
                                            '{{ route('delete_user') }}',
                                            'POST',
                                            '{{ $loop->index }}',
                                            '削除確認',
                                            'ユーザー',
                                            '{{ $row->user_name }}')">
                                        {{ __('app.delete') }}
                                    </button>
                                    @endif
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
