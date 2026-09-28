<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Role;

class RoleManagement extends Component
{
    public $search = '';
    public $modalAbierto = false;
    public $modoEdicion = false;

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

        // Al editar un rol existente se conserva la validación
        // de la descripción que ya tenía el sistema.
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

        session()->flash(
            'mensaje',
            'Rol guardado correctamente.'
        );
    }

    public function eliminar($id)
    {
        $rol = Role::withCount('users')->findOrFail($id);

        // Protección 1: No eliminar los roles principales
        if (
            in_array(
                strtolower($rol->nombre),
                [
                    'administrador',
                    'super admin',
                    'superadministrador'
                ]
            )
        ) {
            session()->flash(
                'error',
                'El rol principal de Administrador no puede ser eliminado.'
            );

            return;
        }

        // Protección 2: Evitar eliminación si hay usuarios asignados
        if ($rol->users_count > 0) {
            session()->flash(
                'error',
                "No se puede eliminar '{$rol->nombre}': tiene {$rol->users_count} usuario(s) asignado(s). Reasígnalos primero."
            );

            return;
        }

        $rol->delete();

        session()->flash(
            'mensaje',
            'Rol eliminado correctamente.'
        );
    }
}