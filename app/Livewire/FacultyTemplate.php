<?php

namespace App\Livewire;

use App\Models\BranchModel;
use App\Models\CurriculumTemplateModel;
use App\Models\FacultyModel;
use App\Models\FacultyTemplateModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



class FacultyTemplate extends Component
{

    use WithFileUploads;

    public $form;
    public $select = [
        'branch' => '',
        'year'=> '',
        'semester' => ''
    ];
    public $search;

    public $id;
    public $department_id;
    public $employee_number;
    public $firstname;
    public $lastname;
    public $middlename;
    public $gender;
    public $image;
    public $email;
    public $template;
    public object $curriculum_template;

    public function mount(Request $request) {

        $action = $request->input('action');
        $id = $request->input('id');

        $this->id = $id;

        if (in_array($action, ['template', 'connect'])) {
            $data = FacultyModel::with(['templates.curriculum_template.subjects.courses.departments.branches', 'departments.branches'])->where('id', $id)->get()[0]->toArray();
        
            $template_data = [];
            foreach ($data['templates'] as $template) {
                $courseName = $template['curriculum_template'][0]['subjects']['courses']['name'];
                $subjectName = $template['curriculum_template'][0]['subjects']['name'];
                $yearLevel = $template['curriculum_template'][0]['year_level'];
                $semester = $template['curriculum_template'][0]['subject_sem'];
                $branchName = $template['curriculum_template'][0]['subjects']['courses']['departments']['branches']['name'];
        
                $key = "$branchName-$courseName-$yearLevel-$semester";
        
                if (!isset($template_data[$key])) {
                    $template_data[$key] = [
                        'branch' => $branchName,
                        'course' => $courseName,
                        'year' => $yearLevel,
                        'semester' => $semester,
                        'subjects' => []
                    ];
                }
        
                $template_data[$key]['subjects'][] = $subjectName;
            }
        
            $template_data = array_values($template_data);
        
            $data['templates'] = $template_data;
        
        
            $this->template = $data;
        }
        

    }

    public function loadCurriculumTemplate() {
        $id = $this->id;
        $curriculum_template = CurriculumTemplateModel::select('*')
            ->when(strlen($this->search) >= 1, function ($sQuery) {
                $sQuery->whereHas('courses', function($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%');
                    $subQuery->orWhere('code', 'like', '%' . $this->search . '%');
                });
                $sQuery->orWhereHas('subjects', function($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%');
                });
                $sQuery->orWhereHas('departments', function ($dQuery) {
                    $dQuery->where('name', 'like', '%' . $this->search . '%');
                    $dQuery->orWhereHas('branches', function ($bQuery) {
                        $bQuery->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when(!empty($this->select['branch']), function ($query) {
                $query->whereHas('departments.branches', function ($subQuery) {
                    $subQuery->where('branch_id', $this->select['branch']);
                });
            })
            ->when(!empty($this->select['year']), function ($query) {
                $query->where('year_level', $this->select['year']);
            })
            ->when(!empty($this->select['semester']), function ($query) {
                $query->where('subject_sem', $this->select['semester']);
            })
            ->selectRaw('(CASE WHEN EXISTS (
                SELECT 1
                FROM afears_faculty_template
                WHERE afears_faculty_template.template_id = afears_curriculum_template.id
                AND afears_faculty_template.faculty_id = ?
            ) THEN 1 ELSE 0 END) as is_exists', [$id])
            ->with(['departments', 'courses', 'subjects'])
            ->get();
            
            $this->curriculum_template = $curriculum_template;
    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function toggleLink($faculty_id, $template_id) {

        $rules = [
            'faculty_id' => 'required|exists:afears_faculty,id',
            'template_id' => 'required|exists:afears_curriculum_template,id'
        ];

        $data = [
            'faculty_id' => $faculty_id,
            'template_id' => $template_id
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            dd($validator->errors());
        }
        
        $exists = FacultyTemplateModel::where('faculty_id', $faculty_id)
        ->where('template_id', $template_id)->exists();

        if($exists) {
            FacultyTemplateModel::where('faculty_id', $faculty_id)
            ->where('template_id', $template_id)->delete();
        } else {
            FacultyTemplateModel::create([
                'faculty_id' => $faculty_id,
                'template_id' => $template_id
            ]);
        }

    }
    
    public function render(Request $request) {
        
        $action = $request->input('action') ?? '';
        $id = $request->input('id');

        $faculty = FacultyModel::with(['departments.branches'])
            ->when(strlen($this->search) >= 1, function ($sQuery) {
                $sQuery->where(function($query) {
                    $query->where('firstname', 'like', '%' . $this->search . '%');
                });
                $sQuery->orWhereHas('departments', function ($dQuery) {
                    $dQuery->where('name', 'like', '%' . $this->search . '%');
                    $dQuery->orWhereHas('branches', function ($bQuery) {
                        $bQuery->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->select['branch'] != '', function ($query) {
                $query->whereHas('departments.branches', function ($subQuery) {
                    $subQuery->where('branch_id', $this->select);
                });
            })
            ->get();
       
    
        $faculty = $faculty->isEmpty() ? [] : $faculty;

        $this->loadCurriculumTemplate();

        $data = [
            'branches' => BranchModel::with('departments')->get(),
            'faculty' => $faculty,
        ];

        return view('livewire.faculty-template', compact('data'));
       
    }
}
