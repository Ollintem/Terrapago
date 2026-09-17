@extends('layouts.app')

@section('content')

<style>
    @keyframes floatWave {
        0%, 100% {
            transform: translateX(0) translateY(0);
        }

        50% {
            transform: translateX(-18px) translateY(-5px);
        }
    }

    @keyframes floatWaveReverse {
        0%, 100% {
            transform: translateX(0) translateY(0);
        }

        50% {
            transform: translateX(20px) translateY(5px);
        }
    }

    @keyframes floatShape {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-15px) rotate(2deg);
        }
    }

    @keyframes fadeLogin {
        from {
            opacity: 0;
            transform: translateY(20px) scale(.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes logoGlow {
        0%, 100% {
            box-shadow: 0 0 20px rgba(16, 185, 129, .15);
        }

        50% {
            box-shadow: 0 0 35px rgba(16, 185, 129, .35);
        }
    }

    .login-card {
        animation: fadeLogin .7s ease-out forwards;
    }

    .floating-shape {
        animation: floatShape 7s ease-in-out infinite;
    }

    .wave-one {
        animation: floatWave 7s ease-in-out infinite;
    }

    .wave-two {
        animation: floatWaveReverse 9s ease-in-out infinite;
    }

    .wave-three {
        animation: floatWave 11s ease-in-out infinite;
    }

    .logo-glow {
        animation: logoGlow 3s ease-in-out infinite;
    }
</style>


<div class="relative min-h-screen w-full overflow-hidden bg-emerald-500">


    <!-- ===================================================== -->
    <!-- FONDO GEOMÉTRICO -->
    <!-- ===================================================== -->

    <!-- Azul principal -->
    <div class="absolute inset-0 bg-[#0F172A]"
         style="clip-path: polygon(0 0, 0 100%, 100% 100%, 68% 67%, 68% 38%, 0 0);">
    </div>


    <!-- Verde esmeralda -->
    <div class="absolute inset-0 bg-emerald-500"
         style="clip-path: polygon(0 0, 100% 0, 100% 67%, 68% 67%, 0 0);">
    </div>


    <!-- Azul inferior -->
    <div class="absolute inset-0 bg-[#0F172A]"
         style="clip-path: polygon(0 100%, 100% 100%, 100% 67%, 68% 67%);">
    </div>


    <!-- ===================================================== -->
    <!-- ELEMENTOS FLOTANTES -->
    <!-- ===================================================== -->

    <div class="absolute top-16 left-[8%]
                w-5 h-5 rounded-full
                bg-white/20
                floating-shape">
    </div>


    <div class="absolute top-[25%] right-[8%]
                w-3 h-3 rounded-full
                bg-white/30
                floating-shape"
         style="animation-delay: 1s;">
    </div>


    <div class="absolute bottom-[18%] left-[10%]
                w-3 h-3 rounded-full
                bg-emerald-300/30
                floating-shape"
         style="animation-delay: 2s;">
    </div>


    <div class="absolute top-[15%] right-[25%]
                w-2 h-2 rounded-full
                bg-emerald-300/40
                animate-ping">
    </div>


    <!-- ===================================================== -->
    <!-- LOGIN -->
    <!-- ===================================================== -->

    <div class="relative z-10 min-h-screen
                flex items-center justify-center
                px-5 py-10">


        <!-- ================================================= -->
        <!-- TARJETA -->
        <!-- ================================================= -->

        <div class="login-card relative
                    w-full max-w-md
                    overflow-hidden

                    bg-[#0F172A]

                    rounded-none

                    shadow-[0_25px_60px_rgba(15,23,42,0.45)]

                    border border-white/10">


            <!-- ================================================= -->
            <!-- CONTENIDO -->
            <!-- ================================================= -->

            <div class="relative z-20
                        px-8 sm:px-10
                        pt-10
                        pb-36">


                <!-- ================================================= -->
                <!-- LOGO + TERRAPAGO -->
                <!-- ================================================= -->

                <div class="text-center mb-9">


                    <!-- LOGO TP -->
                    <div class="relative inline-flex">

                        <!-- Brillo -->
                        <div class="absolute inset-0
                                    bg-emerald-400/30
                                    rounded-2xl
                                    blur-xl
                                    animate-pulse">
                        </div>


                        <!-- Logo -->
                        <div class="logo-glow relative
                                    w-16 h-16
                                    rounded-2xl

                                    bg-gradient-to-br
                                    from-emerald-400
                                    to-emerald-600

                                    flex items-center justify-center

                                    text-white
                                    text-2xl
                                    font-black

                                    shadow-lg
                                    shadow-emerald-500/20

                                    transition-all
                                    duration-300

                                    hover:scale-110
                                    hover:rotate-3">

                            TP

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- TERRAPAGO DESTACADO -->
                    <!-- ================================================= -->

                    <h1 class="mt-5
                               text-4xl
                               sm:text-[2.7rem]
                               font-black
                               tracking-[-0.04em]
                               leading-none

                               bg-gradient-to-r
                               from-white
                               via-white
                               to-emerald-400

                               bg-clip-text
                               text-transparent">

                        TerraPago

                    </h1>


                    <!-- Línea decorativa -->
                    <div class="flex items-center justify-center gap-2 mt-3">

                        <span class="h-px w-8 bg-white/10"></span>

                        <span class="w-1.5 h-1.5
                                     rounded-full
                                     bg-emerald-400
                                     animate-pulse">
                        </span>

                        <span class="h-px w-8 bg-white/10"></span>

                    </div>


                    <p class="mt-2
                              text-[11px]
                              uppercase
                              tracking-[0.25em]
                              text-white/50
                              font-bold">

                        Sistema de Cobro

                    </p>

                </div>


                <!-- ================================================= -->
                <!-- FORMULARIO -->
                <!-- ================================================= -->

                <form method="POST"
                      action="{{ route('login') }}"
                      class="space-y-5">

                    @csrf


                    <!-- ================================================= -->
                    <!-- CORREO -->
                    <!-- ================================================= -->

                    <div>

                        <label for="email"
                               class="block
                                      text-[11px]
                                      font-bold
                                      uppercase
                                      tracking-[0.15em]
                                      text-white/60
                                      mb-2">

                            Correo Electrónico

                        </label>


                        <div class="relative">

                            <!-- Icono -->
                            <div class="absolute inset-y-0 left-0
                                        pl-4
                                        flex items-center
                                        pointer-events-none">

                                <svg class="w-5 h-5 text-white/35"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="1.8"
                                          d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>

                                </svg>

                            </div>


                            <input id="email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="email"
                                   autofocus
                                   placeholder="correo@ejemplo.com"

                                   class="w-full
                                          pl-12
                                          pr-4
                                          py-3

                                          bg-transparent

                                          border-0
                                          border-b
                                          border-white/20

                                          text-sm
                                          text-white

                                          placeholder-white/30

                                          focus:outline-none
                                          focus:border-emerald-400
                                          focus:ring-0

                                          transition-all
                                          duration-300

                                          @error('email')
                                              border-rose-400
                                          @enderror">

                        </div>


                        @error('email')

                            <p class="text-rose-300
                                      text-xs
                                      mt-2
                                      font-medium">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>


                    <!-- ================================================= -->
                    <!-- CONTRASEÑA -->
                    <!-- ================================================= -->

                    <div>

                        <label for="password"
                               class="block
                                      text-[11px]
                                      font-bold
                                      uppercase
                                      tracking-[0.15em]
                                      text-white/60
                                      mb-2">

                            Contraseña

                        </label>


                        <div class="relative">

                            <!-- Icono -->
                            <div class="absolute inset-y-0 left-0
                                        pl-4
                                        flex items-center
                                        pointer-events-none">

                                <svg class="w-5 h-5 text-white/35"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="1.8"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zm10-9V7a4 4 0 00-8 0v3h8z"/>

                                </svg>

                            </div>


                            <input id="password"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="••••••••"

                                   class="w-full
                                          pl-12
                                          pr-4
                                          py-3

                                          bg-transparent

                                          border-0
                                          border-b
                                          border-white/20

                                          text-sm
                                          text-white

                                          placeholder-white/30

                                          focus:outline-none
                                          focus:border-emerald-400
                                          focus:ring-0

                                          transition-all
                                          duration-300

                                          @error('password')
                                              border-rose-400
                                          @enderror">

                        </div>


                        @error('password')

                            <p class="text-rose-300
                                      text-xs
                                      mt-2
                                      font-medium">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>


                    <!-- ================================================= -->
                    <!-- BOTÓN -->
                    <!-- ================================================= -->

                    <button type="submit"

                            class="group relative
                                   w-full

                                   py-3

                                   bg-gradient-to-r
                                   from-emerald-500
                                   to-emerald-600

                                   hover:from-emerald-400
                                   hover:to-emerald-500

                                   text-white
                                   text-sm
                                   font-bold

                                   rounded-none

                                   shadow-lg
                                   shadow-emerald-500/20

                                   transition-all
                                   duration-300

                                   hover:-translate-y-0.5

                                   active:translate-y-0

                                   cursor-pointer
                                   overflow-hidden">


                        <span class="relative z-10
                                     flex
                                     items-center
                                     justify-center
                                     gap-2">

                            Acceder al Sistema


                            <svg class="w-4 h-4
                                        transition-transform
                                        duration-300
                                        group-hover:translate-x-1"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M13 7l5 5m0 0l-5 5m5-5H6"/>

                            </svg>

                        </span>


                        <!-- Brillo -->
                        <div class="absolute inset-0
                                    bg-white/10
                                    -translate-x-full
                                    group-hover:translate-x-full
                                    transition-transform
                                    duration-700">
                        </div>

                    </button>

                </form>


                <!-- ================================================= -->
                <!-- PIE -->
                <!-- ================================================= -->

                <div class="mt-7 text-center">

                    <div class="flex items-center
                                justify-center gap-2">

                        <span class="w-1.5 h-1.5
                                     rounded-full
                                     bg-emerald-400
                                     animate-pulse">
                        </span>


                        <p class="text-[10px]
                                  text-white/40
                                  uppercase
                                  tracking-wider">

                            Acceso restringido a personal autorizado

                        </p>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- ONDAS -->
            <!-- ================================================= -->

            <div class="absolute
                        bottom-0
                        left-0
                        right-0
                        h-32
                        overflow-hidden">


                <!-- Onda trasera -->
                <svg class="wave-one
                            absolute
                            bottom-[-12px]
                            left-[-5%]
                            w-[110%]
                            h-32"

                     viewBox="0 0 900 180"
                     preserveAspectRatio="none">

                    <path d="M0,110
                             C100,40 150,160 250,95
                             C350,30 400,145 500,85
                             C600,25 650,150 750,80
                             C820,35 860,80 900,60
                             L900,180
                             L0,180 Z"

                          fill="#064E3B"
                          opacity="0.9">
                    </path>

                </svg>


                <!-- Onda media -->
                <svg class="wave-two
                            absolute
                            bottom-[-18px]
                            left-[-5%]
                            w-[110%]
                            h-28"

                     viewBox="0 0 900 180"
                     preserveAspectRatio="none">

                    <path d="M0,125
                             C80,65 140,150 230,105
                             C320,60 380,150 470,100
                             C560,50 630,145 720,95
                             C800,50 850,105 900,75
                             L900,180
                             L0,180 Z"

                          fill="#059669"
                          opacity="0.9">
                    </path>

                </svg>


                <!-- Onda frontal -->
                <svg class="wave-three
                            absolute
                            bottom-[-25px]
                            left-[-5%]
                            w-[110%]
                            h-24"

                     viewBox="0 0 900 180"
                     preserveAspectRatio="none">

                    <path d="M0,130
                             C90,80 150,150 240,115
                             C330,80 390,150 480,110
                             C570,70 640,145 730,105
                             C810,70 860,115 900,90
                             L900,180
                             L0,180 Z"

                          fill="#10B981">
                    </path>

                </svg>

            </div>

        </div>

    </div>

</div>

@endsection