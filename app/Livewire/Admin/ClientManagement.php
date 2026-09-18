<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;
use App\Http\Requests\ClientRequest;

class ClientManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $modalAbierto = false;
    public $clienteId;

    // Campos acordes a la migración
    public $nombre;
    public $apellido_paterno;
    public $apellido_materno;
    public $fecha_nacimiento;
    public $telefono;
    public $email;
    public $direccion;
    public $curp;
    public $rfc;
    public $estado = true;

    protected function rules()
    {
        return [
            'nombre'           => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'telefono'         => 'required|string|max:20',
            'email'            => 'nullable|email|max:150|unique:clientes,email,' . $this->clienteId,
            'direccion'        => 'nullable|string',
            'curp'             => 'nullable|string|max:20',
            'rfc'              => 'nullable|string|max:20',
            'estado'           => 'boolean',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $esAdmin = $user->rol && in_array(strtolower($user->rol->nombre), ['administrador', 'super admin', 'superadministrador']);

        $clientes = Cliente::with('asesor')
            // Si no es admin, solo ve sus propios clientes
            ->when(!$esAdmin, function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('apellido_paterno', 'like', '%' . $this->search . '%')
                      ->orWhere('apellido_materno', 'like', '%' . $this->search . '%')
                      ->orWhere('telefono', 'like', '%' . $this->search . '%')
                      ->orWhere('rfc', 'like', '%' . $this->search . '%')
                      ->orWhere('curp', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.client-management', [
            'clientes' => $clientes,
            'esAdmin'  => $esAdmin,
        ])->layout('layouts.app');
    }

    public function abrirModalCrear()
    {
        $this->reset([
            'clienteId', 'nombre', 'apellido_paterno', 'apellido_materno',
            'fecha_nacimiento', 'telefono', 'email', 'direccion', 'curp', 'rfc'
        ]);
        $this->estado = true;
        $this->resetValidation();
        $this->modalAbierto = true;
    }

    public function abrirModalEditar($id)
    {
        $this->resetValidation();
        $cliente = Cliente::findOrFail($id);

        $this->clienteId        = $cliente->id;
        $this->nombre           = $cliente->nombre;
        $this->apellido_paterno = $cliente->apellido_paterno;
        $this->apellido_materno = $cliente->apellido_materno;
        $this->fecha_nacimiento = $cliente->fecha_nacimiento ? $cliente->fecha_nacimiento->format('Y-m-d') : null;
        $this->telefono         = $cliente->telefono;
        $this->email            = $cliente->email;
        $this->direccion        = $cliente->direccion;
        $this->curp             = $cliente->curp;
        $this->rfc              = $cliente->rfc;
        $this->estado           = (bool) $cliente->estado;

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

        $datos = [
            'nombre'           => trim($this->nombre),
            'apellido_paterno' => trim($this->apellido_paterno),
            'apellido_materno' => $this->apellido_materno ? trim($this->apellido_materno) : null,
            'fecha_nacimiento' => $this->fecha_nacimiento ?: null,
            'telefono'         => trim($this->telefono),
            'email'            => $this->email ? trim($this->email) : null,
            'direccion'        => $this->direccion ? trim($this->direccion) : null,
            'curp'             => $this->curp ? strtoupper(trim($this->curp)) : null,
            'rfc'              => $this->rfc ? strtoupper(trim($this->rfc)) : null,
            'estado'           => $this->estado,
        ];

        // Si es un cliente nuevo, se asocia al usuario logueado
        if (!$this->clienteId) {
            $datos['user_id'] = auth()->id();
        }

        Cliente::updateOrCreate(['id' => $this->clienteId], $datos);

        $this->cerrarModal();
        session()->flash('mensaje', 'Cliente guardado correctamente.');
    }

    public function cambiarEstado($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->estado = !$cliente->estado;
        $cliente->save();
    }

    public function eliminar($id)
    {
        $cliente = Cliente::withCount('contratos')->findOrFail($id);

        if ($cliente->contratos_count > 0) {
            session()->flash('error', "No se puede eliminar a '{$cliente->nombre_completo}' porque tiene {$cliente->contratos_count} contrato(s) asociado(s).");
            return;
        }

        $cliente->delete();
        session()->flash('mensaje', 'Cliente eliminado correctamente.');
    }
}