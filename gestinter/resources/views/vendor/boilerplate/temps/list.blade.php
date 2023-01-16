@extends('boilerplate::layout.index', [
    'title' => __('boilerplate::temps.title'),
    'subtitle' => __('boilerplate::temps.list.title'),
    'breadcrumb' => [__('boilerplate::temps.title')]
])

@section('content')
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">{{ __('boilerplate::temps.list.title') }}</h3>
        </div>
        <div class="box-body">
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
            {{ Form::open(['route' => 'temps', 'method' => 'get', 'autocomplete' => 'off', 'id' => 'formSearch', 'enctype' => 'multipart/form-data']) }}
                <div class="col-md-4 col-sm-12 col-xs-12">
                @if(Auth::user()->hasRole('admin'))
                    {{ Form::select('user',$users,'', array('class'=>'form-control')) }}
                @else
                    {{ Form::select('user',$users,Auth::user()->id, array('class'=>'form-control', 'disabled'=>'disabled')) }}
                    {{ Form::hidden('user',Auth::user()->id) }}
                @endif
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                @if(Auth::user()->hasRole('admin'))
                    {{ Form::select('month', $months, date("n"), array('class'=>'form-control', 'id'=>'month')) }}
                @else
                    {{ Form::select('month', $months, date("n")-1, array('class'=>'form-control', 'id'=>'month')) }}
                @endif
                </div>
                <div class="col-md-2 col-sm-9 col-xs-9">
                    {{ Form::selectYear('year', 2000, date("Y"),date("Y"), array('class' => 'form-control ','id' => 'year')) }}
                </div>
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></button>             
                </div>
            {{ Form::close() }}
            </div> 
            <table class="table table-striped table-hover va-middle" id="temps-table">
                <thead>
                <tr>
                    <th>{{ __('boilerplate::temps.list.intervention') }}</th>
                    <th>{{ __('boilerplate::temps.list.projet') }}</th>
                    <th>{{ __('boilerplate::temps.list.date') }}</th>
                    <th>{{ __('boilerplate::temps.list.debut') }}</th>
                    <th>{{ __('boilerplate::temps.list.fin') }}</th>
                    <th>{{ __('boilerplate::temps.list.dure') }}</th>
                    <th>{{ __('boilerplate::temps.list.pause') }}</th>
                    <th>{{ __('boilerplate::temps.list.route') }}</th>
                    <th>{{ __('boilerplate::temps.list.repasmidi') }}</th>
                    <th>{{ __('boilerplate::temps.list.repassoir') }}</th>
                    <th>{{ __('boilerplate::temps.list.grand') }}</th>
                    <th>{{ __('boilerplate::temps.list.autonome') }}</th>
                    <th>{{ __('boilerplate::temps.list.logistique') }}</th>
                    <th>{{ __('boilerplate::temps.list.supplement') }}</th>
                    <th class="hidden">{{ __('boilerplate::temps.list.commentaire') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tiemposConso as $tiempo)  
                    <tr>
                        <td> 
                            {{ $tiempo->IDINTERVENTION }}
                        </td>
                        <td> 
                            {{ $tiempo->LIB50 }}
                        </td>
                        <td>
                            {{ date('d/m/Y',strtotime($tiempo->DT_REAL)) }}
                        </td>
                        <td>
                            {{ date('H:i',strtotime($tiempo->DHDEB)) }}
                        </td>
                        <td>
                            {{ date('H:i',strtotime($tiempo->DHFIN)) }}
                        </td>
                        <td>
                            <?php
                             $horas = substr($tiempo->DUREE_CONSO, 1, 2);
                             $minutos = substr($tiempo->DUREE_CONSO, 3, 2);
                            ?>
                            {{ str_pad($horas, 2, "0", STR_PAD_LEFT).':'.str_pad($minutos, 2, "0", STR_PAD_LEFT) }}
                        </td>
                        <td>
                            {{ date('H:i',strtotime($tiempo->PAUSE)) }}
                        </td>
                        <td>
                            {{ date('H:i',strtotime($tiempo->ROUTE)) }}
                        </td>
                        <td>
                            {{ $tiempo->REPMIDI }}
                        </td>
                        <td>
                            {{ $tiempo->REPSOIR }}
                        </td>
                        <td>
                            {{ $tiempo->NUITEE }}
                        </td>
                        <td>
                            {{ $tiempo->AUTONOME }}
                        </td>
                        <td>
                            {{ $tiempo->LOGISTIQUE }}
                        </td>
                        <td>
                            {{ $tiempo->SUPPLEMENT }}
                        </td>
                        <td class="hidden">
                            {{ $tiempo->LIBCONSO }}
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

        

        $('#temps-table').dataTable({
            info: false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    messageTop: $("select[name=user] option:selected" ).text().toUpperCase()+" - "+$("select[name=month] option:selected" ).text().toUpperCase()+" "+$("select[name=year] option:selected" ).text(),
                    className: 'btn btn-primary',
                },
                {
                    extend: 'excel',
                    messageTop: $("select[name=user] option:selected" ).text().toUpperCase()+" - "+$("select[name=month] option:selected" ).text().toUpperCase()+" "+$("select[name=year] option:selected" ).text(),
                    className: 'btn btn-primary',
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    text: 'Signer(PDF)',
                    className: 'btn btn-primary',
                    pageSize: 'A4',
                    orientation: 'landscape',
                    exportOptions: {
                        columns: ':visible',
						search: 'applied',
						order: 'applied'
                    },
                    action: function ( e, dt, node, config ) 
                    {
                        $.ajax({
                                url: "{{ route('existtempspdf') }}",
                                type: "post",
                                data: {
                                    user: $("select[name=user] option:selected" ).val(),
                                    month: $("select[name=month] option:selected" ).val(),
                                    month_text: $("select[name=month] option:selected" ).text().toUpperCase(),
                                    year: $("select[name=year] option:selected" ).val()
                                },
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                cache: false,
                                success: function(resp) 
                                {
                                    if(resp==='exist')
                                    {
                                        growl("{!! __('boilerplate::temps.list.error_signe') !!}", 'error');
                                    }
                                    else
                                    {

                                        bootbox.confirm("{!! __('boilerplate::temps.list.error_exist') !!}", function(confirmed)
                                        {
                                            if(confirmed == true)
                                            {
                                                $.ajax({
                                                        url: "{{ route('tempspdf') }}",
                                                        type: "post",
                                                        data: {
                                                            user: $("select[name=user] option:selected" ).val(),
                                                            month: $("select[name=month] option:selected" ).val(),
                                                            month_text: $("select[name=month] option:selected" ).text().toUpperCase(),
                                                            year: $("select[name=year] option:selected" ).val()
                                                        },
                                                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                                        cache: false,
                                                        success: function(resp) 
                                                        {
                                                            const a = document.createElement("a");
                                                            a.setAttribute('href', resp);
                                                            a.setAttribute('target', '_blank');
                                                            a.click();
                                                            growl("{!! __('boilerplate::temps.list.pdf') !!}", "success");
                                                        }
                                                });
                                            }
                                        });

                                    }
                              
                                }
                        });
                           
                        
                    }
                }
            ]
        });
        
        $('#temps-table').on('click', '.destroy', function (e) {
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

        @if(!Auth::user()->hasRole('admin'))
            
            viewMonth();
            
            $('#year').on('change', function (e) {
                viewMonth();
            });

            
        @endif
    });

    function viewMonth() {
        var d = new Date();
        var month = d.getMonth();
        if($('#year option:selected').val()==d.getFullYear())
        {
            if(month==0)
            {
                $('#month').val(12).change()
                $('#year').val(d.getFullYear()-1).change();
            }
            else
            {
                $("#month option").each(function(){
                    if($(this).val()>month)
                        $(this).attr('disabled', true);
                });
            }
        }
        else{
            $("#month option").each(function(){
                $(this).attr('disabled', false);
            });
        } 
    }

</script>
@endpush