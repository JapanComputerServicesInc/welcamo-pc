@extends('layouts.app')

@section('title', '詳細')

@section('content')
<div class="container-fluid">
    @include('alert::bootstrap')

    <nav class="navbar-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route($broute) }}">{{ $bname }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">詳細</li>
        </ol>
        <div class="navbar-breadcrumb-buttons">
            <form class="float-right" action="{{ route($broute) }}" method="GET">
                <button type="submit" class="btn btn-secondary">{{ __('app.back') }}</button>
           </form>
            @if($bname == __('menu.approvals'))
            <form class="float-right mr-2" action="{{ route('approval') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">{{ __('app.approval') }}</button>
                <input type="hidden" name="id"     value="{{ $history->id }}">
           </form>
           @endif
        </div>
    </nav>

    <hr>

    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="card bg-light">
                <div class="card-body">

                    @if($bname == __('menu.entries') || $bname == __('menu.approvals'))
                    <form name="history-form" method="POST">
                        @csrf

                        <dl>
                            <dt class="font-weight-bold">{{ __('app.visit_area') }}     </dt><dd class="pb-2">{{ $history->visit_area }}</dd>
                            <dt class="font-weight-bold">{{ __('app.visit_dt') }}       </dt><dd class="pb-2">{{ $history->visit_dt->format('Y年n月j日 H時i分s秒') }}</dd>
                            <dt class="font-weight-bold">{{ __('app.reception_user') }} </dt>
                                <dd class="pb-2">
                                    <select class="custom-select" name="reception_user_id">
                                        @foreach($receptioners as $row)
                                            <option value="{{ $row->id }}" @if($row->id == old('reception_user_id') || $row->id == $reception->id)selected="selected"@endif>{{ $row->short_name }}</option>
                                        @endforeach
                                    </select>
                                </dd>
                            <dt class="font-weight-bold">{{ __('app.company_name') }}   </dt>
                                <dd class="pb-2">
                                    <input type="text" class="form-control @if(!empty($errors->has('company_name')))is-invalid @endif"
                                        name="company_name" maxlength="60" value="{{ old('company_name') ?? $history->company_name }}">
                                    @include('form.invalid', ['element' => 'company_name'])
                                </dd>
                            <dt class="font-weight-bold">{{ __('app.visitor_name') }}   </dt>
                                <dd class="pb-2">
                                    <input type="text" class="form-control @if(!empty($errors->has('visitor_name')))is-invalid @endif"
                                        name="visitor_name" maxlength="20" value="{{ old('visitor_name') ?? $history->visitor_name }}">
                                    @include('form.invalid', ['element' => 'visitor_name'])
                                </dd>
                            <dt class="font-weight-bold">{{ __('app.purpose') }}        </dt>
                                <dd class="pb-2">
                                    <select class="custom-select" name="purpose_id">
                                        @foreach($purposes as $row)
                                            <option value="{{ $row->id }}" @if($row->id == old('purpose_id') || $row->id == $purpose->id)selected="selected"@endif>{{ $row->purpose }}</option>
                                        @endforeach
                                    </select>
                                </dd>
                            <dt class="font-weight-bold">{{ __('app.purpose_remarks') }}</dt>
                                <dd class="pb-2">
                                    <textarea class="form-control" id="purpose_remarks" name="purpose_remarks" rows="3">{{ old('purpose_remarks') ?? $history->purpose_remarks }}</textarea>
                                </dd>
                        </dl>

                        @if($approval)
                        <dl>
                            <dt class="font-weight-bold">{{ __('app.approval_user') }}</dt><dd class="pb-2">{{ $approval ? $approval->short_name : "" }}</dd>
                            <dt class="font-weight-bold">{{ __('app.approval_dt') }}  </dt><dd class="">{{ $approval ? $history->approval_dt->format('Y年n月j日 H時i分s秒') : "" }}</dd>
                        </dl>
                        @endif

                        <div class="float-right">
                            @if($bname == __('menu.entries'))
                            <button type="button" class="btn btn-danger" 
                                onclick="appjs.submitWithConfirm(
                                    'delete-form', 
                                    '{{ route('delete_history') }}',
                                    'POST',
                                    null,
                                    '削除確認',
                                    '入館履歴',
                                    '削除すると元に戻すことはできません。削除してよろしいですか？')">
                                {{ __('app.delete') }}
                            </button>
                            @endif
                            <button type="button" class="btn btn-primary" onclick="appjs.submitForm('history-form', '{{ route('update_history') }}', 'POST', null)">
                                {{ __('app.save') }}
                            </button>
                        </div>

                        <input type="hidden" name="id"  value="{{ $history->id }}">
                        <input type="hidden" name="bname"  value="{{ $bname }}">
                        <input type="hidden" name="broute" value="{{ $broute }}">
                    </form>

                    <form name="delete-form" class="d-none">
                        @csrf
                        <input type="hidden" name="id" value="{{ $history->id }}">
                    </form>

                    @else
                        <dl>
                            <dt class="font-weight-bold">{{ __('app.visit_area') }}     </dt><dd class="pb-2">{{ $history->visit_area }}</dd>
                            <dt class="font-weight-bold">{{ __('app.visit_dt') }}       </dt><dd class="pb-2">{{ $history->visit_dt->format('Y年n月j日 H時i分s秒') }}</dd>
                            <dt class="font-weight-bold">{{ __('app.reception_user') }} </dt><dd class="pb-2">{{ $reception->short_name }}</dd>
                            <dt class="font-weight-bold">{{ __('app.company_name') }}   </dt><dd class="pb-2">{{ $history->company_name }}</dd>
                            <dt class="font-weight-bold">{{ __('app.visitor_name') }}   </dt><dd class="pb-2">{{ $history->visitor_name }}</dd>
                            <dt class="font-weight-bold">{{ __('app.purpose') }}        </dt><dd class="pb-2">{{ $purpose->purpose }}</dd>
                            <dt class="font-weight-bold">{{ __('app.purpose_remarks') }}</dt><dd class="pb-2">{{ $history->purpose_remarks }}</dd>
                        </dl>

                        @if($approval)
                        <dl>
                            <dt class="font-weight-bold">{{ __('app.approval_user') }}</dt><dd class="pb-2">{{ $approval ? $approval->short_name : "" }}</dd>
                            <dt class="font-weight-bold">{{ __('app.approval_dt') }}  </dt><dd class="">{{ $approval ? $history->approval_dt->format('Y年n月j日 H時i分s秒') : "" }}</dd>
                        </dl>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="font-weight-bold">入館者情報</p5>
                </div>
                @foreach($visitors as $row)
                    <div class="col-md-6 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row no-gutters">
                                    <div class="col-4">
                                        <p class="justify-content-center" style="font-size:5.6rem;"><i class="fas fa-user"></i></p>
                                    </div>
                                    <div class="col-8">
                                        <form action="{{ route('update_visitor') }}" method="POST">
                                            @csrf

                                            <p class="font-weight-bold">{{ __('app.admission_no') }}</p>
                                            @if($bname == __('menu.entries'))
                                                <p>
                                                    <select class="custom-select" name="admission_id">
                                                        @foreach($admissions as $admission)
                                                            <option value="{{ $admission->id }}" @if($row->admission_id == $admission->id)selected="selected"@endif>
                                                                {{ $admission->display_no }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </p>
                                            @else
                                                <p>{{ $row->admission_no }}</p>
                                            @endif
                                            <p class="font-weight-bold">{{ __('app.exit_dt') }}</p>
                                            <p>{{ $row->exit_dt ? $row->exit_dt->format('Y年n月j日 H時i分s秒') : "" }}</p>
                                            <p class="font-weight-bold">{{ __('app.signature') }}</p>
                                            <figure class="figure">
                                                <img class="figure-img img-fluid rounded" src="{{ route('signature') . '/'. $row->id }}">
                                            </figure>
                                            @if($bname == __('menu.entries'))
                                            <div class="float-right">
                                                @if(empty($row->exit_dt))
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="$('#visitor_id').val('{{ $row->id }}'); appjs.submitForm('submit-form', '{{ route('exit_visitor') }}', 'POST', null)">
                                                        {{ __('app.exit') }}
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="$('#visitor_id').val('{{ $row->id }}'); appjs.submitForm('submit-form', '{{ route('cancel_exit') }}', 'POST', null)">
                                                        {{ __('app.cancel_exit') }}
                                                    </button>
                                                @endif
                                                <button type="submit" class="btn btn-primary" onclick="">
                                                    {{ __('app.save') }}
                                                </button>
                                                <input type="hidden" name="id" value="{{ $history->id }}">
                                                <input type="hidden" name="visitor_id" value="{{ $row->id }}">
                                            </div>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<form class="d-none" name="submit-form">
    @csrf

    <input type="hidden" name="id" value="{{ $history->id }}">
    <input type="hidden" id="visitor_id" name="visitor_id" value="">
</form>

@include('modal.confirm')

@endsection
