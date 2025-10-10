<?php

namespace App\View\Components\Dossier\Task;

use Illuminate\View\Component;

class Liste extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $dossier;
    public function __construct($dossier)
    {
        $this->dossier = $dossier;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dossier.task.liste', [
            'dossier' => $this->dossier
        ]);
    }
}
