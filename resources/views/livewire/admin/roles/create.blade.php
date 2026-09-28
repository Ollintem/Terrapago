 {{-- =========================================================
         MODAL CREAR / EDITAR
    ========================================================== --}}
    @if($modalAbierto)

        <div
            class="fixed inset-0
                   bg-slate-900/50
                   backdrop-blur-sm
                   flex items-center justify-center
                   p-4
                   z-50"
        >

            <div
                class="bg-white rounded-2xl
                       shadow-2xl
                       w-full max-w-md
                       p-6"
            >


                {{-- =================================================
                     ENCABEZADO DEL MODAL
                ================================================== --}}
                <div class="flex items-center gap-3 mb-5">

                    <div
                        class="w-10 h-10 rounded-xl
                               bg-emerald-50
                               text-emerald-600
                               flex items-center justify-center"
                    >

                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4"
                            />

                        </svg>

                    </div>


                    <div>

                        <h3 class="text-lg font-bold tracking-tight text-slate-900">

                            {{ $modoEdicion
                                ? 'Editar Puesto / Rol'
                                : 'Registrar Nuevo Puesto'
                            }}

                        </h3>

                        <p class="text-xs text-slate-400 font-medium">

                            {{ $modoEdicion
                                ? 'Actualiza la información del puesto.'
                                : 'Ingresa el nombre del nuevo puesto.'
                            }}

                        </p>

                    </div>

                </div>


                {{-- =================================================
                     FORMULARIO
                ================================================== --}}
                <form
                    wire:submit.prevent="guardar"
                    novalidate
                    class="space-y-4"
                >


                    {{-- NOMBRE DEL PUESTO --}}
                    <div>

                        <label
                            class="block text-xs font-bold
                                   text-slate-600
                                   uppercase tracking-wide
                                   mb-1.5"
                        >

                            Nombre del Puesto

                        </label>


                        <input
                            type="text"
                            wire:model="nombre"

                            class="w-full px-3 py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm font-medium
                                   placeholder:text-slate-400
                                   focus:ring-2
                                   focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   focus:outline-none
                                   transition"

                            placeholder="Ej. Cobrador en Campo"
                        >


                        @error('nombre')

                            <span class="block mt-1.5 text-xs text-rose-500 font-medium">

                                {{ $message }}

                            </span>

                        @enderror

                    </div>


                    {{-- BOTONES --}}
                    <div class="mt-6 flex justify-end gap-3">

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
                                   cursor-pointer"
                        >

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
                                   cursor-pointer"
                        >

                            {{ $modoEdicion ? 'Actualizar' : 'Guardar' }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif