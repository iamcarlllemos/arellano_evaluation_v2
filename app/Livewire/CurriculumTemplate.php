<?php

namespace App\Livewire;

use App\Models\BranchModel;
use App\Models\CourseModel;
use App\Models\CurriculumTemplateModel;
use App\Models\DepartmentModel;
use App\Models\SubjectModel;
use App\Traits\SearchCurriculumTemplate;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class CurriculumTemplate extends Component
{

    use SearchCurriculumTemplate;

    public $form;
    public $select;
    public $search;

    public $id;
    public $departments = [];
    public $courses = [];
    public $subjects = [];
    public $department_id;
    public $course_id;
    public $subject_id;
    public $year_level;
    public $subject_sem;

    public function loadDepartments($id = null) {

        if($id == null) {
            $clean = [];
            $dirty = DepartmentModel::with('branches')->get();

            foreach($dirty as $item) {
                $clean[] = (object)[
                    'id' => $item->id,
                    'name' => $item->name . ' - (' . $item['branches']->name .  ')'
                ];
            }
            return $this->departments = $clean;
        } else {
            $this->department_id = $id;
            $this->loadCourses($id);
            $this->loadSubjects();
        }
    }

    public function loadCourses($id = null) {
        if($id != null) {
            $data = CourseModel::where('department_id', $this->department_id)->get();
            $this->loadSubjects();
            return $this->courses = $data;
        } 
    }

    public function loadYear($id) {
        $this->year_level = $id;
    }

    public function loadSubjects() {
        $data = SubjectModel::with(['courses.departments'])
            ->when($this->course_id != '', function($subQuery) {
                $subQuery->whereHas('courses', function($query) {
                    $query->where('id', $this->course_id);
                });
            })
            ->when($this->department_id != '', function($subQuery) {
                $subQuery->whereHas('courses.departments', function($query) {
                    $query->where('id', $this->department_id);
                });
            })
            ->get();
        $this->subjects = $data;
    }

    public function mount(Request $request) {

        $this->loadDepartments();

        $action = $request->input('action');
        $id = $request->input('id');
        $data = CurriculumTemplateModel::first();

        if($data) {

            $this->id = $id;
            $this->department_id = $data->department_id ?? '';
            $this->course_id = $data->course_id ?? '';
            $this->subject_id = $data->subject_id ?? '';
            $this->subject_sem = $data->subject_sem ?? '';
            $this->year_level = $data->year_level ?? '';

            if(in_array($action, ['update', 'delete'])) {
                $dirty = DepartmentModel::with('branches')->get();
                
                $clean = [];

                foreach($dirty as $item) {
                    $clean[] = (object)[
                        'id' => $item->id,
                        'name' => $item->name . ' - (' . $item['branches']->name .  ')'
                    ];
                }
                $this->departments = $clean;
                $this->courses = CourseModel::all();
                $this->subjects = SubjectModel::all();
            } 
        }

 

        
    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function create() {


        $rules = [
            'department_id' => 'required|integer|exists:afears_department,id',
            'course_id' => 'required|integer|exists:afears_course,id',
            'subject_id' => 'required|integer|exists:afears_subject,id',
            'subject_sem' => 'required|integer|in:1,2',
            'year_level' => 'required|integer|in:1,2,3,4',
        ];

        $this->validate($rules);

        $data = [
            'department_id' => $this->department_id,
            'course_id' => $this->course_id,
            'subject_id' => $this->subject_id,
            'subject_sem' => $this->subject_sem,
            'year_level' => $this->year_level,
        ];

        try {

            CurriculumTemplateModel::create($data);

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Curriculum Template created successfully'
            ]);

            $this->department_id = '';
            $this->course_id = '';
            $this->subject_id = '';
            $this->subject_sem = '';
            $this->year_level = '';

        } catch (\Exception $e) {

            session()->flash('flash', [
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }       
    }

    public function update() {

        $model = DepartmentModel::where('id', $this->id)->first();
    
        if ($model) {

            $rules = [
                'branch_id' => 'required|integer|exists:afears_branch,id',
                'name' => [
                    'required',
                    'string',
                    'min:4',
                    Rule::unique('afears_department')->where(function ($query) {
                        return $query->where('branch_id', $this->id);
                    })->ignore($this->id)
                ]
            ];
    
            $this->validate($rules);
            
            try {

                $model->branch_id = $this->branch_id;
                $model->name = $this->name;

                $model->save();
    
                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'Department `' . ucwords($this->name) . '` updated successfully'
                ]);
    
            } catch (\Exception $e) {
    
                session()->flash('flash', [
                    'status' => 'failed',
                    'message' => $e->getMessage()
                ]);
            }    
        }
    }

    public function delete() {

        $model = DepartmentModel::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Department `'.$model->name.'` deleted successfully'
            ]);
            return redirect()->route('programs.departments');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }
    }
    public function render(Request $request) {
        
        $action = $request->input('action') ?? '';

        if($action == 'open') {
            $view = $request->input('view');
            if(in_array($view, ['departments'])) {
                $id = $request->input('id');
                $this->select = $id;
            }
        }


        $keywords = [
            'course' => $request->input('course'),
            'year_level' => $request->input('year_level'),
            'semester' => $request->input('semester')
        ];
       
        $templates = $this->find($keywords);


        $data = [
            'templates' => $templates
        ];
        

        return view('livewire.curriculum-template', compact('data'));
    }
}
