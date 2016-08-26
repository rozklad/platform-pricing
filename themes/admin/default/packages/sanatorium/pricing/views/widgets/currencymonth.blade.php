{{-- Chart lib --}}
{{ Asset::queue('nvd3', 'Sanatorium/pricing::nvd3/nv.d3.min.css', 'style') }}
{{ Asset::queue('d3', 'Sanatorium/pricing::nvd3/lib/d3.v3.js', 'jquery') }}
{{ Asset::queue('nvd3', 'Sanatorium/pricing::nvd3/nv.d3.min.js', 'jquery') }}
{{ Asset::queue('utils', 'Sanatorium/pricing::nvd3/src/utils.js', 'jquery') }}
{{ Asset::queue('tooltip', 'Sanatorium/pricing::nvd3/src/tooltip.js', 'jquery') }}
{{ Asset::queue('interactiveLayer', 'Sanatorium/pricing::nvd3/src/interactiveLayer.js', 'jquery') }}
{{ Asset::queue('axis', 'Sanatorium/pricing::nvd3/src/models/axis.js', 'jquery') }}
{{ Asset::queue('line', 'Sanatorium/pricing::nvd3/src/models/line.js', 'jquery') }}
{{ Asset::queue('lineWithFocusChart', 'Sanatorium/pricing::nvd3/src/models/lineWithFocusChart.js', 'jquery') }}

<div class="panel no-border widget-loader-circle no-margin">
	<div class="row">
		<div class="col-xlg-8 ">
			<div class="panel-body">
				<div class="row">
					<div class="col-xlg-8 ">
						<div class="p-l-10">
							<h2 class="pull-left">{{ trans('sanatorium/pricing::currencymonth.show') }}</h2>
						</div>
					</div>
				</div>
				<div class="line-chart" id="chart-currencymonth">
					<svg></svg>
				</div>
			</div>
		</div>
	</div>
</div>

@section('styles')
@parent
<style>
	#chart-currencymonth svg {
	  height: 400px;
	}
</style>
@stop

@section('scripts')
@parent
<script type="text/javascript">
$(function(){
	d3.json('{{ route('admin.sanatorium.pricing.currencieshistories.history') }}', function(data) {
	  nv.addGraph(function() {
	    var chart = nv.models.lineChart()
	    	.x(function(d) { return d[0] })
	    	.y(function(d) { return d[1] })
	    	.color([
	    		$.Pages.getColor('success'),
	    		$.Pages.getColor('danger'),
	    		$.Pages.getColor('primary'),
	    		$.Pages.getColor('complete'),

	    		])
	    	.showLegend(false)
	    	.margin({
	    		left: 30,
	    		bottom: 35
	    	})
	    	.useInteractiveGuideline(true);

	    chart.xAxis
	    	.axisLabel('{{ trans('sanatorium/pricing::currencymonth.axis.x') }}')
	    	.tickFormat(function(d) {
	    		return d3.time.format('%x')(new Date(d*1000))
	    	});

	    chart.yAxis
	    	.axisLabel('{{ trans('sanatorium/pricing::currencymonth.axis.y') }}');
	    
	    d3.select('#chart-currencymonth svg')
	        .datum(data)
	        .call(chart);

	    //TODO: Figure out a good way to do this automatically
	    nv.utils.windowResize(chart.update);

	    return chart;
	  });
	});
});
</script>
@stop