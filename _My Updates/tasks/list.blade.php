@extends('boilerplate::layout.index', [
    'title' => __('boilerplate::tasks.title'),
    'subtitle' => __('boilerplate::tasks.list.title'),
    'breadcrumb' => [__('boilerplate::tasks.title')]
])

@section('content')
<style type="text/css">
.btn-cir{
  width: 20px;
  height: 20px;
  text-align: center;
  padding: 6px 0;
  font-size: 12px;
  line-height: 1.428571429;
  border-radius: 15px;
}
.mostrar{
            display: none;
       }
@media screen and (max-width: 600px) {
      #tasks-table {
           width:100%;
       }
       #tasks-table thead {
           display: none;
       }
       #tasks-table tr:nth-of-type(2n) {
           background-color: inherit;
       }
       #tasks-table tr td:first-child {
           background: #f0f0f0;
           font-weight:bold;
           font-size:1em;
           padding: 2px !important;
       }
       #tasks-table tbody td {
           display: block;
           text-align:left;
           padding: 1px !important;
           border-top-style: none !important;
       }
       #tasks-table tbody td:before {
           content: attr(data-th);
           display: block;
       }
       #tasks-table .ocultante, .dataTables_length, .dataTables_info{
            display: none !important;
       }
       #tasks-table .mostrar{
            display: inline-block;
            float: right;
       }
       #tasks-table .reducir{
            padding: 3px 7px !important;
            font-size: 10px !important;
       }
       #tasks-table .aumentar{
            font-size:1.2em;
       }
    

}
</style>
    <div class="row">
        <div class="col-sm-12 mbl">
            <span class="pull-right">
            @if(isset($formulaire))
                <a href="{{ URL::route("tasks.create") }}" class="btn btn-primary">{{ __('boilerplate::tasks.create.title') }}</a>
            @endif
            </span>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">{{ __('boilerplate::tasks.list.title') }}</h3>
        </div>

        <div class="box-body">
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
            <table class="table table-striped table-hover va-middle" id="tasks-table">
                <thead>
                <tr>
                    <th></th>
                    <th>{{ __('boilerplate::tasks.date') }}</th>
                    <th>{{ __('boilerplate::tasks.nrointer') }}</th>
                    <th>{{ __('boilerplate::tasks.description') }}</th>
                    <th>{{ __('boilerplate::tasks.nroproyect') }}</th>
                    <th>{{ __('boilerplate::tasks.proyect') }}</th>
                    <th>{{ __('boilerplate::tasks.client') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
                <?php
                    switch ($task->ST_INTER) {
                        case 1:
                            $colorStatus='btn-danger';
                            break;
                        case 2:
                            $colorStatus='btn-warning';
                            break;
                        case 3:
                            $colorStatus='btn-warning';
                            break;
                        default:
                            $colorStatus='btn-success';
                            break;
                    }
                    ?>    
                    <tr>
                        <td class="ocultante"> 
                            <span class="hidden">{{ $task->ST_INTER}}</span>
                            <button type="button" class="btn {{ $colorStatus }}  btn-cir"></button>
                           
                        </td>
                        <td class="ocultante"> 
                            <span class="hidden">{{ $task->DT_INTER_DBT }}</span>
                            <strong>{{ date('d/m/Y',strtotime($task->DT_INTER_DBT)) }}</strong>
                            
                        </td>
                        <td class="ocultante">
                            {{ $task->IDINTERVENTION }}
                        </td>
                        <td>
                            <strong class="aumentar"><a href="{{ URL::route('tasks.edit', $task->IDINTERVENTION) }}">{{ str_limit($task->LIB50, 23, "...") }}</a></strong>
                            <div class="mostrar">       
                                 <strong>{{ date('d/m/Y',strtotime($task->DT_INTER_DBT)) }}</strong>
                            </div>
                        </td>
                        <td class="ocultante">
                            {{ (isset($task->proyect))?$task->proyect->IDPROJET:'' }}
                        </td>
                        <td>
                           
                            {{ (isset($task->proyect))?str_limit($task->proyect->NOMPROJET, 23, "..."):'' }}
                            <div class="mostrar">
                                <button type="button" class="btn {{ $colorStatus }}  btn-cir"></button>
                                <a href="{{ URL::route('tasks.edit', $task->IDINTERVENTION) }}" class="btn btn-sm btn-primary reducir">
                                    <span class="fa fa-pencil"></span>
                                </a>
                                <a href="{{ URL::route('tasks.destroy', $task->IDINTERVENTION) }}" class="btn btn-sm btn-danger destroy disabled reducir">
                                    <span class="fa fa-trash"></span>
                                </a>
                            </div>
                        </td>
                        <td>
                            {{ (isset($task->client))?str_limit($task->client->RAISON_SOCIALE, 23, "..."):'' }}
                        </td>
                        <td class="ocultante">
                            <a href="{{ URL::route('tasks.edit', $task->IDINTERVENTION) }}" class="btn btn-sm btn-primary">
                                <span class="fa fa-pencil"></span>
                            </a>

                                <a href="{{ URL::route('tasks.destroy', $task->IDINTERVENTION) }}" class="btn btn-sm btn-danger destroy disabled">
                                    <span class="fa fa-trash"></span>
                                </a>
                           
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
         
        </div>
    </div>
@endsection

@include('boilerplate::load.datatables')
@include('boilerplate::load.datepicker')

@push('js')
<script>
    $(function () {
            const params = new URLSearchParams(window.location.search);
            $('#tasks-table').dataTable(
            {
                info: false,
                "order": [[1,"desc"]],
                "columnDefs": [
                    { "width": "4%", "targets": [0,2,4] },
                    { "width": "7%", "targets": [1] },
                    { "width": "23%", "targets": [3,5] },
                    { "width": "25%", "targets": [6] },
                    { "width": "10%", "orderable": false , "searchable": false, "targets": [7] }
                ],
                oSearch: {
                    sSearch: (params.get('search')!=null)?params.get('search'):''
                }
            }).on('search.dt', function () {
                var valor = $('.dataTables_filter input').val();
                params.set('search', valor);
                window.history.replaceState({}, "", decodeURIComponent(`${window.location.pathname}?${params}`));
            });
        
    
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
               
                const params = new URLSearchParams(window.location.search);
                params.set('start', start.format('YYYY-MM-DD'));
                params.set('end', end.format('YYYY-MM-DD'));
                window.location.href = decodeURIComponent(`${window.location.pathname}?${params}`);

            }
        );
        $('.range_inputs').css('font-size','0px');
        $('.range_inputs *').css('display','none');
        $('.ranges li:last-child').css('display','none');
        $('.daterangepicker .calendar').css('display','none');

        $('#tasks-table').on('click', '.destroy', function (e) {
            e.preventDefault();

            var href = $(this).attr('href');
            var line = $(this).closest('tr');

            bootbox.confirm("{{ __('boilerplate::tasks.list.confirmdelete') }}", function (result) {
                if (result === false) return;

                $.ajax({
                    url: href,
                    method: 'delete',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function(){
                        line.remove();
                        growl("{{ __('boilerplate::tasks.list.deletesuccess') }}", 'success');
                    }
                });
            });
        });
    });
</script>
@endpush