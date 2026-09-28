{{-- =========================================================
        MODAL CREAR / EDITAR
    ========================================================== --}}
    @if($modalAbierto)

        <div
            class="fixed inset-0
                   bg-slate-900/60
                   backdrop-blur-sm
                   flex items-center justify-center
                   p-4
                   z-50">

            <div
                class="bg-white
                       rounded-2xl
                       shadow-2xl
                       w-full
                       max-w-lg
                       max-h-[90vh]
                       overflow-y-auto">

                {{-- Encabezado modal --}}
                <div
                    class="flex items-center justify-between
                           px-6 py-5
                           border-b border-slate-100">

                    <div>

                        <p
                            class="text-xs
                                   font-bold
                                   uppercase
                                   tracking-widest
                                   text-emerald-600">

                            Inventario

                        </p>

                        <h3
                            class="text-xl
                                   font-extrabold
                                   text-slate-900
                                   mt-1">

                            {{ $terrenoId
                                ? 'Editar Lote / Terreno'
                                : 'Registrar Nuevo Lote' }}

                        </h3>

                    </div>


                    <button
                        type="button"
                        wire:click="cerrarModal"
                        class="w-9 h-9
                               rounded-lg
                               bg-slate-100
                               hover:bg-slate-200
                               text-slate-500
                               flex items-center justify-center
                               transition
                               cursor-pointer">

                        <span class="text-xl leading-none">
                            ×
                        </span>

                    </button>

                </div>


                {{-- Formulario --}}
                <form
                    wire:submit.prevent="guardar"
                    novalidate
                    class="p-6 space-y-5">

                    {{-- Manzana / Lote --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Manzana --}}
                        <div>

                            <label
                                class="block
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-600
                                       mb-1.5">

                                Manzana *

                            </label>

                            <input
                                type="text"
                                wire:model="manzana"
                                placeholder="Ej. 04"
                                class="w-full
                                       px-4 py-2.5
                                       border border-slate-200
                                       rounded-xl
                                       bg-slate-50
                                       text-sm
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-emerald-500/30
                                       focus:border-emerald-500">

                            @error('manzana')

                                <span
                                    class="block
                                           text-xs
                                           text-rose-500
                                           mt-1">

                                    {{ $message }}

                                </span>

                            @enderror

                        </div>


                        {{-- Lote --}}
                        <div>

                            <label
                                class="block
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-600
                                       mb-1.5">

                                Lote *

                            </label>

                            <input
                                type="text"
                                wire:model="lote"
                                placeholder="Ej. 01"
                                class="w-full
                                       px-4 py-2.5
                                       border border-slate-200
                                       rounded-xl
                                       bg-slate-50
                                       text-sm
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-emerald-500/30
                                       focus:border-emerald-500">

                            @error('lote')

                                <span
                                    class="block
                                           text-xs
                                           text-rose-500
                                           mt-1">

                                    {{ $message }}

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Medidas / Superficie --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Medidas --}}
                        <div>

                            <label
                                class="block
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-600
                                       mb-1.5">

                                Medidas *

                            </label>

                            <input
                                type="text"
                                wire:model="medidas"
                                placeholder="Ej. 10x20 m"
                                class="w-full
                                       px-4 py-2.5
                                       border border-slate-200
                                       rounded-xl
                                       bg-slate-50
                                       text-sm
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-emerald-500/30
                                       focus:border-emerald-500">

                            @error('medidas')

                                <span
                                    class="block
                                           text-xs
                                           text-rose-500
                                           mt-1">

                                    {{ $message }}

                                </span>

                            @enderror

                        </div>


                        {{-- Superficie --}}
                        <div>

                            <label
                                class="block
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-600
                                       mb-1.5">

                                Superficie (m²) *

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                wire:model="superficie"
                                placeholder="200.00"
                                class="w-full
                                       px-4 py-2.5
                                       border border-slate-200
                                       rounded-xl
                                       bg-slate-50
                                       text-sm
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-emerald-500/30
                                       focus:border-emerald-500">

                            @error('superficie')

                                <span
                                    class="block
                                           text-xs
                                           text-rose-500
                                           mt-1">

                                    {{ $message }}

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Precio / Estado --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Precio --}}
                        <div>

                            <label
                                class="block
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-600
                                       mb-1.5">

                                Precio ($) *

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                wire:model="precio"
                                placeholder="400000.00"
                                class="w-full
                                       px-4 py-2.5
                                       border border-slate-200
                                       rounded-xl
                                       bg-slate-50
                                       text-sm
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-emerald-500/30
                                       focus:border-emerald-500">

                            @error('precio')

                                <span
                                    class="block
                                           text-xs
                                           text-rose-500
                                           mt-1">

                                    {{ $message }}

                                </span>

                            @enderror

                        </div>


                        {{-- Estado --}}
                        <div>

                            <label
                                class="block
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-600
                                       mb-1.5">

                                Estado *

                            </label>

                            <select
                                wire:model="estado"
                                class="w-full
                                       px-4 py-2.5
                                       border border-slate-200
                                       rounded-xl
                                       bg-slate-50
                                       text-sm
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-emerald-500/30
                                       focus:border-emerald-500">

                                <option value="DISPONIBLE">
                                    DISPONIBLE
                                </option>

                                <option value="APARTADO">
                                    APARTADO
                                </option>

                                <option value="VENDIDO">
                                    VENDIDO
                                </option>

                            </select>

                            @error('estado')

                                <span
                                    class="block
                                           text-xs
                                           text-rose-500
                                           mt-1">

                                    {{ $message }}

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Ubicación --}}
                    <div>

                        <label
                            class="block
                                   text-xs
                                   font-bold
                                   uppercase
                                   tracking-wider
                                   text-slate-600
                                   mb-1.5">

                            Ubicación / Referencia

                        </label>

                        <input
                            type="text"
                            wire:model="ubicacion"
                            placeholder="Ej. Esquina norte, frente al parque"
                            class="w-full
                                   px-4 py-2.5
                                   border border-slate-200
                                   rounded-xl
                                   bg-slate-50
                                   text-sm
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-emerald-500/30
                                   focus:border-emerald-500">

                        @error('ubicacion')

                            <span
                                class="block
                                       text-xs
                                       text-rose-500
                                       mt-1">

                                {{ $message }}

                            </span>

                        @enderror

                    </div>


                    {{-- Botones --}}
                    <div
                        class="flex justify-end
                               gap-3
                               pt-4
                               border-t border-slate-100">

                        <button
                            type="button"
                            wire:click="cerrarModal"
                            class="px-5 py-2.5
                                   border border-slate-200
                                   text-slate-600
                                   hover:bg-slate-50
                                   rounded-xl
                                   text-sm font-bold
                                   transition
                                   cursor-pointer">

                            Cancelar

                        </button>


                        <button
                            type="submit"
                            class="px-5 py-2.5
                                   bg-emerald-600
                                   hover:bg-emerald-700
                                   text-white
                                   rounded-xl
                                   text-sm font-bold
                                   shadow-lg
                                   shadow-emerald-600/20
                                   transition
                                   cursor-pointer">

                            {{ $terrenoId
                                ? 'Actualizar Lote'
                                : 'Guardar Lote' }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif

