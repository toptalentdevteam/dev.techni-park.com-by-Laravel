@if(!defined('LOAD_INPUTMASK'))

    @push('js')
        <script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    @endpush

    @php define('LOAD_INPUTMASK', true) @endphp
@endif