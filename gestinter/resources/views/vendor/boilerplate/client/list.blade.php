@extends('boilerplate::layout.index', [
    'title' => __('boilerplate::clients.title'),
    'subtitle' => __('boilerplate::clients.list.title'),
    'breadcrumb' => [__('boilerplate::clients.title')]
])

@section('content')
<style type="text/css">

.mostrar{
            display: none;
       }
@media screen and (max-width: 600px) {
      #clients-table{
           width:100%;
       }
       #clients-table thead {
           display: none;
       }
       #clients-table tr:nth-of-type(2n) {
           background-color: inherit;
       }
       #clients-table tr td:first-child {
           background: #f0f0f0;
           font-weight:bold;
           font-size:1em;
           padding: 2px !important;
       }
       #clients-table tbody td {
           display: block;
           text-align:left;
           padding: 1px !important;
           border-top-style: none !important;
       }
       #clients-table tbody td:before {
           content: attr(data-th);
           display: block;
       }
       #clients-table .ocultante, .dataTables_length, .dataTables_info{
            display: none !important;
       }
       #clients-table .mostrar{
            display: inline-block;
            float: right;
       }
       #clients-table .reducir{
            padding: 3px 7px !important;
            font-size: 10px !important;
       }
       #clients-table .aumentar{
            font-size:1.2em;
       }
    
}
</style>
    <div class="row">
        <div class="col-sm-12 mbl">
            <span class="pull-right">
                <a href="{{ URL::route("clients.create") }}" class="btn btn-primary disabled">{{ __('boilerplate::clients.create.title') }}</a>
            </span>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">{{ __('boilerplate::clients.list.title') }}</h3>
        </div>
        <div class="box-body">
            <table class="table table-striped table-hover va-middle" id="clients-table">
                <thead>
                <tr>
                    <th>{{ __('boilerplate::clients.id') }}</th>
                    <th>{{ __('boilerplate::clients.name') }}</th>
                    <th>{{ __('boilerplate::clients.lastname') }}</th>
                    <th>{{ __('boilerplate::clients.tlf') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td class="ocultante">
                            <strong>{{ $client->IDCONTACT }}</strong>
                        </td>
                        <td>
                            <a href="{{ URL::route('clients.edit', $client->IDCONTACT) }}">{{ $client->RAISON_SOCIALE }} </a>
                            <div class="mostrar">
                                <a href="{{ URL::route('clients.edit', $client->IDCONTACT) }}" class="btn btn-sm btn-primary">
                                    <span class="fa fa-pencil"></span>
                                </a>

                                <a href="{{ URL::route('clients.destroy', $client->IDCONTACT) }}" class="btn btn-sm btn-danger destroy disabled">
                                    <span class="fa fa-trash"></span>
                                </a>
                            </div>
                        </td>
                        <td>
                            {{ $client->PRENOM }} {{ $client->NOMFAMILLE }}
                        </td>
                        <td>
                            @if(isset($client->TELP1) && !empty($client->TELP1))<a href="tel:{{ str_replace('-','',$client->TELP1) }}" class="active" role="button">{{ $client->TELP1 }}</a>@endif
                            <br>@if(isset($client->TELP2) && !empty($client->TELP2))<a href="tel:{{ str_replace('-','',$client->TELP2) }}" class="active" role="button">{{ $client->TELP2 }}</a>@endif
                        </td>
                        <td class="ocultante">

                            <a href="{{ URL::route('clients.edit', $client->IDCONTACT) }}" class="btn btn-sm btn-primary">
                                <span class="fa fa-pencil"></span>
                            </a>

                                <a href="{{ URL::route('clients.destroy', $client->IDCONTACT) }}" class="btn btn-sm btn-danger destroy disabled">
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

        $('#clients-table').dataTable(
            {
                responsive: true,
                "order": [[1,"asc"]],
                info: false,
                "columnDefs": [
                    { "width": "10%", "targets": [0] },
                    { "width": "30%", "targets": [1] },
                    { "width": "25%", "targets": [2,3] },
                    { "width": "10%", "orderable": false , "searchable": false, "targets": [4] }
                ]
            }
        );

        $('#clients-table').on('click', '.destroy', function (e) {
            e.preventDefault();

            var href = $(this).attr('href');
            var line = $(this).closest('tr');

            bootbox.confirm("{{ __('boilerplate::clients.list.confirmdelete') }}", function (result) {
                if (result === false) return;

                $.ajax({
                    url: href,
                    method: 'delete',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function(){
                        line.remove();
                        growl("{{ __('boilerplate::clients.list.deletesuccess') }}", 'success');
                    }
                });
            });
        });
    });
</script>
@endpush