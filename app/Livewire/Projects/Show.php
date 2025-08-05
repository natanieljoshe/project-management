<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Show extends Component
{
    //menampung data project
    public Project $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }
    public function render()
    {
        return view('livewire.projects.show');
    }
}
