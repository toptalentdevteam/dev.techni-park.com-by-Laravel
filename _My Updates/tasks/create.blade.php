@extends('boilerplate::layout.index', [
    'title' => __('boilerplate::tasks.title'),
    'subtitle' => __('boilerplate::tasks.create.title'),
    'breadcrumb' => [
        __('boilerplate::tasks.title') => 'tasks.index',
        __('boilerplate::tasks.create.title')
    ]
])
<style>
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
</style>
@include('boilerplate::load.icheck')

@section('content')
    <iframe class="responsive-iframe" src={{$formulaire}} frameborder="0" marginheight="0" marginwidth="0">Chargement…</iframe>
@endsection