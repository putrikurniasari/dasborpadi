<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- [Title] -->
    <title>Padi | {{$title}} </title>

    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Able Pro is a trending dashboard template built with the Bootstrap 5 design framework. It is available in multiple technologies, including Bootstrap, React, Vue, CodeIgniter, Angular, .NET, and more.">
    <meta name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard">
    <meta name="author" content="Phoenixcoded">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- [Page specific CSS] start -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
        rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- [Page specific CSS] end -->

    <!-- [Font] Family -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}" id="main-font-link" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">


    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">

    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">

    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">

    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}" />

    <!-- Buy Now Link Script -->
    <script defer src="https://fomo.codedthemes.com/pixel/CDkpF1sQ8Tt5wpMZgqRvKpQiUhpWE3bc"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: "Manrope", sans-serif !important;
        }


        .preloader {
            position: fixed;
            width: 100%;
            height: 100vh;
            background: #fff;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 400ms;
            z-index: 2000;
        }

        .preloader.hide {
            opacity: 0;
            pointer-events: none;
        }

        .preloader .preloader-text {
            color: #838383;
            text-transform: uppercase;
            letter-spacing: 8px;
            font-size: 15px;
        }

        .preloader .dots-container {
            display: flex;
            margin-bottom: 48px;
        }

        .preloader .dot {
            background: red;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin: 0 5px;
        }

        .preloader .dot.red {
            background: #53629E;
            animation: bounce 1000ms infinite;
        }

        .preloader .dot.green {
            background: #87BAC3;
            animation: bounce 1000ms infinite;
            animation-delay: 200ms;
        }

        .preloader .dot.yellow {
            background: #D6F4ED;
            animation: bounce 1000ms infinite;
            animation-delay: 400ms;
        }

        @keyframes bounce {
            50% {
                transform: translateY(16px);
            }

            100% {
                transform: translateY(0);
            }
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            top: 0;
            left: 0;
            z-index: 0;
            /* GANTI -1 menjadi 0 */
        }

        #container,
        h2 {
            position: relative;
            z-index: 1;
        }

        canvas {
            display: block;
            vertical-align: bottom;
        }
    </style>

    @stack('css')
</head>

<body class="{{ $class ?? '' }}" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical"
    data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="light">
    @auth
        <div class="preloader">

            <div class="dots-container">
                <div class="dot red"></div>
                <div class="dot green"></div>
                <div class="dot yellow"></div>
            </div>

            <div class="preloader-text">
                Loading...
            </div>
        </div>
        @include('layouts.navbars.sidebar')
        <div class="pc-header">
            @include('layouts.navbars.navbar')
        </div>

        
        <div class="pc-container">
            <div id="particles-js"></div>
            @yield('content')
        </div>

        <div class="pc-footer">
            @include('layouts.footer')
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

    @else
        <div>
            @include('layouts.navbars.navbar')
            <div id="particles-js"></div>
            <div class="full-page {{ $contentClass ?? '' }}" style="padding-top: 80px; margin-bottom: 25px;">
                <div class="content">
                    <div class="container">
                        @yield('content')
                    </div>
                </div>
                <div style="padding-left: 100px;">
                    @include('layouts.footer')
                </div>
            </div>
        </div>

    @endauth

    <script>
        const preloader = document.querySelector(".preloader");
        const preloaderDuration = 400;

        const hidePreloader = () => {
            setTimeout(() => {
                preloader.classList.add("hide");
            }, preloaderDuration);
        }

        window.addEventListener("load", hidePreloader);
    </script>

    <!-- [ Main Content ] end -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <!-- Required Js -->
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.chartjs.org/samples/latest/utils.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

    <script>layout_change('light');</script>
    <script>layout_theme_contrast_change('false');</script>
    <script>change_box_container('false');</script>
    <script>layout_caption_change('true');</script>
    <script>layout_rtl_change('false');</script>
    <script>preset_change("preset-1");</script>

    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 300,
                    "density": {
                        "enable": true,
                        "value_area": 489.15
                    }
                },
                "color": {
                    "value": "#4A70A9"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#f80000ff"
                    },
                    "polygon": {
                        "nb_sides": 5
                    },
                    "image": {
                        "src": "img/github.svg",
                        "width": 100,
                        "height": 100
                    }
                },
                "opacity": {
                    "value": 0.49,
                    "random": false,
                    "anim": {
                        "enable": true,
                        "speed": 0.25,
                        "opacity_min": 0,
                        "sync": false
                    }
                },
                "size": {
                    "value": 2,
                    "random": true,
                    "anim": {
                        "enable": true,
                        "speed": 0.333,
                        "size_min": 0,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": false,
                    "distance": 150,
                    "color": "#0C2B4E",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 0.1,
                    "direction": "none",
                    "random": true,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "bubble"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 400,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 83.9,
                        "size": 1,
                        "duration": 3,
                        "opacity": 1,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true
        });
    </script>

    @stack('js')
</body>

</html>