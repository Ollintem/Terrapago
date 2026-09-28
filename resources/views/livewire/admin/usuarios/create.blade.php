 {{-- =========================================================
         MODAL CREAR / EDITAR
    ========================================================== --}}
    @if($isModalOpen)

        <div
            class="fixed inset-0
                   bg-slate-900/50
                   backdrop-blur-sm
                   flex items-center
                   justify-center
                   z-50
                   p-4"
        >

            <div
                class="bg-white
                       rounded-2xl
                       max-w-md
                       w-full
                       p-6
                       shadow-2xl"
            >


                {{-- ENCABEZADO --}}
                <div class="mb-5">

                    <div class="flex items-center gap-3">

                        <div
                            class="w-10 h-10
                                   rounded-xl
                                   bg-emerald-50
                                   text-emerald-600
                                   flex items-center
                                   justify-center"
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

                            <h3
                                class="text-lg
                                       font-bold
                                       tracking-tight
                                       text-slate-900"
                            >

                                {{ $user_id
                                    ? 'Editar Usuario'
                                    : 'Registrar Nuevo Usuario'
                                }}

                            </h3>

                            <p
                                class="text-xs
                                       text-slate-400
                                       font-medium"
                            >
                                Completa la información del usuario.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- FORMULARIO --}}
                <form
                    wire:submit.prevent="guardar"
                    novalidate
                    class="space-y-4"
                >


                    {{-- NOMBRE --}}
                    <div>

                        <label
                            class="block
                                   text-xs
                                   font-bold
                                   text-slate-600
                                   uppercase
                                   tracking-wide
                                   mb-1.5"
                        >
                            Nombre Completo
                        </label>


                        <input
                            wire:model="nombre"
                            type="text"

                            class="w-full
                                   px-3
                                   py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm
                                   font-medium
                                   focus:ring-2
                                   focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   focus:outline-none
                                   transition"
                        >


                        @error('nombre')

                            <span
                                class="block
                                       mt-1
                                       text-rose-500
                                       text-xs
                                       font-medium"
                            >
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- CORREO --}}
                    <div>

                        <label
                            class="block
                                   text-xs
                                   font-bold
                                   text-slate-600
                                   uppercase
                                   tracking-wide
                                   mb-1.5"
                        >
                            Correo Electrónico
                        </label>


                        <input
                            wire:model="email"
                            type="email"

                            class="w-full
                                   px-3
                                   py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm
                                   font-medium
                                   focus:ring-2
                                   focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   focus:outline-none
                                   transition"
                        >


                        @error('email')

                            <span
                                class="block
                                       mt-1
                                       text-rose-500
                                       text-xs
                                       font-medium"
                            >
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- CONTRASEÑA --}}
                    <div>

                        <label
                            class="block
                                   text-xs
                                   font-bold
                                   text-slate-600
                                   uppercase
                                   tracking-wide
                                   mb-1.5"
                        >
                            Contraseña
                        </label>


                        <input
                            wire:model="password"
                            type="password"
                            placeholder="{{ $user_id ? 'En blanco para no cambiar' : '' }}"

                            class="w-full
                                   px-3
                                   py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm
                                   font-medium
                                   focus:ring-2
                                   focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   focus:outline-none
                                   transition"
                        >


                        @error('password')

                            <span
                                class="block
                                       mt-1
                                       text-rose-500
                                       text-xs
                                       font-medium"
                            >
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- ROL --}}
                    <div>

                        <label
                            class="block
                                   text-xs
                                   font-bold
                                   text-slate-600
                                   uppercase
                                   tracking-wide
                                   mb-1.5"
                        >
                            Rol
                        </label>


                        <select
                            wire:model="rol_id"

                            class="w-full
                                   px-3
                                   py-2.5
                                   border border-slate-300
                                   rounded-lg
                                   text-sm
                                   font-medium
                                   bg-white
                                   focus:ring-2
                                   focus:ring-emerald-500/30
                                   focus:border-emerald-500
                                   focus:outline-none
                                   transition"
                        >

                            <option value="">
                                Selecciona rol...
                            </option>


                            @foreach($roles as $rol)

                                <option value="{{ $rol->id }}">
                                    {{ $rol->nombre }}
                                </option>

                            @endforeach

                        </select>


                        @error('rol_id')

                            <span
                                class="block
                                       mt-1
                                       text-rose-500
                                       text-xs
                                       font-medium"
                            >
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- BOTONES --}}
                    <div
                        class="mt-6
                               flex
                               justify-end
                               gap-3"
                    >

                        <button
                            type="button"
                            wire:click="cerrarModal"

                            class="px-4
                                   py-2.5
                                   border border-slate-300
                                   text-slate-600
                                   rounded-lg
                                   text-sm
                                   font-semibold
                                   hover:bg-slate-50
                                   transition
                                   cursor-pointer"
                        >
                            Cancelar
                        </button>


                        <button
                            type="submit"

                            class="px-4
                                   py-2.5
                                   bg-emerald-600
                                   text-white
                                   rounded-lg
                                   text-sm
                                   font-semibold
                                   hover:bg-emerald-700
                                   shadow-sm
                                   shadow-emerald-600/20
                                   transition
                                   cursor-pointer"
                        >
                            {{ $user_id ? 'Actualizar' : 'Guardar' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif