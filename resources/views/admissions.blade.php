@extends('layouts.app')

@section('title', __('menu.admission'))

@section('content')
<div class="container-fluid">
    @include('alert::bootstrap')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form class="form-row" name="add-form" method="POST" action="{{ route('store_admission') }}">
                @csrf

                <!-- 入館証NO -->
                <div class="col-sm-3">
                    <input type="text" class="form-control @if(!empty($errors->has('no')))is-invalid @endif"
                        name="no" placeholder="{{ __('app.admission_no') }}" maxlength="12" value="{{ old('no') }}">
                    @include('form.invalid', ['element' => 'no'])
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
                            <th scope="col">{{ __('app.admission_no') }}</th>
                            <th scope="col">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    </body>
                        @foreach ($admissions as $row)
                        <tr>
                            <td>
                                <input type="text" class="form-control @if(!empty($errors->has('e_no.'.$loop->index)))is-invalid @endif"
                                    name="e_no[]" value="{{ old('e_no.'.$loop->index, $row->no) }}" maxlength="40">
                                @include('form.invalid', ['element' => 'e_no.'.$loop->index])
                            </td>
                            <td>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" name="save"
                                        onclick="appjs.submitForm('edit-form', '{{ route('update_admission') }}', 'POST', '{{ $loop->index }}')">
                                        {{ __('app.save') }}
                                    </button>
                                    <button type="button" class="btn btn-danger" name="delete"
                                        onclick="appjs.submitWithConfirm(
                                            'edit-form', 
                                            '{{ route('delete_admission') }}',
                                            'POST',
                                            '{{ $loop->index }}',
                                            '削除確認',
                                            '入館証No.',
                                            '{{ $row->no }}')">
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
