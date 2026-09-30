{{-- =========================================================
     ESTADÍSTICAS
========================================================== --}}

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    {{-- =====================================================
         AL CORRIENTE
    ====================================================== --}}

    <div
        class="bg-white
               border border-slate-200
               rounded-2xl
               shadow-sm
               p-5
               hover:shadow-md
               transition"
    >

        <div class="flex items-center justify-between">

            <div>

                <p
                    class="text-xs
                           font-semibold
                           text-slate-500
                           uppercase
                           tracking-wide"
                >
                    Al corriente
                </p>

                <p
                    class="mt-1
                           text-3xl
                           font-extrabold
                           text-slate-900"
                >
                    {{ $clientesAlCorriente }}
                </p>

            </div>


            <div
                class="w-11 h-11
                       rounded-xl
                       bg-emerald-50
                       flex items-center
                       justify-center"
            >

                <span
                    class="w-3 h-3
                           rounded-full
                           bg-emerald-500"
                ></span>

            </div>

        </div>


        <p class="mt-3 text-xs text-slate-400">
            Clientes sin cuotas vencidas
        </p>

    </div>


    {{-- =====================================================
         PRÓXIMOS A VENCER
    ====================================================== --}}

    <div
        class="bg-white
               border border-slate-200
               rounded-2xl
               shadow-sm
               p-5
               hover:shadow-md
               transition"
    >

        <div class="flex items-center justify-between">

            <div>

                <p
                    class="text-xs
                           font-semibold
                           text-slate-500
                           uppercase
                           tracking-wide"
                >
                    Próximos a vencer
                </p>


                <p
                    class="mt-1
                           text-3xl
                           font-extrabold
                           text-slate-900"
                >
                    {{ $clientesProximos }}
                </p>

            </div>


            <div
                class="w-11 h-11
                       rounded-xl
                       bg-amber-50
                       flex items-center
                       justify-center"
            >

                <span
                    class="w-3 h-3
                           rounded-full
                           bg-amber-400"
                ></span>

            </div>

        </div>


        <p class="mt-3 text-xs text-slate-400">
            Vencimiento dentro de 15 días
        </p>

    </div>


    {{-- =====================================================
         MOROSOS
    ====================================================== --}}

    <div
        class="bg-white
               border border-slate-200
               rounded-2xl
               shadow-sm
               p-5
               hover:shadow-md
               transition"
    >

        <div class="flex items-center justify-between">

            <div>

                <p
                    class="text-xs
                           font-semibold
                           text-slate-500
                           uppercase
                           tracking-wide"
                >
                    Morosos
                </p>


                <p
                    class="mt-1
                           text-3xl
                           font-extrabold
                           text-slate-900"
                >
                    {{ $clientesMorosos }}
                </p>

            </div>


            <div
                class="w-11 h-11
                       rounded-xl
                       bg-rose-50
                       flex items-center
                       justify-center"
            >

                <span
                    class="w-3 h-3
                           rounded-full
                           bg-rose-500"
                ></span>

            </div>

        </div>


        <p class="mt-3 text-xs text-slate-400">
            Clientes con cuotas vencidas
        </p>

    </div>

</div>