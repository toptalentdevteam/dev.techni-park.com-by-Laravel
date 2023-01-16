@extends('boilerplate::layout.index', [
    'title' => __('boilerplate::proyects.title'),
    'subtitle' => __('boilerplate::proyects.list.title'),
    'breadcrumb' => [__('boilerplate::proyects.title')]
])

@section('content')
<style type="text/css">

.mostrar{
            display: none;
       }
@media screen and (max-width: 600px) {
      #proyects-table{
           width:100%;
       }
       #proyects-table thead {
           display: none;
       }
       #proyects-table tr:nth-of-type(2n) {
           background-color: inherit;
       }
       #proyects-table tr td:first-child {
           background: #f0f0f0;
           font-weight:bold;
           font-size:1em;
           padding: 2px !important;
       }
       #proyects-table tbody td {
           display: block;
           text-align:left;
           padding: 1px !important;
           border-top-style: none !important;
       }
       #proyects-table tbody td:before {
           content: attr(data-th);
           display: block;
       }
       #proyects-table .ocultante, .dataTables_length, .dataTables_info{
            display: none !important;
       }
       #proyects-table .mostrar{
            display: inline-block;
            float: right;
       }
       #proyects-table .reducir{
            padding: 3px 7px !important;
            font-size: 10px !important;
       }
       #proyects-table .aumentar{
            font-size:1.2em;
       }
    
}
</style>
    <div class="row">
        <div class="col-sm-12 mbl">
            <span class="pull-right">
                <a href="{{ URL::route("proyects.create") }}" class="btn btn-primary disabled">{{ __('boilerplate::proyects.create.title') }}</a>
            </span>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">{{ __('boilerplate::proyects.list.title') }}</h3>
        </div>
        <div class="box-body">
            <table class="table table-striped table-hover va-middle" id="proyects-table">
                <thead>
                <tr>
                    <th>{{ __('boilerplate::proyects.id') }}</th>
                    <th>{{ __('boilerplate::proyects.name') }}</th>
                    <th>{{ __('boilerplate::proyects.client') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($proyects as $proyect)
                    <tr>
                        <td class="ocultante">
                            <strong>{{ $proyect->IDPROJET }}</strong>
                        </td>
                        <td>
                            {{ $proyect->NOMPROJET }}
                            <div class="mostrar">
                                <a href="{{ URL::route('proyects.edit', $proyect->id) }}" class="btn btn-sm btn-primary disabled">
                                    <span class="fa fa-pencil"></span>
                                </a>

                                <a href="{{ URL::route('proyects.destroy', $proyect->id) }}" class="btn btn-sm btn-danger destroy disabled">
                                    <span class="fa fa-trash"></span>
                                </a>
                            </div>
                        </td>
                        <td>
                            {{ $proyect->RAISON_SOCIALE }}
                        </td>
                        <td class="ocultante">
                            <a href="{{ URL::route('proyects.edit', $proyect->id) }}" class="btn btn-sm btn-primary disabled">
                                <span class="fa fa-pencil"></span>
                            </a>

                                <a href="{{ URL::route('proyects.destroy', $proyect->id) }}" class="btn btn-sm btn-danger destroy disabled">
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

        $('#proyects-table').dataTable(
            {
                responsive: true,
                "order": [[1,"asc"]],
                info: false,
                "columnDefs": [
                    { "width": "10%", "targets": [0] },
                    { "width": "40%", "targets": [1] },
                    { "width": "40%", "targets": [2] },
                    { "width": "10%", "orderable": false , "searchable": false, "targets": [3] }
                ]
            }
        );

        $('#proyects-table').on('click', '.destroy', function (e) {
            e.preventDefault();

            var href = $(this).attr('href');
            var line = $(this).closest('tr');

            bootbox.confirm("{{ __('boilerplate::proyects.list.confirmdelete') }}", function (result) {
                if (result === false) return;

                $.ajax({
                    url: href,
                    method: 'delete',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function(){
                        line.remove();
                        growl("{{ __('boilerplate::proyects.list.deletesuccess') }}", 'success');
                    }
                });
            });
        });
    });
</script>
@endpush