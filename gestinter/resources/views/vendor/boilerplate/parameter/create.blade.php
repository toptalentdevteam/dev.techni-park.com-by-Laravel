@extends('boilerplate::layout.index', [
    'title' => __('boilerplate::parameters.title'),
    'subtitle' => __('boilerplate::parameters.create.title'),
    'breadcrumb' => [
        __('boilerplate::parameters.title') => 'users.index',
        __('boilerplate::parameters.create.title')
    ]
])

@include('boilerplate::load.icheck')

@section('content')
    {{ Form::open(['route' => 'parameters.store', 'autocomplete' => 'off']) }}
        <div class="row">
            <div class="col-sm-12 mbl">
                <a href="{{ route("parameters.index") }}" class="btn btn-default">
                    {{ __('boilerplate::parameters.returntolist') }}
                </a>
                <span class="btn-group pull-right">
                    <button type="submit" class="btn btn-primary">
                        {{ __('boilerplate::parameters.save') }}
                    </button>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('boilerplate::parameters.informations') }}</h3>
                    </div>
                    <div class="box-body">
                        
                        <div class="form-group {{ $errors->has('module') ? 'has-error' : '' }}">
                            {{ Form::label('module', __('boilerplate::parameters.module')) }}
                            {{ Form::select("module", [__('boilerplate::parameters.module1') => __('boilerplate::parameters.module1'), __('boilerplate::parameters.module2') => __('boilerplate::parameters.module2'), __('boilerplate::parameters.module3') => __('boilerplate::parameters.module3'), __('boilerplate::parameters.module4') => __('boilerplate::parameters.module4')], old('module', 1), ['class' => 'form-control','id' => 'module']) }}
                            {!! $errors->first('module','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div id="proyect" class="form-group {{ $errors->has('proyect') ? 'has-error' : '' }}">
                                {{ Form::label('proyect', __('boilerplate::parameters.module1E')) }}
                                {{ Form::select("proyect", $proyects, old('proyect', 0), ['class' => 'form-control']) }}
                                {!! $errors->first('proyect','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div id="intervention" class="form-group {{ $errors->has('intervention') ? 'has-error' : '' }} hidden">
                                {{ Form::label('intervention', __('boilerplate::parameters.module2E')) }}
                                {{ Form::select("intervention", $interventions, old('intervention', 0), ['class' => 'form-control']) }}
                                {!! $errors->first('intervention','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div id="user" class="form-group {{ $errors->has('user') ? 'has-error' : '' }} hidden">
                                {{ Form::label('user', __('boilerplate::parameters.module3E')) }}
                                {{ Form::select("user", $users, old('user', 0), ['class' => 'form-control']) }}
                                {!! $errors->first('user','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                {{ Form::label('description', __('boilerplate::parameters.description')) }}
                                {{ Form::input('text', 'description', old('description'), ['class' => 'form-control', 'autofocus']) }}
                                {!! $errors->first('description','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div class="form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                            {{ Form::label('actif', __('boilerplate::parameters.status')) }}
                            {{ Form::select("actif", ['0' => __('boilerplate::parameters.inactive'), '1' => __('boilerplate::parameters.active')], old('actif', 1), ['class' => 'form-control']) }}
                            {!! $errors->first('actif','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('boilerplate::parameters.google') }}</h3>
                    </div>
                    <div class="box-body">
                        
                        <div class="form-group {{ $errors->has('form') ? 'has-error' : '' }}">
                                {{ Form::label('form', __('boilerplate::parameters.form')) }}
                                {{ Form::input('text', 'form', old('form'), ['class' => 'form-control', 'autofocus']) }}
                                {!! $errors->first('form','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div class="form-group {{ $errors->has('drive') ? 'has-error' : '' }}">
                                {{ Form::label('drive', __('boilerplate::parameters.drive')) }}
                                {{ Form::input('text', 'drive', old('drive'), ['class' => 'form-control', 'autofocus']) }}
                                {!! $errors->first('drive','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>

                    </div>
                </div>
            </div>
  
        </div>
    {{ Form::close() }}

    @push('js')   
       
    <script>

        $(document).ready(function() {
            $('#module').on('change', function(e) { 
                if ($(this).val()=='PROJET') {
                    $('#proyect').removeClass('hidden');
                    $('#intervention').addClass('hidden');
                    $('#user').addClass('hidden');
                }  
                else if($(this).val()=='INTERVENTION' || $(this).val()=='EXTRA_TIME'){
                    $('#intervention').removeClass('hidden');
                    $('#proyect').addClass('hidden');
                    $('#user').addClass('hidden');
                }
                else if($(this).val()=='ABSENCES'){
                    $('#user').removeClass('hidden');
                    $('#intervention').addClass('hidden');
                    $('#proyect').addClass('hidden');
                }
            });
        });

    </script>
    @endpush
@endsection