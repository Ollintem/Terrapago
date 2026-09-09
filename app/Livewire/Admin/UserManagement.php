<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $nombre, $email, $password, $rol_id, $user_id;
    public $isModalOpen = false;

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'rol_id' => 'required|exists:roles,id',
            'password' => $this->user_id ? 'nullable|min:6' : 'required|min:6',
        ];
    }

    public function render()
    {
        $usuarios = User::with('rol')
            ->where(function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'usuarios' => $usuarios,
            'roles' => Role::all()
        ]);
    }

    public function abrirModal()
    {
        $this->reset(['nombre', 'email', 'password', 'rol_id', 'user_id']);
        $this->isModalOpen = true;
    }

    public function guardar()
    {
        $this->validate();

        User::updateOrCreate(
            ['id' => $this->user_id],
            [
                'nombre' => $this->nombre,
                'email' => $this->email,
                'rol_id' => $this->rol_id,
                'password' => $this->password ? bcrypt($this->password) : User::find($this->user_id)->password,
            ]
        );

        $this->isModalOpen = false;
        session()->flash('message', 'Usuario guardado correctamente.');
    }

    public function editar($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->nombre = $user->nombre;
        $this->email = $user->email;
        $this->rol_id = $user->rol_id;
        $this->isModalOpen = true;
    }
}