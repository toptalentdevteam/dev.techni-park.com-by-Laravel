@if(!defined('LOAD_DATATABLES'))

    @push('css')
    <link rel="stylesheet" href="{!! asset('/js/plugins/datatables/dataTables.bootstrap.css') !!}">
    @endpush

    @push('js')
    <script src="{!! asset('/js/plugins/datatables/jquery.dataTables.min.js') !!}"></script>
    <script src="{!! asset('/js/plugins/datatables/dataTables.bootstrap.min.js') !!}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
   
    @include('boilerplate::load.moment')

    <script src="{!! asset('/js/plugins/datatables/plugins/sorting/datetime-moment.js') !!}"></script>
    <script>
        $.extend( true, $.fn.dataTable.defaults, {
            language: {
                url: "/js/plugins/datatables/plugins/i18n/{{ $locale }}.json"
            }
        });
    </script>
    @endpush

    @php define('LOAD_DATATABLES', true) @endphp
@endif