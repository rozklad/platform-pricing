
@if ( count($currencies) > 0 )
	<ul class="{{ $class }}">
		
		<li class="dropdown">

			<a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown">
				
					{{ strtoupper($active_currency->code) }}
					
					<b class="caret"></b>

			</a>

			<ul class="dropdown-menu" role="menu">
				
				@foreach($currencies as $currency)

					<li><a href="{{ route('sanatorium.pricing.currencies.set', $currency->id) }}">{{ strtoupper($currency->code) }}</a></li>

				@endforeach

			</ul>

		</li>
		
	</ul>
@endif