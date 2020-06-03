<!doctype html>
<html lang="{{ Voyager::getLocale() }}" locales="{{ implode(',', Voyager::getLocales()) }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ Str::finish(route('voyager.dashboard'), '/') }}">

    <title>@yield('page-title') - {{ Voyager::setting('admin.title', 'Voyager II') }}</title>
    <link href="{{ Voyager::assetUrl('css/voyager.css') }}" rel="stylesheet">
    @foreach (resolve(\Voyager\Admin\Manager\Plugins::class)->getAllPlugins()->where('enabled') as $plugin)
        @foreach ($plugin->getCssRoutes() as $css)
        <link href="{{ $css }}" rel="stylesheet">
        @endforeach
    @endforeach
</head>

<body>
    <div id="voyager">

    </div>
</body>
<script src="{{ Voyager::assetUrl('js/voyager.js') }}"></script>
@foreach (resolve(\Voyager\Admin\Manager\Plugins::class)->getAllPlugins()->where('enabled') as $plugin)
    @foreach ($plugin->getJsRoutes() as $js)
    <script src="{{ $js }}" type="text/javascript"></script>
    @endforeach
@endforeach
<script>
/*var voyager = new Vue({
    el: '#voyager',
    mounted: function () {
        var vm = this;

        var messages = {!! Voyager::getMessages()->toJson() !!};

        messages.forEach(function (m) {
            new vm.$notification(m.message).color(m.color).timeout(m.timeout).show();
        });
    },
    created: function () {
        this.$language.localization = {!! Voyager::getLocalization() !!};
        this.$store.routes = {!! Voyager::getRoutes() !!};
        this.$store.breads = {!! json_encode(resolve(\Voyager\Admin\Manager\Breads::class)->getBreads()) !!};
        this.$store.formfields = {!! json_encode(resolve(\Voyager\Admin\Manager\Breads::class)->getFormfields()) !!};
        this.$store.debug = {{ var_export(config('app.debug') ?? false, true) }};
    }
});*/
</script>
@yield('js')
</html>