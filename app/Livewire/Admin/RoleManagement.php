<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Role;

class RoleManagement extends Component
{
    public $search = '';
    public $modalAbierto = false;
    public $modoEdicion = false;

    // Control de vista: 'index' o 'delete'
    public $vistaActual = 'index';
    public $rolAEliminar = null;

    public $rolId;
    public $nombre;
    public $descripcion;

    protected function rules()
    {
        // Al crear un nuevo rol solamente se solicita el nombre
        if (!$this->modoEdicion) {
            return [
                'nombre' => 'required|string|max:50|unique:roles,nombre',
            ];
        }

        // Al editar un rol existente
        return [
            'nombre'      => 'required|string|max:50|unique:roles,nombre,' . $this->rolId,
            'descripcion' => 'nullable|string|max:255',
        ];
    }

    /**
     * Mensajes personalizados de validación.
     */
    protected function messages()
    {
        return [
            'nombre.required' => 'El nombre del puesto es obligatorio.',
            'nombre.string'   => 'El nombre del puesto debe ser texto.',
            'nombre.max'      => 'El nombre del puesto no puede tener más de 50 caracteres.',
            'nombre.unique'   => 'Ya existe un puesto o rol con ese nombre.',

            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max'    => 'La descripción no puede tener más de 255 caracteres.',
        ];
    }

    public function cambiarVista($vista)
    {
        $this->vistaActual = $vista;
        if ($vista === 'index') {
            $this->rolAEliminar = null;
        }
    }

    public function abrirModalCrear()
    {
        $this->reset([
            'rolId',
            'nombre',
            'descripcion',
            'modoEdicion'
        ]);

        $this->resetValidation();
        $this->modoEdicion = false;
        $this->modalAbierto = true;
    }

    public function abrirModalEditar($id)
    {
        $this->resetValidation();

        $rol = Role::findOrFail($id);

        $this->rolId = $rol->id;
        $this->nombre = $rol->nombre;
        $this->descripcion = $rol->descripcion;
        $this->modoEdicion = true;
        $this->modalAbierto = true;
    }

    public function cerrarModal()
    {
        $this->resetValidation();
        $this->modalAbierto = false;
    }

    public function guardar()
    {
        $this->validate();

        // CREAR NUEVO ROL
        if (!$this->modoEdicion) {
            Role::create([
                'nombre' => trim($this->nombre),
            ]);
        } else {
            // EDITAR ROL EXISTENTE
            $rol = Role::findOrFail($this->rolId);

            $rol->update([
                'nombre'      => trim($this->nombre),
                'descripcion' => trim($this->descripcion ?? ''),
            ]);
        }

        $this->cerrarModal();

        session()->flash('mensaje', 'Rol guardado correctamente.');
    }

    /**
     * Prepara los datos y abre el modal flotante de eliminación.
     */
    public function confirmarEliminar($id)
    {
        $rol = Role::withCount('users')->findOrFail($id);

        // Protección 1: No eliminar los roles principales
        if (
            in_array(
                strtolower($rol->nombre),
                ['administrador', 'super admin', 'superadministrador']
            )
        ) {
            session()->flash('error', 'El rol principal de Administrador no puede ser eliminado.');
            return;
        }

        $this->rolAEliminar = $rol;
        // Ya no cambiamos $vistaActual porque se muestra con @include en index
    }

    /**
     * Cierra el modal flotante de eliminación.
     */
    public function cancelarEliminar()
    {
        $this->rolAEliminar = null;
    }

    /**
     * Ejecuta el borrado tras confirmar en el modal delete.
     */
    public function ejecutarEliminar()
    {
        if (!$this->rolAEliminar) {
            return;
        }

        $rol = Role::withCount('users')->findOrFail($this->rolAEliminar->id);

        // Protección 2: Evitar eliminación si tiene usuarios asignados
        if ($rol->users_count > 0) {
            session()->flash(
                'error',
                "No se puede eliminar '{$rol->nombre}': tiene {$rol->users_count} usuario(s) asignado(s). Reasígnalos primero."
            );
            $this->rolAEliminar = null;
            return;
        }

        $nombre = $rol->nombre;

        // Eliminar permisos asociados si existe la relación
        if (method_exists($rol, 'permisos')) {
            $rol->permisos()->delete();
        }

        $rol->delete();

        $this->rolAEliminar = null;
        session()->flash('mensaje', "El rol '{$nombre}' fue eliminado correctamente.");
    }

    public function render()
    {
        $roles = Role::withCount('users')
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->get();

        return view('livewire.admin.roles.index', [
            'roles' => $roles,
        ])->layout('layouts.app');
    }
}