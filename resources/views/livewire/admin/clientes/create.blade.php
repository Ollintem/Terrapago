{{-- =========================================================
     MODAL REGISTRO / EDICIÓN
     SE CONSERVA LA LÓGICA ACTUAL
========================================================== --}}
@if($modalAbierto)

    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm
                flex items-center justify-center
                z-50 p-4">

        <div class="bg-white rounded-2xl
                    shadow-2xl
                    w-full max-w-xl
                    p-6
                    max-h-[90vh]
                    overflow-y-auto">

            {{-- TÍTULO --}}
            <div class="mb-6">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl
                                bg-emerald-50
                                text-emerald-600
                                flex items-center justify-center">

                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4"/>

                        </svg>

                    </div>

                    <div>

                        <h3 class="text-lg font-bold tracking-tight text-slate-900">
                            {{ $clienteId ? 'Editar Cliente' : 'Registrar Nuevo Cliente' }}
                        </h3>

                        <p class="text-xs text-slate-400 font-medium">
                            Completa la información del cliente.
                        </p>

                    </div>

                </div>

            </div>


            <form wire:submit.prevent="guardar" class="space-y-4">

                {{-- NOMBRE --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                    <div>

                        <label class="block text-xs font-bold
                                      text-slate-600 uppercase
                                      tracking-wide mb-1.5">

                            Nombre *

                        </label>

                        <input
                            type="text"
                            wire:model="nombre"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium
                                   focus:ring-2 focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   outline-none">

                        @error('nombre')
                            <span class="text-xs text-rose-500">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <div>

                        <label class="block text-xs font-bold
                                      text-slate-600 uppercase
                                      tracking-wide mb-1.5">

                            Ap. Paterno *

                        </label>

                        <input
                            type="text"
                            wire:model="apellido_paterno"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium
                                   focus:ring-2 focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   outline-none">

                        @error('apellido_paterno')
                            <span class="text-xs text-rose-500">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <div>

                        <label class="block text-xs font-bold
                                      text-slate-600 uppercase
                                      tracking-wide mb-1.5">

                            Ap. Materno

                        </label>

                        <input
                            type="text"
                            wire:model="apellido_materno"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium
                                   focus:ring-2 focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   outline-none">

                        @error('apellido_materno')
                            <span class="text-xs text-rose-500">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>


                {{-- CONTACTO --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                    <div>

                        <label class="block text-xs font-bold
                                      text-slate-600 uppercase
                                      tracking-wide mb-1.5">

                            Teléfono *

                        </label>

                        <input
                            type="text"
                            wire:model="telefono"
                            placeholder="10 dígitos"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium
                                   focus:ring-2 focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   outline-none">

                        @error('telefono')
                            <span class="text-xs text-rose-500">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <div>

                        <label class="block text-xs font-bold
                                      text-slate-600 uppercase
                                      tracking-wide mb-1.5">

                            F. Nacimiento

                        </label>

                        <input
                            type="date"
                            wire:model="fecha_nacimiento"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium
                                   focus:ring-2 focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   outline-none">

                        @error('fecha_nacimiento')
                            <span class="text-xs text-rose-500">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <div>

                        <label class="block text-xs font-bold
                                      text-slate-600 uppercase
                                      tracking-wide mb-1.5">

                            Correo

                        </label>

                        <input
                            type="email"
                            wire:model="email"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium
                                   focus:ring-2 focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   outline-none">

                        @error('email')
                            <span class="text-xs text-rose-500">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>


                {{-- CURP / RFC --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    <div>

                        <label class="block text-xs font-bold
                                      text-slate-600 uppercase
                                      tracking-wide mb-1.5">

                            CURP

                        </label>

                        <input
                            type="text"
                            wire:model="curp"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium uppercase
                                   focus:ring-2 focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   outline-none">

                        @error('curp')
                            <span class="text-xs text-rose-500">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <div>

                        <label class="block text-xs font-bold
                                      text-slate-600 uppercase
                                      tracking-wide mb-1.5">

                            RFC

                        </label>

                        <input
                            type="text"
                            wire:model="rfc"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium uppercase
                                   focus:ring-2 focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   outline-none">

                        @error('rfc')
                            <span class="text-xs text-rose-500">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>


                {{-- DIRECCIÓN --}}
                <div>

                    <label class="block text-xs font-bold
                                  text-slate-600 uppercase
                                  tracking-wide mb-1.5">

                        Dirección Completa

                    </label>

                    <textarea
                        wire:model="direccion"
                        rows="2"
                        placeholder="Calle, número, colonia, municipio"

                        class="w-full px-3 py-2.5
                               border border-slate-300
                               rounded-lg
                               text-sm font-medium
                               focus:ring-2 focus:ring-emerald-500/30
                               focus:border-emerald-500
                               outline-none"></textarea>

                    @error('direccion')
                        <span class="text-xs text-rose-500">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                {{-- BOTONES --}}
                <div class="flex justify-end gap-3 pt-4">

                    <button
                        type="button"
                        wire:click="cerrarModal"

                        class="px-4 py-2.5
                               border border-slate-300
                               text-slate-600
                               rounded-lg
                               text-sm font-semibold
                               hover:bg-slate-50
                               transition
                               cursor-pointer">

                        Cancelar

                    </button>


                    <button
                        type="submit"

                        class="px-4 py-2.5
                               bg-emerald-600
                               text-white
                               rounded-lg
                               text-sm font-semibold
                               hover:bg-emerald-700
                               shadow-sm
                               shadow-emerald-600/20
                               transition
                               cursor-pointer">

                        Guardar Cliente

                    </button>

                </div>

            </form>

        </div>

    </div>

@endif