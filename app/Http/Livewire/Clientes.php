<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;

class Clientes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $Nombre, $Direccion, $telefono, $rol;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.clientes.view', [
            'clientes' => Cliente::latest()
						->orWhere('Nombre', 'LIKE', $keyWord)
						->orWhere('Direccion', 'LIKE', $keyWord)
						->orWhere('telefono', 'LIKE', $keyWord)
						->orWhere('rol', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->Nombre = null;
		$this->Direccion = null;
		$this->telefono = null;
		$this->rol = null;
    }

    public function store()
    {
        $this->validate([
		'Nombre' => 'required',
		'Direccion' => 'required',
		'telefono' => 'required',
		'rol' => 'required',
        ]);

        Cliente::create([ 
			'Nombre' => $this-> Nombre,
			'Direccion' => $this-> Direccion,
			'telefono' => $this-> telefono,
			'rol' => $this-> rol
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Cliente Successfully created.');
    }

    public function edit($id)
    {
        $record = Cliente::findOrFail($id);

        $this->selected_id = $id; 
		$this->Nombre = $record-> Nombre;
		$this->Direccion = $record-> Direccion;
		$this->telefono = $record-> telefono;
		$this->rol = $record-> rol;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'Nombre' => 'required',
		'Direccion' => 'required',
		'telefono' => 'required',
		'rol' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Cliente::find($this->selected_id);
            $record->update([ 
			'Nombre' => $this-> Nombre,
			'Direccion' => $this-> Direccion,
			'telefono' => $this-> telefono,
			'rol' => $this-> rol
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Cliente Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Cliente::where('id', $id);
            $record->delete();
        }
    }
}
