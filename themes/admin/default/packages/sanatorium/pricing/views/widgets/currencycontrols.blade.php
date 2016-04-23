<div class="panel no-border no-margin widget-loader-circle">
	<div class="panel-heading">
		<div class="panel-title">{{ trans('sanatorium/pricing::currencycontrols.title') }}
		</div>
		<div class="panel-controls">
			<ul>
				<li><a href="#" class="portlet-refresh text-black" data-toggle="refresh"><i class="portlet-icon portlet-icon-refresh"></i></a>
				</li>
			</ul>
		</div>
	</div>
	<div class="widget-16-header padding-20">
		<span class="icon-thumbnail bg-master-light pull-left text-master">{{ trans('sanatorium/pricing::currencycontrols.shortcut') }}</span>
		<div class="pull-left">
			<p class="hint-text all-caps font-montserrat  small no-margin overflow-ellipsis ">{{ trans('sanatorium/pricing::currencycontrols.subtitle') }}</p>
			<h5 class="no-margin overflow-ellipsis ">{{ trans('sanatorium/pricing::currencycontrols.subtitle_desc') }}</h5>
		</div>
		<div class="clearfix"></div>
	</div>
	@if ( Route::has('admin.sanatorium.pricingexchangecnb.exchange.history') )
	<div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
		<p class="pull-left">
			<a href="{{ route('admin.sanatorium.pricingexchangecnb.exchange.history') }}">
				{{ trans('sanatorium/pricing::currencycontrols.history') }}
			</a>
		</p>
		<div class="clearfix"></div>
	</div>
	@endif
	@if ( Route::has('admin.sanatorium.pricingexchangecnb.exchange') )
	<div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
		<p class="pull-left">
			<a href="{{ route('admin.sanatorium.pricingexchangecnb.exchange') }}">
				{{ trans('sanatorium/pricing::currencycontrols.force') }}
			</a>
		</p>
		<div class="clearfix"></div>
	</div>
	@endif
</div>
