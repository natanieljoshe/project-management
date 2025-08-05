<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.app')] 
class Index extends Component
{
    public $name, $description, $deadline, $status;
    public bool $isProjectModalOpen = false;

    //buat cek, apakah project sedang diedit
    public ?int $editingProjectId = null;

    public bool $isConfirmingDelete = false;
    public ?int $deletingProjectId = null;

    // Aturan validasi
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'status' => 'required|string',
        ];
    }

    //buka dan tutup modal
     public function openModal(): void
    {
        $this->reset('name', 'description', 'deadline', 'status', 'editingProjectId');
        $this->isProjectModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isProjectModalOpen = false;
        $this->reset('name', 'description', 'deadline', 'status', 'editingProjectId');
    }


    public function edit(Project $project): void
    {
        // user hanya bisa mengedit proyek miliknya
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }

        $this->editingProjectId = $project->id;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->deadline = $project->deadline->format('Y-m-d');
        $this->status = $project->status;

        $this->isProjectModalOpen = true;
    }

    // menyimpan perubahan
    public function update(): void
    {
        $validated = $this->validate();

        $project = Project::findOrFail($this->editingProjectId);

        // Pastikan user hanya bisa mengupdate proyek miliknya
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }
        
        $project->update($validated);

        session()->flash('message', 'Project successfully updated.');

        $this->closeModal();
    }

    //simpan project baru
     public function store(): void
    {
        //mode edit, panggil update()
        if ($this->editingProjectId) {
            $this->update();
            return;
        }

        $validated = $this->validate();
        $validated['user_id'] = Auth::id();
        Project::create($validated);
        session()->flash('message', 'Project successfully created.');
        $this->closeModal();
    }

    //menghapus project
    public function confirmDelete(int $projectId): void
    {
        $this->deletingProjectId = $projectId;
        $this->isConfirmingDelete = true;
    }

    public function delete(): void
    {
        $project = Project::findOrFail($this->deletingProjectId);

        // Keamanan: pastikan user hanya bisa menghapus proyek miliknya
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }

        $project->delete();

        $this->isConfirmingDelete = false;
        session()->flash('message', 'Project successfully deleted.');
    }


    public function render()
    {
        // ambil semua project, urutkan dari yang terbaru
        $projects = Project::where('user_id', Auth::id())
                           ->latest()
                           ->get();

        return view('livewire.projects.index', [
            'projects' => $projects,
        ]); 
        // return view('livewire.projects.index');
    }
}
