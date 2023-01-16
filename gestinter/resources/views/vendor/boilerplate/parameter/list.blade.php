@extends('boilerplate::layout.index', [
    'title' => __('boilerplate::parameters.title'),
    'subtitle' => __('boilerplate::parameters.list.title'),
    'breadcrumb' => [__('boilerplate::parameters.title')]
])

@section('content')
<style type="text/css">

.mostrar{
            display: none;
       }
@media screen and (max-width: 600px) {
      #parameters-table{
           width:100%;
       }
       #parameters-table thead {
           display: none;
       }
       #parameters-table tr:nth-of-type(2n) {
           background-color: inherit;
       }
       #parameters-table tr td:first-child {
           background: #f0f0f0;
           font-weight:bold;
           font-size:1em;
           padding: 2px !important;
       }
       #parameters-table tbody td {
           display: block;
           text-align:left;
           padding: 1px !important;
           border-top-style: none !important;
       }
       #parameters-table tbody td:before {
           content: attr(data-th);
           display: block;
       }
       #parameters-table .ocultante, .dataTables_length, .dataTables_info{
            display: none !important;
       }
       #parameters-table .mostrar{
            display: inline-block;
            float: right;
       }
       #parameters-table .reducir{
            padding: 3px 7px !important;
            font-size: 10px !important;
       }
       #parameters-table .aumentar{
            font-size:1.2em;
       }
    
}
</style>
    <div class="row">
        <div class="col-sm-12 mbl">
            <span class="pull-right">
                <a href="{{ URL::route("parameters.create") }}" class="btn btn-primary">{{ __('boilerplate::parameters.create.title') }}</a>
            </span>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">{{ __('boilerplate::parameters.list.title') }}</h3>
        </div>
        <div class="box-body">
            <table class="table table-striped table-hover va-middle" id="parameters-table">
                <thead>
                <tr>
                    <th>{{ __('boilerplate::parameters.list.actif') }}</th>
                    <th>{{ __('boilerplate::parameters.list.module') }}</th>
                    <th>{{ __('boilerplate::parameters.list.description') }}</th>
                    <th>{{ __('boilerplate::parameters.list.identifiant') }}</th>
                    <th>{{ __('boilerplate::parameters.list.designation') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($parametres as $parametre)
                    <tr>
                        <td>
                            @if($parametre->actif==1) <span class="label label-success">OUI</span> @else <span class="label label-warning">NON</span> @endif
                        </td>
                        <td>
                            {{ $parametre->module }}
                        </td>
                        <td>
                            {{ $parametre->description }}
                        </td>
                        <td>
                            {{ $parametre->identifiant }}
                        </td>
                        <td>
                            {{ ($parametre->module=='PROJET')?$parametre->NOMPROJET:(($parametre->module=='INTERVENTION')?$parametre->LIB50:$parametre->first_name." ".$parametre->last_name) }}
                        </td>
                        <td class="ocultante">
                            <a href="{{ URL::route('parameters.edit', $parametre->id) }}" class="btn btn-sm btn-primary">
                                <span class="fa fa-pencil"></span>
                            </a>
                            <a href="{{ URL::route('parameters.destroy', $parametre->id) }}" class="btn btn-sm btn-danger destroy">
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

@push('js')
<script>
    $(function () {

        $('#parameters-table').dataTable(
            {
                responsive: true,
                "order": [[1,"asc"]],
                info: false,
                "columnDefs": [
                    { "width": "10%", "targets": [0] },
                    { "width": "20%", "targets": [1,2,3,4] },
                    { "width": "10%", "orderable": false , "searchable": false, "targets": [4] }
                ]
            }
        );

        $('#parameters-table').on('click', '.destroy', function (e) {
            e.preventDefault();

            var href = $(this).attr('href');
            var line = $(this).closest('tr');

            bootbox.confirm("{{ __('boilerplate::parameters.list.confirmdelete') }}", function (result) {
                if (result === false) return;

                $.ajax({
                    url: href,
                    method: 'delete',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function(){
                        line.remove();
                        growl("{{ __('boilerplate::parameters.list.deletesuccess') }}", 'success');
                    }
                });
            });
        });
    });
</script>
@endpush