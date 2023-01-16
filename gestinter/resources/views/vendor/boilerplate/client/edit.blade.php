@extends('boilerplate::layout.index', [
    'title' => __('boilerplate::clients.title'),
    'subtitle' => __('boilerplate::clients.edit.title'),
    'breadcrumb' => [
        __('boilerplate::clients.title') => 'clients.index',
        __('boilerplate::clients.edit.title')
    ]
])

@section('content')
       
        <div class="row">
            <div class="col-sm-12 mbl">
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('clients.index') }}" class="btn btn-default">
                        {{ __('boilerplate::clients.list.title') }}
                    </a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                   
                    <div class="box-body">
                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                            {{ Form::label('IDCONTACT', __('boilerplate::clients.id')) }}
                            <br>{{ $client->IDCONTACT }}
                            {!! $errors->first('IDCONTACT','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                            {{ Form::label('NOMFAMILLE', __('boilerplate::clients.lastname')) }}
                            <br>{{ $client->NOMFAMILLE }}
                            {!! $errors->first('NOMFAMILLE','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                            {{ Form::label('PRENOM', __('boilerplate::clients.firstname')) }}
                            <br>{{ $client->PRENOM }}
                            {!! $errors->first('PRENOM','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                            {{ Form::label('RAISON_SOCIALE', __('boilerplate::clients.name')) }}
                            <br>{{ $client->RAISON_SOCIALE }}
                            {!! $errors->first('RAISON_SOCIALE','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                            {{ Form::label('ADRESSEP', __('boilerplate::clients.address')) }}
                            <br>@if(isset($client->ADRESSEP1) && !empty($client->ADRESSEP1))<a href="http://maps.google.com/?daddr={{ $client->ADRESSEP1 }}+{{ (isset($client->CPOSTALP))?$client->CPOSTALP:'' }}+{{ (isset($client->VILLEP))?$client->VILLEP:'' }}" target="_blank" class="active" role="button">{{ $client->ADRESSEP1 }}</a>@endif
                            <br>@if(isset($client->ADRESSEP2) && !empty($client->ADRESSEP2))<a href="http://maps.google.com/?daddr={{ $client->ADRESSEP2 }}+{{ (isset($client->CPOSTALP))?$client->CPOSTALP:'' }}+{{ (isset($client->VILLEP))?$client->VILLEP:'' }}" target="_blank" class="active" role="button">{{ $client->ADRESSEP2 }}</a>@endif
                    
                            {!! $errors->first('ADRESSEP1','<p class="text-danger"><strong>:message</strong></p>') !!}
                            {!! $errors->first('ADRESSEP2','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                            {{ Form::label('CPOSTALP', __('boilerplate::clients.cp')) }}
                            <br>{{ $client->CPOSTALP }}
                            {!! $errors->first('CPOSTALP','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                            {{ Form::label('VILLEP', __('boilerplate::clients.ville')) }}
                            <br>{{ $client->VILLEP }} 
                            {!! $errors->first('VILLEP','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                      
                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                            {{ Form::label('EMAILP', __('boilerplate::clients.email')) }}
                            <br><a href="mailto:{{ $client->EMAILP }}">{{ $client->EMAILP }}</a>
                            {!! $errors->first('EMAILP','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                            {{ Form::label('TELP', __('boilerplate::clients.tlf')) }}
                            <br>@if(isset($client->TELP1) && !empty($client->TELP1))<a href="tel:{{ str_replace('-','',$client->TELP1) }}" class="active" role="button">{{ $client->TELP1 }}</a>@endif
                            <br>@if(isset($client->TELP2) && !empty($client->TELP2))<a href="tel:{{ str_replace('-','',$client->TELP2) }}" class="active" role="button">{{ $client->TELP2 }}</a>@endif
                            {!! $errors->first('TELP1','<p class="text-danger"><strong>:message</strong></p>') !!}
                            {!! $errors->first('TELP2','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                       
                    </div>
                   
                    
                    </div>
                </div>
            </div>
          
            <div>
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('boilerplate::clients.documents') }}</h3>
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


           
        
        
 
        
        
        
        @push('js')   
        <script>
            $(document).ready(function() {
                    $(".file-tree").filetree();
            });
        </script>

        @endpush
@endsection