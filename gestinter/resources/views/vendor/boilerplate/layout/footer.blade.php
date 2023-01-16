<footer class="main-footer">
    <strong>
        &copy; {{ date('Y') }}
        @if(config('boilerplate.app.vendorlink'))
            <a href="{{ config('boilerplate.app.vendorlink') }}">
                {!! config('boilerplate.app.vendorname') !!}
            </a>.
        @else
            {!! config('boilerplate.app.vendorname') !!}.
        @endif
    </strong>
    {{ __('boilerplate::layout.rightsres') }}
</footer>