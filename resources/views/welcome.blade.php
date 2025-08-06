<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BEMA Project Management</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html.lenis {
            height: auto;
        }

        .lenis.lenis-smooth {
            scroll-behavior: auto !important;
        }

        .lenis.lenis-stopped {
            overflow: hidden;
        }

        .section {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .main-title {
            font-weight: 800;
            position: absolute;
            width: 80%;
        }

        .hover-text span {
            display: inline-block;
            transition: transform 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .side-nav {
            position: fixed;
            top: 50%;
            left: 2rem;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 1rem;
            z-index: 50;
        }

        .side-nav a {
            width: 12px;
            height: 12px;
            border-radius: 9999px;
            background-color: #333;
            transition: background-color 0.3s ease;
        }

        .dark .side-nav a {
            background-color: #ccc;
        }

        .side-nav a.active {
            background-color: #4f46e5;
        }

        #logo-container {
            width: 100%;
            height: 100vh;
            position: relative;
        }

        #final-destination {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        #main-logo {
            width: 600px;
            height: 600px;
            will-change: transform;
        }

        #get-started-btn {
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>

<body class="antialiased bg-gray-100 dark:bg-gray-900">
    <x-preloader />
    {{-- tombol login dan register --}}
    <div class="fixed top-0 right-0 p-6 z-50">
        @if (Route::has('login'))
            <nav class="flex flex-1 justify-end gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Log
                        in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Register</a>
                    @endif
                @endauth
            </nav>
        @endif
    </div>

    {{-- navigasi titik --}}
    <nav class="side-nav">
        <a href="#section1" class="dot active"></a>
        <a href="#section2" class="dot"></a>
        <a href="#section3" class="dot"></a>
        <a href="#section4" class="dot"></a>
    </nav>

    <div id="main-container">
        <section id="section1" class="section">
            <h1 class="main-title hover-text text-2xl md:text-5xl lg:text-7xl">Ready for awesomeness? 😎</h1>
        </section>
        <section id="section2" class="section">
            <h1 class="main-title hover-text text-2xl md:text-5xl lg:text-7xl">Ready for productivity? 💪</h1>
        </section>

        <section id="section3" class="section">
            <div id="main-logo">
                <img src="{{ asset('build/assets/logo/logo-full-light-mode.svg') }}" class="w-full h-full"
                    alt="Main Logo" class="w-10 sm:w-10 md:w-10 lg:w-10 xl:w-10">
            </div>
        </section>

        <section id="section4" class="section">
            <div id="final-destination">
                <a href="{{ route('login') }}" id="get-started-btn"
                    class="relative inline-block px-8 py-4 bg-black text-white font-bold text-xl rounded-lg transition-all duration-300 ease-out hover:bg-slate-800 hover:scale-105 active:scale-100 overflow-hidden group">

                    <span class="relative z-10">Get Started</span>

                    <div
                        class="absolute inset-0 z-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-out skew-x-[-20deg]">
                    </div>
                </a>
            </div>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.34/dist/lenis.min.js"></script>

    <script>
        const lenis = new Lenis();

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);
        lenis.on('scroll', ScrollTrigger.update);
        gsap.ticker.add((time) => {
            lenis.raf(time * 1000);
        });
        gsap.registerPlugin(ScrollTrigger);

        // fade in/out teks 
        gsap.utils.toArray('.main-title').forEach(title => {
            const section = title.closest('.section');

            gsap.to(title, {
                scrollTrigger: {
                    trigger: section,
                    start: 'top 130%',
                    end: 'bottom 10%',
                    scrub: 1,
                    // markers: true   
                },
                keyframes: [{
                        opacity: 1,
                        y: 0,
                        duration: 1
                    },
                    {
                        opacity: 0,
                        y: -100,
                        duration: 1
                    }
                ],
                ease: 'none',
                opacity: 0,
                y: 100
            });
        });




        // hover huruf
        document.querySelectorAll('.hover-text').forEach(textEl => {
            const text = textEl.textContent;
            textEl.innerHTML = '';
            for (let char of text) {
                const span = document.createElement('span');
                span.innerHTML = char === ' ' ? '&nbsp;' : char;
                textEl.appendChild(span);
            }

            textEl.querySelectorAll('span').forEach(span => {
                span.addEventListener('mouseover', () => {
                    gsap.to(span, {
                        y: -10,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
                span.addEventListener('mouseout', () => {
                    gsap.to(span, {
                        y: 0,
                        duration: 0.3,
                        ease: 'power2.in'
                    });
                });
            });
        });

        const sections = gsap.utils.toArray('.section');
        const dots = gsap.utils.toArray('.dot');
        sections.forEach((section, i) => {
            ScrollTrigger.create({
                trigger: section,
                start: "top center",
                end: "bottom center",
                onEnter: () => {
                    dots.forEach(dot => dot.classList.remove('active'));
                    dots[i].classList.add('active');
                },
                onEnterBack: () => {
                    dots.forEach(dot => dot.classList.remove('active'));
                    dots[i].classList.add('active');
                }
            });
        });

        // logo
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: '#section3',
                start: 'bottom bottom',
                endTrigger: '#section4',
                end: 'center center',
                scrub: 1,
                pin: '#main-logo',
            }
        });
        tl.to('#main-logo', {
                scale: 0.4,
                y: '-5rem'
            })
            .to('#get-started-btn', {
                opacity: 1,
                visibility: 'visible'
            }, '<');
    </script>
</body>

</html>
