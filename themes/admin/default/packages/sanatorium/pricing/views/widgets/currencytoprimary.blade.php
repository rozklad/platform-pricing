@section('styles')
@parent
<style type="text/css">
.currency-usd {
  background-color: #85bb65;
}
.currency-eur {
  background-color: #6b6bd4;
}
</style>
@stop

<div class="widget-9 panel no-border bg-primary no-margin widget-loader-bar currency-{{ $currency->code }}">
  <div class="container-xs-height full-height">
    <div class="row-xs-height">
      <div class="col-xs-height col-top">
        <div class="panel-heading  top-left top-right">
          <div class="panel-title text-black">
            <span class="font-montserrat fs-11 all-caps">{{ $currency->name }} <i class="fa fa-chevron-right"></i>
            </span>
          </div>
          <div class="panel-controls">
            <ul>
              <li><a href="#" class="portlet-refresh text-black" data-toggle="refresh"><i class="portlet-icon portlet-icon-refresh"></i></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row-xs-height">
      <div class="col-xs-height col-top">
        <div class="p-l-20 p-t-15">
        <h3 class="no-margin p-b-5 text-white">{{ $currency->unit }} {{ ucfirst($primary->code) }}</h3>
          <span class="small hint-text">{{ trans('sanatorium/pricing::currencytoprimary.change.'.$way, ['change' => $change]) }}</span>
        </div>
      </div>
    </div>
  </div>
</div>