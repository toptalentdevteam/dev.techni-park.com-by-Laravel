<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ mix('css/boilerplate.css') }}?ver=1.2">
    <link rel="stylesheet" href="{{ asset('css/file-explore.css') }}">
   
    @stack('css')
</head>
<body class="sidebar-mini skin-blue">
    <div class="wrapper">
        @include('boilerplate::layout.header')
        @include('boilerplate::layout.mainsidebar')
        <div class="content-wrapper">
            <section class="content-header">
                @include('boilerplate::layout.contentheader')
            </section>
            <section class="content">
                @yield('content')
            </section>
        </div>
        @include('boilerplate::layout.footer')
    </div>
    <script src="{{ mix('/js/boilerplate.js') }}"></script>
    <script src="{{ asset('/js/file-explore.js') }}"></script>
    
    <script>
        $(function() {
            bootbox.setLocale("{{ App::getLocale() }}");
            @if(Session::has('growl'))
                @if(is_array(Session::get('growl')))
                    growl("{!! Session::get('growl')[0] !!}", "{{ Session::get('growl')[1] }}");
                @else
                    growl("{{Session::get('growl')}}");
                @endif
            @endif
            $('.logout').click(function(e){
                e.preventDefault();
                if(bootbox.confirm("{{ __('boilerplate::layout.logoutconfirm') }}", function(e){
                    if(e === false) return;
                    $('#logout-form').submit();
                }));
            });
        });
    </script>
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/61d8027cf7cf527e84d0e453/1fopsjebk';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
    @stack('js')
</body>
</html>