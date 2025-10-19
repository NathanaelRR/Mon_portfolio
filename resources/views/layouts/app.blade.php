<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mon Portfolio')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

</head>
<body>

    <header style="text-align:center; margin-bottom:2rem;">
        <h1>Mon Portfolio</h1>
        <nav class="main-nav">
            <ul>
                <li><a href="/#presentation">Mon profil</a></li>
                <li><a href="/#projets">Mes projets</a></li>
                <li><a href="#contacts">Mes contacts</a></li>
            </ul>
        </nav>
        <hr>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer class="section-contacts" id="contacts">
        <hr>
        <h2>Contacts</h2>
        <ul class="contact-list">
            <li><a href="mailto:nathanael.r.r@gmail.com">nathanael.r.r@gmail.com</a></li>
            <li><a href="#">+33 6 69 65 31 91</a></li>
            <li><a href="https://www.linkedin.com/in/nathana%C3%ABl-rasoamanana-7196a6310/">LinkedIn</a></li>
        </ul>
        <p>&copy; {{ date('Y') }} par Nathanaël RASOAMANANA</p>
    </footer>

</body>
</html>
