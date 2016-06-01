@section('scripts')
<script type="text/javascript">
$(function(){
	$('[data-price-control]').change(function(){

		// Manual/automatic switch
		$('.manual-control').each(function(){

			// Disable automatic controls
			if ( $(this).is(':checked') ) {
				$(this).parents('.row:first').find('[type="text"][data-price-control]').attr('disabled', false);
			} else {
				$(this).parents('.row:first').find('[type="text"][data-price-control]').attr('disabled', 'disabled');
			}
		});

		$('[data-price-control]').each(function(){
			// If input is disabled, try to calculate value
			if ( $(this).is(':disabled') ) {

				if ( $sibling = $(this).parents('[data-currency-group]:first').find('input:not(:disabled)') ) {
					// First priority is price in same currency with/without tax
					console.log( ' + ' + $sibling.val() );
				} else if ( $primary = $('[data-primary="1"]').find('input[data-type="'+$(this).data('type')+'"]') ) {
					console.log($primary.val());
					// Second priority is primary currency price
					$(this).val($primary.val() * $(this).data('ratio'));
				}
			}
		});
	});
});
</script>
@parent
@stop

@section('styles')
<style type="text/css">
.panel.panel-default.panel-currency .panel-body {
	padding: 15px;
}
#pricing .panel-footer.panel-footer-currency {
	height: 45px;
}
</style>
@parent
@stop


<div class="row">
	@foreach( $currencies as $currency )
		<div class="col-sm-4">
			<div class="panel panel-default panel-currency">
				<div class="panel-heading">
					{{ $currency->name }}
				</div>

				<div class="panel-body" data-currency-group="{{ $currency->code }}" data-primary="{{ $primary_currency->code == $currency->code ? '1' : '0' }}">
				
				@foreach( $types as $type => $type_name )
					<div class="row" data-type="{{ $type }}" data-currency-code="{{ $currency->code }}">
						<div class="col-sm-6">
							<input type="text" class="form-control {{ $entity->getPriceObject($currency->code, $type)->manual ? '' : 'disabled' }}" name="prices[{{ $type }}][{{ $currency->code }}][value]" data-price-control placeholder="{{ $type_name }}" data-ratio="{{ $currency->unit }}" data-type="{{ $type }}" data-currency-code="{{ $currency->code }}" value="{{ $entity->getPrice($type, 1, $currency->id) }}" {{ $entity->getPriceObject($currency->code, $type)->manual ? '' : 'disabled' }}>
						</div>
						<div class="col-sm-6">
							<label for="manual-{{ $currency->code }}-{{ $type }}">
								<input type="checkbox" name="prices[{{ $type }}][{{ $currency->code }}][manual]" value="1" data-price-control class="manual-control" id="manual-{{ $currency->code }}-{{ $type }}" {{ $entity->getPriceObject($currency->code, $type)->manual ? 'checked' : '' }}>
								{{ trans('sanatorium/pricing::price/model.manual') }}
							</label>
						</div>
					</div>
				@endforeach
				</div>

				<div class="panel-footer">
					<div class="row">
						<div class="col-sm-12">
							<small>{{ trans('sanatorium/pricing::price/model.rate') }} <code>{{ $currency->unit }} {{ $primary_currency->code }}</code></small>
						</div>
					</div>
				</div>
				<div class="panel-footer panel-footer-currency">
					<div class="row">
						<div class="col-sm-12">
							<label for="primary-{{ $currency->code }}" class="control-label">
								<input type="radio" data-price-control name="prices[primary]" id="primary-{{ $currency->code }}" value="{{ $currency->code }}" {{ $entity->getPriceObject($currency->code, $type)->primary || $primary_currency->code == $currency->code ? 'checked' : '' }}>
								{{ trans('sanatorium/pricing::price/model.primary') }}
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
</div>


