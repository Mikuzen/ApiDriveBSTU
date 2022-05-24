<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <title>Driver API</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            font-size: 14pt;
            max-width: 99vw;
        }

        .group-card {
            vertical-align: middle;
        }

        #file-item {
            width: 100%;
            height: 100%;
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }


    </style>
</head>
<body>

<main>
    <div class="row">
        <div class="col-2 bg-light">
            <div class="d-flex flex-column flex-shrink-0 p-3 sticky-top " style="min-height: 100vh;">
                <a href="#"
                   class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                    <img src="https://www.bstu.ru/shared/attachments/45735" width="32" height="32"
                         class="rounded-2 me-2">
                    <span class="text-info fs-4">Диск.</span>
                    <span class="text-dark fs-4">БГТУ</span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                </ul>
                <div class="row">
                    <div class="col-sm-6">
                        <form action="#" class="d-inline" method="get">

                            <button class="btn btn-dark"><strong>Войти</strong></button>
                        </form>
                    </div>
                    <div class="col-sm-6">
                        <form action="#" class="d-inline" method="get">

                            <button class="btn btn-warning"><strong>Регистрация</strong></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-10 p-1" id="app">
            @yield('content')
        </div>
    </div>
</main>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
