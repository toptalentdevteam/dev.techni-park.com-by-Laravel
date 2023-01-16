@extends('boilerplate::layout.index', [
    'title' => 'Tableau de bord',
    'subtitle' => '',
    'breadcrumb' => ['']]
)

@section('content')
@include('boilerplate::load.datepicker')

@push('js')
<script>
    $('#daterange-btn span').html('{{ $fechaFrom }} - {{ $fechaTo }}');
    $('#daterange-btn').daterangepicker(
        {
            "locale": {
                "format": "DD/MM/YYYY",
            },
            ranges: {
                "Aujourd'hui": [moment(), moment()],
                "Les 7 derniers jours": [moment().subtract(6, 'days'), moment()],
                "Les 30 derniers jours": [moment().subtract(30, 'days'), moment()],
                "L'année écoulée": [moment().subtract(1, 'years'), moment()],
                "Toutes les interventions": [moment('2000-01-01'), moment('2099-12-31')]
            },
            startDate: moment().startOf('month'),
            endDate: moment()
        },
        function (start, end) {
            $('#daterange-btn span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            //Aquí va si queremos actualizar
            var url = window.location.origin;    
            url += '?start='+ start.format('YYYY-MM-DD')+'&end='+ end.format('YYYY-MM-DD');
            window.location.href = url;
        }
    );
    $('.range_inputs').css('font-size','0px');
    $('.range_inputs *').css('display','none');
    $('.ranges li:last-child').css('display','none');
    $('.daterangepicker .calendar').css('display','none');

</script>
@endpush
<style type="text/css">
.btn-cir{
  width: 40px;
  height: 40px;
  text-align: center;
  font-size: 12px;
  line-height: 1.428571429;
  border-radius: 25px;
}
.btn-cir-list{
  width: 20px;
  height: 20px;
  text-align: center;
  padding: 6px 0;
  font-size: 12px;
  line-height: 1.428571429;
  border-radius: 15px;
}
.progress{
    height:4px !important;
}
</style>

<div class="row">
    <div class="form-group">
        <div class="input-group">
                <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                    <span>
                      <i class="fa fa-calendar"></i> Sélecteur de dates
                    </span>
                    <i class="fa fa-caret-down"></i>
                </button>
        </div>
    </div> 
</div> 
<div class="row">
     <div class="col-md-4">
        <div class="info-box level level-all ">
            <span class="info-box-icon">
                <button type="button" class="btn btn-success btn-cir"></button>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Traitees</span>
                    <span class="info-box-number">
                        {{ $enattente }} interventions - {{ ($total>0)?round(($enattente*100)/$total):0 }} %
                    </span>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ ($total>0)?($enattente*100)/$total:0 }}%; background-color:#00a65a;"></div>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box level level-all ">
            <span class="info-box-icon">
                <button type="button" class="btn btn-warning btn-cir"></button>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">En cours</span>
                    <span class="info-box-number">
                    {{ $encurso }} interventions - {{ ($total>0)?round(($encurso*100)/$total):0 }} %
                    </span>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: {{ ($total>0)?($encurso*100)/$total:0 }}%; background-color: #f39c12;"></div>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box level level-all ">
            <span class="info-box-icon">
                <button type="button" class="btn btn-danger btn-cir"></button>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">A faire</span>
                    <span class="info-box-number">
                        {{ $afaire }} interventions - {{ ($total>0)?round(($afaire*100)/$total):0 }} %
                    </span>
                    <div class="progress">
                        <div class="progress-bar bg-danger" style="width: {{ ($total>0)?($afaire*100)/$total:0  }}%; background-color: #dd4b39;"></div>
                    </div>
            </div>
        </div>
    </div>
</div>
<div class="row">       
    <div class="panel panel-default">
        <div class="panel-heading">Intervention</div>
        <div class="panel-body">
            <table class="table table-striped table-hover va-middle">
                <thead>
                <tr>
                    <th class="hidden">{{ __('boilerplate::tasks.id') }}</th>
                    <th>{{ __('boilerplate::tasks.status') }}</th>
                    <th>{{ __('boilerplate::tasks.nrointer') }}</th>
                    <th>{{ __('boilerplate::tasks.designation') }}</th>
                    <th>{{ __('boilerplate::tasks.date') }}</th>
                    <th>{{ __('boilerplate::tasks.nroproyect') }}</th>
                    <th>{{ __('boilerplate::tasks.affaire') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
                <?php
                    switch ($task->STATEID) {
                        case 3:
                            $colorStatus='btn-warning';
                            break;
                        case 2:
                            $colorStatus='btn-warning';
                            break;
                        case 1:
                            $colorStatus='btn-danger';
                            break;
                        default:
                            $colorStatus='btn-success';
                            break;
                    }
                ?>    
                <tr>
                        <td class="hidden">{{ $task->IDINTERVENTION }}</td>
                        <td>
                            <button title="{{ $task->STATE }}" type="button" class="btn {{ $colorStatus }} btn-cir-list"></button>

                        </td>
                        <td>{{ $task->IDINTERVENTION }}</td>
                        <td>
                            <strong><a href="{{ URL::route('tasks.edit', $task->IDINTERVENTION) }}">{{ str_limit($task->LIB50, 25, "...") }}</a></strong>
                        </td>
                        <td>
                            {{ date('d/m/Y',strtotime($task->DT_INTER_DBT)) }}
                        </td>
                        <td>
                            {{ (isset($task->proyect))?$task->proyect->IDPROJET:'' }}
                        </td>
                        
                        <td>
                            {{ (isset($task->proyect))?str_limit($task->proyect->NOMPROJET, 25, "..."):'' }}
                           
                        </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @if(isset($_GET['start']))
                <p>{!! $tasks->appends(array('start'=>$_GET['start'],'end'=>$_GET['end']))->setPath('/') !!} </p>
            @else
                 <p>{{ $tasks->links() }} </p>
            @endif
        </div>
    </div>
</div>

@endsection