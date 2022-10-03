<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Categoria;

class Categorias extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $Nombre, $Detalle;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.categorias.view', [
            'categorias' => Categoria::latest()
						->orWhere('Nombre', 'LIKE', $keyWord)
						->orWhere('Detalle', 'LIKE', $keyWord)
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
		$this->Detalle = null;
    }

    public function store()
    {
        $this->validate([
		'Nombre' => 'required',
		'Detalle' => 'required',
        ]);

        Categoria::create([ 
			'Nombre' => $this-> Nombre,
			'Detalle' => $this-> Detalle
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Categoria Successfully created.');
    }

    public function edit($id)
    {
        $record = Categoria::findOrFail($id);

        $this->selected_id = $id; 
		$this->Nombre = $record-> Nombre;
		$this->Detalle = $record-> Detalle;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'Nombre' => 'required',
		'Detalle' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Categoria::find($this->selected_id);
            $record->update([ 
			'Nombre' => $this-> Nombre,
			'Detalle' => $this-> Detalle
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Categoria Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Categoria::where('id', $id);
            $record->delete();
        }
    }
}
