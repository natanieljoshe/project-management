<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout; 
use App\Exports\ProjectsExport; 
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.app')] 
class Index extends Component
{
    public $name, $description, $deadline, $status;
    public bool $isProjectModalOpen = false;

    //buat cek, apakah project sedang dibuat
   public ?string $editingProjectId = null;

    public bool $isConfirmingDelete = false;
    public ?string $deletingProjectId = null;

    // Aturan validasi
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after_or_equal:today',
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


    //simpan project baru
    public function store(): void
    {
        $validated = $this->validate();
        $validated['user_id'] = Auth::id();
        Project::create($validated);
        session()->flash('message', 'Project successfully created.');
        $this->closeModal();
    }

    //menghapus project
    public function confirmDelete(string $projectId): void
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

     public function export() 
    {
        return Excel::download(new ProjectsExport, 'projects-and-tasks.xlsx');
    }

    public function viewProject(string $projectId)
    {
        return $this->redirect(route('projects.show', $projectId), navigate: true);
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
