<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Cliente;

class ClientManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $modalAbierto = false;
    public $clienteId;

    // =========================================================
    // CAMPOS DEL CLIENTE
    // =========================================================

    public $nombre;
    public $apellido_paterno;
    public $apellido_materno;
    public $fecha_nacimiento;
    public $telefono;
    public $email;
    public $direccion;
    public $curp;
    public $rfc;


    // =========================================================
    // REGLAS DE VALIDACIÓN
    // =========================================================

    protected function rules()
    {
        return [
            'nombre' =>
                'required|string|max:100',

            'apellido_paterno' =>
                'required|string|max:100',

            'apellido_materno' =>
                'nullable|string|max:100',

            'fecha_nacimiento' =>
                'nullable|date',

            'telefono' =>
                'required|string|max:20',

            'email' =>
                'nullable|email|max:150|unique:clientes,email,' . $this->clienteId,

            'direccion' =>
                'nullable|string',

            'curp' =>
                'nullable|string|max:20',

            'rfc' =>
                'nullable|string|max:20',
        ];
    }


    // =========================================================
    // MENSAJES DE VALIDACIÓN
    // =========================================================

    protected function messages()
    {
        return [
            'nombre.required' =>
                'El nombre es obligatorio.',

            'nombre.string' =>
                'El nombre debe ser texto.',

            'nombre.max' =>
                'El nombre no puede tener más de 100 caracteres.',

            'apellido_paterno.required' =>
                'El apellido paterno es obligatorio.',

            'apellido_paterno.string' =>
                'El apellido paterno debe ser texto.',

            'apellido_paterno.max' =>
                'El apellido paterno no puede tener más de 100 caracteres.',

            'apellido_materno.string' =>
                'El apellido materno debe ser texto.',

            'apellido_materno.max' =>
                'El apellido materno no puede tener más de 100 caracteres.',

            'fecha_nacimiento.date' =>
                'La fecha de nacimiento no es válida.',

            'telefono.required' =>
                'El teléfono es obligatorio.',

            'telefono.string' =>
                'El teléfono debe ser texto.',

            'telefono.max' =>
                'El teléfono no puede tener más de 20 caracteres.',

            'email.email' =>
                'El correo electrónico no tiene un formato válido.',

            'email.max' =>
                'El correo electrónico no puede tener más de 150 caracteres.',

            'email.unique' =>
                'Este correo electrónico ya está registrado.',

            'direccion.string' =>
                'La dirección debe ser texto.',

            'curp.string' =>
                'La CURP debe ser texto.',

            'curp.max' =>
                'La CURP no puede tener más de 20 caracteres.',

            'rfc.string' =>
                'El RFC debe ser texto.',

            'rfc.max' =>
                'El RFC no puede tener más de 20 caracteres.',
        ];
    }


    // =========================================================
    // BÚSQUEDA
    // =========================================================

    public function updatingSearch()
    {
        $this->resetPage();
    }


    // =========================================================
    // ACTUALIZACIÓN DESPUÉS DE UN PAGO
    // =========================================================

    #[On('pago-registrado')]
    public function actualizarDespuesDePago()
    {
        /*
         * No necesitamos modificar manualmente los datos.
         *
         * Livewire volverá a ejecutar render(), por lo que:
         *
         * - se vuelven a consultar los clientes
         * - se vuelven a consultar sus contratos
         * - se vuelven a consultar sus cuotas
         * - se recalculan las estadísticas
         * - se muestra el nuevo saldo_actual
         * - se actualiza el estado de la cuenta
         */
    }


    // =========================================================
    // RENDER
    // =========================================================

    public function render()
    {
        $user = auth()->user();


        // =====================================================
        // DETERMINAR SI EL USUARIO ES ADMINISTRADOR
        // =====================================================

        $esAdmin = $user->rol &&
            in_array(
                strtolower($user->rol->nombre),
                [
                    'administrador',
                    'super admin',
                    'superadministrador'
                ]
            );


        // =====================================================
        // CONSULTA BASE DE CLIENTES
        // =====================================================

        $consultaClientes = Cliente::with([
            'asesor',
            'contratos.terreno',
            'contratos.cuotas',
        ]);


        // =====================================================
        // RESTRICCIÓN PARA USUARIOS NO ADMINISTRADORES
        // =====================================================

        if (!$esAdmin) {

            $consultaClientes->where(
                'user_id',
                $user->id
            );
        }


        // =====================================================
        // BÚSQUEDA
        // =====================================================

        if ($this->search) {

            $consultaClientes->where(function ($q) {

                $q->where(
                    'nombre',
                    'like',
                    '%' . $this->search . '%'
                )

                ->orWhere(
                    'apellido_paterno',
                    'like',
                    '%' . $this->search . '%'
                )

                ->orWhere(
                    'apellido_materno',
                    'like',
                    '%' . $this->search . '%'
                )

                ->orWhere(
                    'telefono',
                    'like',
                    '%' . $this->search . '%'
                )

                ->orWhere(
                    'rfc',
                    'like',
                    '%' . $this->search . '%'
                )

                ->orWhere(
                    'curp',
                    'like',
                    '%' . $this->search . '%'
                )

                ->orWhere(
                    'email',
                    'like',
                    '%' . $this->search . '%'
                );
            });
        }


        // =====================================================
        // ESTADÍSTICAS
        //
        // Se calculan ANTES de paginar para que no se limiten
        // únicamente a los 10 clientes mostrados en pantalla.
        //
        // Respetan:
        // - usuario administrador
        // - usuario normal
        // - búsqueda actual
        // =====================================================

        $clientesParaEstadisticas =
            (clone $consultaClientes)->get();


        $clientesAlCorriente = 0;
        $clientesProximos = 0;
        $clientesMorosos = 0;


        foreach (
            $clientesParaEstadisticas
            as $clienteEstadistica
        ) {

            // -------------------------------------------------
            // CONTRATO ACTIVO MÁS RECIENTE
            // -------------------------------------------------

            $contratoEstadistica =
                $clienteEstadistica->contratos
                    ->where('estado', 'ACTIVO')
                    ->sortByDesc('id')
                    ->first();


            if (!$contratoEstadistica) {
                continue;
            }


            // -------------------------------------------------
            // CUOTAS DEL CONTRATO
            // -------------------------------------------------

            $cuotasEstadistica =
                $contratoEstadistica->cuotas;


            // -------------------------------------------------
            // CUOTA VENCIDA
            // -------------------------------------------------

            $cuotaVencida =
                $cuotasEstadistica
                    ->where('estado', 'VENCIDA')
                    ->first();


            // -------------------------------------------------
            // PRÓXIMA CUOTA PENDIENTE
            // -------------------------------------------------

            $cuotaProxima =
                $cuotasEstadistica
                    ->where('estado', 'PENDIENTE')
                    ->sortBy('fecha_vencimiento')
                    ->first();


            // -------------------------------------------------
            // DETERMINAR ESTADO DE LA CUENTA
            // -------------------------------------------------

            if ($cuotaVencida) {

                $clientesMorosos++;

            } elseif (
                $cuotaProxima &&
                $cuotaProxima->fecha_vencimiento &&
                now()->diffInDays(
                    $cuotaProxima->fecha_vencimiento,
                    false
                ) >= 0 &&
                now()->diffInDays(
                    $cuotaProxima->fecha_vencimiento,
                    false
                ) <= 15
            ) {

                $clientesProximos++;

            } else {

                $clientesAlCorriente++;
            }
        }


        // =====================================================
        // PAGINACIÓN
        //
        // La tabla sigue mostrando solamente 10 clientes.
        // =====================================================

        $clientes =
            $consultaClientes
                ->latest()
                ->paginate(10);


        // =====================================================
        // ENVIAR DATOS A LA VISTA
        // =====================================================

        return view(
            'livewire.admin.clientes.index',
            [
                'clientes' =>
                    $clientes,

                'esAdmin' =>
                    $esAdmin,

                'clientesAlCorriente' =>
                    $clientesAlCorriente,

                'clientesProximos' =>
                    $clientesProximos,

                'clientesMorosos' =>
                    $clientesMorosos,
            ]
        )->layout('layouts.app');
    }


    // =========================================================
    // ABRIR MODAL CREAR
    // =========================================================

    public function abrirModalCrear()
    {
        $this->reset([
            'clienteId',
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'fecha_nacimiento',
            'telefono',
            'email',
            'direccion',
            'curp',
            'rfc'
        ]);

        $this->resetValidation();

        $this->modalAbierto = true;
    }


    // =========================================================
    // ABRIR MODAL EDITAR
    // =========================================================

    public function abrirModalEditar($id)
    {
        $this->resetValidation();

        $cliente =
            Cliente::findOrFail($id);


        $this->clienteId =
            $cliente->id;

        $this->nombre =
            $cliente->nombre;

        $this->apellido_paterno =
            $cliente->apellido_paterno;

        $this->apellido_materno =
            $cliente->apellido_materno;

        $this->fecha_nacimiento =
            $cliente->fecha_nacimiento
                ? $cliente->fecha_nacimiento->format('Y-m-d')
                : null;

        $this->telefono =
            $cliente->telefono;

        $this->email =
            $cliente->email;

        $this->direccion =
            $cliente->direccion;

        $this->curp =
            $cliente->curp;

        $this->rfc =
            $cliente->rfc;

        $this->modalAbierto = true;
    }


    // =========================================================
    // CERRAR MODAL
    // =========================================================

    public function cerrarModal()
    {
        $this->resetValidation();

        $this->modalAbierto = false;
    }


    // =========================================================
    // GUARDAR CLIENTE
    // =========================================================

    public function guardar()
    {
        $this->validate();


        $datos = [

            'nombre' =>
                trim($this->nombre),

            'apellido_paterno' =>
                trim($this->apellido_paterno),

            'apellido_materno' =>
                $this->apellido_materno
                    ? trim($this->apellido_materno)
                    : null,

            'fecha_nacimiento' =>
                $this->fecha_nacimiento ?: null,

            'telefono' =>
                trim($this->telefono),

            'email' =>
                $this->email
                    ? trim($this->email)
                    : null,

            'direccion' =>
                $this->direccion
                    ? trim($this->direccion)
                    : null,

            'curp' =>
                $this->curp
                    ? strtoupper(
                        trim($this->curp)
                    )
                    : null,

            'rfc' =>
                $this->rfc
                    ? strtoupper(
                        trim($this->rfc)
                    )
                    : null,
        ];


        // -----------------------------------------------------
        // CLIENTE NUEVO
        // -----------------------------------------------------

        if (!$this->clienteId) {

            $datos['user_id'] =
                auth()->id();
        }


        // -----------------------------------------------------
        // CREAR O ACTUALIZAR
        // -----------------------------------------------------

        Cliente::updateOrCreate(
            [
                'id' =>
                    $this->clienteId
            ],
            $datos
        );


        $this->cerrarModal();


        session()->flash(
            'mensaje',
            'Cliente guardado correctamente.'
        );
    }


    // =========================================================
    // ELIMINAR CLIENTE
    // =========================================================

    public function eliminar($id)
    {
        $cliente =
            Cliente::withCount('contratos')
                ->findOrFail($id);


        if ($cliente->contratos_count > 0) {

            session()->flash(
                'error',
                "No se puede eliminar a '{$cliente->nombre_completo}' porque tiene {$cliente->contratos_count} contrato(s) asociado(s)."
            );

            return;
        }


        $cliente->delete();


        session()->flash(
            'mensaje',
            'Cliente eliminado correctamente.'
        );
    }
}