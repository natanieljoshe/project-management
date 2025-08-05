<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class ProjectsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         return Project::where('user_id', Auth::id())->with('tasks')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        //header
        return [
            'Project Name',
            'Project Status',
            'Project Deadline',
            'Task Title',
            'Task Status',
            'Task Deadline',
        ];
    }

    public function map($project): array
    {
        if ($project->tasks->isEmpty()) {
            return [
                $project->name,
                $project->status,
                $project->deadline->format('Y-m-d'),
                'N/A', // Task Title
                'N/A', // Task Status
                'N/A', // Task Deadline
            ];
        }
        $rows = [];
        foreach ($project->tasks as $task) {
            $rows[] = [
                $project->name,
                $project->status,
                $project->deadline->format('Y-m-d'),
                $task->title,
                $task->status,
                $task->deadline->format('Y-m-d'),
            ];
        }
        return $rows;
    }
}