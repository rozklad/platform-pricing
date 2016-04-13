@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/pricing::currencies/common.title') }}
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

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.pricing.currencies.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $currency->exists ? $currency->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($currency->exists)
							<li>
								<a href="{{ route('admin.sanatorium.pricing.currencies.delete', $currency->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
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
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/pricing::currencies/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/pricing::currencies/common.tabs.attributes') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('name', ' has-error') }}">

									<label for="name" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/pricing::currencies/model.general.name_help') }}}"></i>
										{{{ trans('sanatorium/pricing::currencies/model.general.name') }}}
									</label>

									<input type="text" class="form-control" name="name" id="name" placeholder="{{{ trans('sanatorium/pricing::currencies/model.general.name') }}}" value="{{{ input()->old('name', $currency->name) }}}">

									<span class="help-block">{{{ Alert::onForm('name') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('code', ' has-error') }}">

									<label for="code" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/pricing::currencies/model.general.code_help') }}}"></i>
										{{{ trans('sanatorium/pricing::currencies/model.general.code') }}}
									</label>

									<input type="text" class="form-control" name="code" id="code" placeholder="{{{ trans('sanatorium/pricing::currencies/model.general.code') }}}" value="{{{ input()->old('code', $currency->code) }}}">

									<span class="help-block">{{{ Alert::onForm('code') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('unit', ' has-error') }}">

									<label for="unit" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/pricing::currencies/model.general.unit_help') }}}"></i>
										{{{ trans('sanatorium/pricing::currencies/model.general.unit') }}}
									</label>

									<input type="text" class="form-control" name="unit" id="unit" placeholder="{{{ trans('sanatorium/pricing::currencies/model.general.unit') }}}" value="{{{ input()->old('unit', $currency->unit) }}}">

									<span class="help-block">{{{ Alert::onForm('unit') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('symbol', ' has-error') }}">

									<label for="symbol" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/pricing::currencies/model.general.symbol_help') }}}"></i>
										{{{ trans('sanatorium/pricing::currencies/model.general.symbol') }}}
									</label>

									<input type="text" class="form-control" name="symbol" id="symbol" placeholder="{{{ trans('sanatorium/pricing::currencies/model.general.symbol') }}}" value="{{{ input()->old('symbol', $currency->symbol) }}}">

									<span class="help-block">{{{ Alert::onForm('symbol') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('format', ' has-error') }}">

									<label for="format" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/pricing::currencies/model.general.format_help') }}}"></i>
										{{{ trans('sanatorium/pricing::currencies/model.general.format') }}}
									</label>

									<input type="text" class="form-control" name="format" id="format" placeholder="{{{ trans('sanatorium/pricing::currencies/model.general.format') }}}" value="{{{ input()->old('format', $currency->format) }}}">

									<span class="help-block">{{{ Alert::onForm('format') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('short_format', ' has-error') }}">

									<label for="short_format" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/pricing::currencies/model.general.short_format_help') }}}"></i>
										{{{ trans('sanatorium/pricing::currencies/model.general.short_format') }}}
									</label>

									<input type="text" class="form-control" name="short_format" id="short_format" placeholder="{{{ trans('sanatorium/pricing::currencies/model.general.short_format') }}}" value="{{{ input()->old('short_format', $currency->short_format) }}}">

									<span class="help-block">{{{ Alert::onForm('short_format') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('locale', ' has-error') }}">

									<label for="locale" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/pricing::currencies/model.general.locale_help') }}}"></i>
										{{{ trans('sanatorium/pricing::currencies/model.general.locale') }}}
									</label>

									<input type="text" class="form-control" name="locale" id="locale" placeholder="{{{ trans('sanatorium/pricing::currencies/model.general.locale') }}}" value="{{{ input()->old('locale', $currency->locale) }}}">

									<span class="help-block">{{{ Alert::onForm('locale') }}}</span>

								</div>


							</div>

						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($currency)
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
