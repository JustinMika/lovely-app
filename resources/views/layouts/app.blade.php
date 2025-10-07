<!doctype html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
        Dashboard | Lovely
    </title>

    @includeIf('partials.admin-header-link')
    @livewireStyles
</head>

<body x-data="{ page: 'ecommerce', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }" x-init="darkMode = JSON.parse(localStorage.getItem('darkMode'));
$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))" :class="{ 'dark bg-gray-900': darkMode === true }">
    @includeIf('partials.loader')

    <div class="flex h-screen overflow-hidden">
        @include('partials.aside')
        <div class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">
            <div :class="sidebarToggle ? 'block xl:hidden' : 'hidden'"
                class="fixed z-50 h-screen w-full bg-gray-900/50"></div>
            <main>
                @includeIf('partials.header')
                <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
    @includeIf('partials.admin-footer-js')
</body>

</html>
