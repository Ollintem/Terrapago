<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Terreno;

class TerrenoManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filtroEstado = '';
    public $modalAbierto = false;
    public $terrenoId;

    public $manzana;
    public $lote;
    public $medidas;
    public $superficie;
    public $precio;
    public $estado = 'DISPONIBLE';
    public $ubicacion;

    protected function rules()
    {
        return [
            'manzana'    => 'required|string|max:20',
            'lote'       => 'required|string|max:20',
            'superficie' => 'required|numeric|min:0.01',
            'medidas'    => 'required|string|max:50',
            'ubicacion'  => 'nullable|string|max:150',
            'precio'     => 'required|numeric|min:0.01',
            'estado'     => 'required|in:DISPONIBLE,APARTADO,VENDIDO',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function render()
    {
        $terrenos = Terreno::query()
            ->when($this->filtroEstado, function ($query) {
                $query->where('estado', $this->filtroEstado);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('manzana', 'like', '%' . $this->search . '%')
                      ->orWhere('lote', 'like', '%' . $this->search . '%')
                      ->orWhere('ubicacion', 'like', '%' . $this->search . '%')
                      ->orWhere('precio', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.terreno-management', [
            'terrenos' => $terrenos,
        ])->layout('layouts.app');
    }

    public function abrirModalCrear()
    {
        $this->reset(['terrenoId', 'manzana', 'lote', 'medidas', 'superficie', 'precio', 'ubicacion']);
        $this->estado = 'DISPONIBLE';
        $this->resetValidation();
        $this->modalAbierto = true;
    }

    public function abrirModalEditar($id)
    {
        $this->resetValidation();
        $terreno = Terreno::findOrFail($id);

        $this->terrenoId  = $terreno->id;
        $this->manzana    = $terreno->manzana;
        $this->lote       = $terreno->lote;
        $this->medidas    = $terreno->medidas;
        $this->superficie = $terreno->superficie;
        $this->precio     = $terreno->precio;
        $this->estado     = $terreno->estado;
        $this->ubicacion  = $terreno->ubicacion;

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

        $existe = Terreno::where('manzana', trim($this->manzana))
            ->where('lote', trim($this->lote))
            ->when($this->terrenoId, fn($q) => $q->where('id', '!=', $this->terrenoId))
            ->exists();

        if ($existe) {
            $this->addError('lote', 'Ya existe un terreno con esa misma Manzana y Lote.');
            return;
        }

        Terreno::updateOrCreate(
            ['id' => $this->terrenoId],
            [
                'manzana'    => trim($this->manzana),
                'lote'       => trim($this->lote),
                'superficie' => $this->superficie,
                'medidas'    => trim($this->medidas),
                'ubicacion'  => $this->ubicacion ? trim($this->ubicacion) : null,
                'precio'     => $this->precio,
                'estado'     => $this->estado,
            ]
        );

        $this->cerrarModal();
        session()->flash('mensaje', 'Terreno registrado correctamente.');
    }

    public function eliminar($id)
    {
        $terreno = Terreno::withCount('contratos')->findOrFail($id);

        if ($terreno->contratos_count > 0) {
            session()->flash('error', "No se puede eliminar el lote {$terreno->ubicacion_completa} porque cuenta con {$terreno->contratos_count} contrato(s) asociado(s).");
            return;
        }

        $terreno->delete();
        session()->flash('mensaje', 'Terreno eliminado del inventario.');
    }
}