<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] 
class Dashboard extends Component
{
    public $allProjects;
    public $selectedPriorityProject;

    public function mount()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $this->allProjects = $user ? $user->projects()->orderBy('name')->get() : collect();
        $this->selectedPriorityProject = $user ? $user->priority_project_id : null;
    }

    public function updatedSelectedPriorityProject($projectId)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user) {
            $user->update(['priority_project_id' => $projectId]);
            $this->dispatch('priority-updated');
        }
    }

    public function render()
    {

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $ongoingProjectsCount = $user ? $user->projects()->where('status', 'ON-GOING')->count() : 0;
        $pendingProjectsCount = $user->projects()->where('status', 'PENDING')->count();

        // ambil priority project
        // kalau belum diset, ambil proyek terlama
        $priorityProject = $user->priorityProject ?? $user->projects()->oldest()->first();

        //upcoming task
        $upcomingTasks = Task::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('status', '!=', 'DONE')
        ->whereDate('deadline', '>=', today())
        ->orderBy('deadline', 'asc')
        ->limit(8)
        ->get();

        return view('livewire.dashboard', [
            'ongoingProjectsCount' => $ongoingProjectsCount,
            'pendingProjectsCount' => $pendingProjectsCount, 
            'priorityProject' => $priorityProject,
            'upcomingTasks' => $upcomingTasks,
        ]);
    }
}