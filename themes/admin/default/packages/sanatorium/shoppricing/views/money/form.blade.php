@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/pricing::money/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Page content --}}
@section('page')

<section class="panel panel-default panel-tabs">

	{{-- Form --}}
	<form id="pricing-form" action="{{ request()->fullUrl() }}" role="form" method="post" data-parsley-validate>

		{{-- Form: CSRF Token --}}
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<header class="panel-heading">

			<nav class="navbar navbar-default navbar-actions">

				<div class="container-fluid">

					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#actions">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.pricing.money.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $money->exists ? $money->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($money->exists)
							<li>
								<a href="{{ route('admin.sanatorium.pricing.money.delete', $money->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
									<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{{ trans('action.delete') }}}</span>
								</a>
							</li>
							@endif

							<li>
								<button class="btn btn-primary navbar-btn" data-toggle="tooltip" data-original-title="{{{ trans('action.save') }}}">
									<i class="fa fa-save"></i> <span class="visible-xs-inline">{{{ trans('action.save') }}}</span>
								</button>
							</li>

						</ul>

					</div>

				</div>

			</nav>

		</header>

		<div class="panel-body">

			<div role="tabpanel">

				{{-- Form: Tabs --}}
				<ul class="nav nav-tabs" role="tablist">
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/pricing::money/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/pricing::money/common.tabs.attributes') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('amount', ' has-error') }}">

									<label for="amount" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/pricing::money/model.general.amount_help') }}}"></i>
										{{{ trans('sanatorium/pricing::money/model.general.amount') }}}
									</label>

									<input type="text" class="form-control" name="amount" id="amount" placeholder="{{{ trans('sanatorium/pricing::money/model.general.amount') }}}" value="{{{ input()->old('amount', $money->amount) }}}">

									<span class="help-block">{{{ Alert::onForm('amount') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('type', ' has-error') }}">

									<label for="type" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/pricing::money/model.general.type_help') }}}"></i>
										{{{ trans('sanatorium/pricing::money/model.general.type') }}}
									</label>

									<input type="text" class="form-control" name="type" id="type" placeholder="{{{ trans('sanatorium/pricing::money/model.general.type') }}}" value="{{{ input()->old('type', $money->type) }}}">

									<span class="help-block">{{{ Alert::onForm('type') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('currency_id', ' has-error') }}">

									<label for="currency_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/pricing::money/model.general.currency_id_help') }}}"></i>
										{{{ trans('sanatorium/pricing::money/model.general.currency_id') }}}
									</label>

									<input type="text" class="form-control" name="currency_id" id="currency_id" placeholder="{{{ trans('sanatorium/pricing::money/model.general.currency_id') }}}" value="{{{ input()->old('currency_id', $money->currency_id) }}}">

									<span class="help-block">{{{ Alert::onForm('currency_id') }}}</span>

								</div>


							</div>

						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($money)
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
