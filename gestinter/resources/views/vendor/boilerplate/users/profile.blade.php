@extends('boilerplate::layout.index', [
    'title' => __('boilerplate::users.profile.title'),
    'subtitle' => $user->name,
    'breadcrumb' => [
        $user->name => 'user.profile',
    ]
])

@section('content')
<style type="text/css">
.scrollBox{
    max-height: 30em;
    overflow-y: scroll;
    overflow-x: hidden;

}
.scrollBox li.folder-root.open>ul {
    max-height: none !important;
}
.container {
  position: relative;
  width: 100%;
  overflow: hidden;
  padding-top: 100%; /* 1:1 Aspect Ratio */
}

.responsive-iframe {
  width: 100%;
  height: 100%;
  border: none;
}
.btn-cir{
  width: 20px;
  height: 20px;
  text-align: center;
  padding: 6px 0;
  font-size: 12px;
  line-height: 1.428571429;
  border-radius: 15px;
}
table.mostrar{
    display: none;
}
div.mostrar{
    display: none;
}
.aumentar{
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
       #tasks-table div.mostrar{
            display: inline-block;
            float: right;
       }
       #tasks-table .reducir{
            padding: 3px 7px !important;
            font-size: 10px !important;
       }
       #tasks-table .aumentar{
            display: inline-block;
            font-size:1.2em;
       }
}
</style>
    {{ Form::open(['route' => ['user.profile'], 'method' => 'post', 'autocomplete' => 'off', 'files' => true]) }}
        <div class="row">
            <div class="col-sm-12 mbl">
                <span class="pull-right">
                    <button type="submit" class="btn btn-primary">
                        {{ __('boilerplate::users.save') }}
                    </button>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                <div class="info-box">
                    <span class="info-box-icon" style="line-height: normal">
                        <img src="{{ $user->avatar_url }}" class="avatar" alt="avatar"/>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">
                            <p class="mbn"><strong class="h3">{{ $user->name  }}</strong></p>
                            <p class="">{{ $user->getRolesList() }}</p>
                        </span>
                        <span class="info-box-more">
                            <p class="mbn text-muted">
                                {{ __('boilerplate::users.profile.subscribedsince', [
                                    'date' => $user->created_at->format(__('boilerplate::date.lFdY')),
                                    'since' => $user->created_at->ago()]) }}
                            </p>
                        </span>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header">
                        @if(is_file($user->avatar_path))
                        <span class="pull-right">
                            <button class="btn btn-xs btn-default" id="remove_avatar">
                                {{ __('boilerplate::users.profile.delavatar') }}
                            </button>
                        </span>
                        @endif
                        <h3 class="box-title">{{ __('boilerplate::users.profile.avatar') }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group {{ $errors->has('avatar') ? 'has-error' : '' }}">
                            {!! Form::file('avatar', ['id' => 'avatar']) !!}
                            {!! $errors->first('avatar','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('boilerplate::users.informations') }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                    {{ Form::label('last_name', __('boilerplate::users.lastname')) }}
                                    {{ Form::text('last_name', old('last_name', $user->last_name), ['class' => 'form-control', 'autofocus']) }}
                                    {!! $errors->first('last_name','<p class="text-danger"><strong>:message</strong></p>') !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                    {{ Form::label('first_name', __('boilerplate::users.firstname')) }}
                                    {{ Form::text('first_name', old('first_name', $user->first_name), ['class' => 'form-control']) }}
                                    {!! $errors->first('first_name','<p class="text-danger"><strong>:message</strong></p>') !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                    {{ Form::label('password', ucfirst(__('validation.attributes.password'))) }}
                                    {{ Form::password('password', ['class' => 'form-control']) }}
                                    {!! $errors->first('password','<p class="text-danger"><strong>:message</strong></p>') !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                    {{ Form::label('password_confirmation', ucfirst(__('validation.attributes.password_confirmation'))) }}
                                    {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                                    {!! $errors->first('password_confirmation','<p class="text-danger"><strong>:message</strong></p>') !!}
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="box box-primary scrollBox">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('boilerplate::users.documents') }}</h3>
                    </div>
                    <div class="box-body">
                    <div class="row">
                        <ul class="file-tree">
                            @include('boilerplate::recursive', ['documents' => $documents]) 
                        </ul>
                    </div>
                       
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Absences</h3>
                        @if(isset($formulaire))
                        <span class="pull-right">
                            <button id="formSub" type="button" class="btn btn-primary">{{ __('boilerplate::users.btnForm') }}</button>
                        </span>
                        @endif 
                    </div>
                    <div class="box-body">
                  
                        <table class="table table-striped table-hover va-middle" id="tasks-table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Motif</th>
                                <th>Description</th>
                                <th>Jours</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($absences as $absence)
                            <?php
                                switch ($absence->IVALIDE) {
                                    case 0:
                                        $colorStatus='btn-danger';
                                        break;
                                    case 1:
                                        $colorStatus='btn-success';
                                        break;
                                }
                                ?>    
                                <tr>
                                    <td class="ocultante"> 
                                        <button type="button" class="btn {{ $colorStatus }}  btn-cir"></button>
                                    </td>
                                    <td class="ocultante"> 
                                        <span class="hidden">{{ date('YmdHi',strtotime($absence->DT_DEBUT.' '.$absence->HR_DEBUT)) }}</span>
                                        <strong>{{ date('d/m/y H:i',strtotime($absence->DT_DEBUT.' '.$absence->HR_DEBUT)) }}</strong>   
                                    </td>
                                    <td>
                                        <div class="ocultante">
                                            <strong>{{ date('d/m/y H:i',strtotime($absence->DT_FIN.' '.$absence->HR_FIN)) }}</strong>  
                                        </div> 
                                        <strong class="aumentar">{{ date('d/m/y H:i',strtotime($absence->DT_DEBUT.' '.$absence->HR_DEBUT)) }} - {{ date('d/m/y H:i',strtotime($absence->DT_FIN.' '.$absence->HR_FIN)) }}</strong>
                                        <div class="mostrar">       
                                            <strong>{{ $absence->NB_JOURS }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $absence->LIB_50 }}
                                        <div class="mostrar">
                                            <button type="button" class="btn {{ $colorStatus }}  btn-cir"></button>
                                            <a href="{{ route('deleteAbsence', $absence->IDABSENCE) }}" class="btn btn-sm btn-danger destroy @if($absence->IVALIDE==1) disabled @endif reducir">
                                                <span class="fa fa-trash"></span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $absence->LIBABSENCE }}
                                    </td>
                                    <td class="ocultante">
                                        {{ $absence->NB_JOURS }}
                                    </td>                                    
   
                                    <td class="ocultante">
                                        <a href="{{ route('deleteAbsence', $absence->IDABSENCE) }}" class="btn btn-sm btn-danger destroy @if($absence->IVALIDE==1) disabled @endif">
                                            <span class="fa fa-trash"></span>
                                        </a>
                                    </td>
                                    
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    {{ Form::close() }}
    <!-- Modal Form -->
    @if(isset($formulaire))
        <div class="modal" id="modalForm">
            <div class="modal-dialog modal-md">
                 <div class="modal-content">
                    <div class="modal-header">
                        <a data-dismiss="modal" class="close">×</a>
                    </div>
                    <div class="modal-body">
                        <iframe class="responsive-iframe" src="{{ $formulaire }}" frameborder="0" marginheight="0" marginwidth="0">Chargement…</iframe>
                    </div> 
                </div>
            </div>
		</div>
    @endif
@endsection

@include('boilerplate::load.fileinput')
@include('boilerplate::load.datatables')

@push('js')
<script>
     $(function () {
        const params = new URLSearchParams(window.location.search);
            $('#tasks-table').dataTable(
            {
                info: false,
                "order": [[1,"desc"]],
                "columnDefs": [
                    { "width": "7%", "targets": [0,5] },
                    { "width": "15%", "targets": [1,2] },
                    { "width": "25%", "targets": [3,4] },
                    { "width": "6%", "orderable": false , "searchable": false, "targets": [6] }
                ],
                oSearch: {
                    sSearch: (params.get('search')!=null)?params.get('search'):''
                }
            }).on('search.dt', function () {
                var valor = $('.dataTables_filter input').val();
                params.set('search', valor);
                window.history.replaceState({}, "", decodeURIComponent(`${window.location.pathname}?${params}`));
            });

            $('#tasks-table').on('click', '.destroy', function (e) {
                e.preventDefault();

                var href = $(this).attr('href');
                var line = $(this).closest('tr');

                bootbox.confirm("Confirmez vous la suppression de l'absence ?", function (result) {
                    if (result === false) return;

                    $.ajax({
                        url: href,
                        type: 'post',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        success: function(){
                            line.remove();
                            growl("L'absence a été supprimé avec succès", 'success');
                        }
                    });
                });
            });
  
        
            $(".file-tree").filetree();
            $('#formSub').on('click', function(e){
                $('#modalForm').appendTo("body").modal('show');
            });
            $('#modalForm').on('show.bs.modal', function () {
                $('.modal-body').css('height',$( window ).height()*1.1);
            });
	

            $('#avatar').fileinput({
                showUpload: false,
                uploadAsync: false
            });

            $('#remove_avatar').on('click', function(e){
                e.preventDefault();

                bootbox.confirm("{{ __('boilerplate::users.profile.confirmdelavatar') }}", function(e){
                    if(e === false) return;

                    $.ajax({
                        url: '{{ route('user.avatardelete') }}',
                        type: 'post',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        cache: false,
                        success: function(res) {
                            $('.avatar').attr('src', "{{ asset('/images/default_user.png') }}");
                            growl("{{ __('boilerplate::users.profile.successdelavatar') }}", "success");
                            $('#remove_avatar').remove();
                        }
                    });
                })
            });

        });


</script>
@endpush