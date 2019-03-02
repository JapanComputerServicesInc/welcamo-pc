@extends('layouts.app')

@section('title', __('menu.purpose'))

@section('content')
<div class="container-fluid">
    @include('alert::bootstrap')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form class="form-row" name="add-form" method="POST" action="{{ route('store_purpose') }}">
                @csrf

                <!-- 入館理由 -->
                <div class="col-sm-3">
                    <input type="text" class="form-control @if(!empty($errors->has('purpose')))is-invalid @endif"
                        name="purpose" placeholder="{{ __('app.purpose') }}" maxlength="40" value="{{ old('purpose') }}">
                    @include('form.invalid', ['element' => 'purpose'])
                </div>

                <!-- 並び順 -->
                <div class="col-sm-2">
                    <input type="number" class="form-control @if(!empty($errors->has('sort_no')))is-invalid @endif"
                        name="sort_no" placeholder="{{ __('app.sort_no') }}" maxlength="5" value="{{ old('sort_no') }}">
                    @include('form.invalid', ['element' => 'sort_no'])
                </div>

                <!-- SUBMIT -->
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">{{ __('app.add') }}</button>
                </div>

            </form>

            <hr>

            <form name="edit-form">
                @csrf

                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">{{ __('app.purpose') }}</th>
                            <th scope="col">{{ __('app.sort_no') }}</th>
                            <th scope="col">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    </body>
                        @foreach ($purposes as $row)
                        <tr>
                            <td>
                                <input type="text" class="form-control @if(!empty($errors->has('e_purpose.'.$loop->index)))is-invalid @endif"
                                    name="e_purpose[]" value="{{ old('e_purpose.'.$loop->index, $row->purpose) }}" maxlength="40">
                                @include('form.invalid', ['element' => 'e_purpose.'.$loop->index])
                            </td>
                            <td>
                                <input type="number" class="form-control @if(!empty($errors->has('e_sort_no.'.$loop->index)))is-invalid @endif"
                                    name="e_sort_no[]" value="{{ old('e_sort_no.'.$loop->index, $row->sort_no) }}" maxlength="5">
                                @include('form.invalid', ['element' => 'e_sort_no.'.$loop->index])
                            </td>
                            <td>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" name="save"
                                        onclick="appjs.submitForm('edit-form', '{{ route('update_purpose') }}', 'POST', '{{ $loop->index }}')">
                                        {{ __('app.save') }}
                                    </button>
                                    <button type="button" class="btn btn-danger" name="delete"
                                        onclick="appjs.submitWithConfirm(
                                            'edit-form', 
                                            '{{ route('delete_purpose') }}',
                                            'POST',
                                            '{{ $loop->index }}',
                                            '削除確認',
                                            '入館理由',
                                            '{{ $row->purpose }}')">
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
