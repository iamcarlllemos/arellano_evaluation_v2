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
    public object $template;
    public object $curriculum_template;
    protected $listeners = [
        'refresh' => '$refresh'
    ];

    public function mount(Request $request) {

        $action = $request->input('action');
        $id = $request->input('id');

        $this->id = $id;

        if(in_array($action, ['template', 'connect'])) {
            $data = FacultyModel::with(['templates', 'departments.branches'])->where('id', $id)->get()[0] ?? [];
            $this->template = $data;
        } else {
            $data = FacultyModel::find($id);
            $this->id = $id;
            $this->department_id = $data->department_id ?? '';
            $this->employee_number = $data->employee_number ?? '';
            $this->firstname = $data->firstname ?? '';
            $this->lastname = $data->lastname ?? '';
            $this->middlename = $data->middlename ?? '';
            $this->gender = $data->gender ?? '';
            $this->birthday = $data->birthday ?? '';
            $this->year_level = $data->year_level ?? '';
            $this->image = $data->image ?? '';
            $this->email = $data->email ?? '';
            $this->username = $data->username ?? '';
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

    public function create() {

        $rules = [
            'employee_number' => 'required|unique:afears_faculty,employee_number',
            'department_id' => 'required|integer|exists:afears_department,id',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'string',
            'email' => 'required|email|unique:afears_faculty,email',
            'gender' => 'required|integer|in:1,2,3',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5000',
        ];

        $this->validate($rules);

        $temp_filename = time();
        $extension =$this->image->getClientOriginalExtension();

        $filename = $temp_filename . '.' . $extension;

        $data = [
            'department_id' => $this->department_id,
            'employee_number' => $this->employee_number,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'middlename' => $this->middlename,
            'email' => $this->email,
            'gender' => $this->gender,
            'image' => $filename,
        ];

        try {

            FacultyModel::create($data);
            $this->image->storeAs('public/images/faculty', $filename);

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Faculty `' . ucwords($this->firstname . ' ' . $this->lastname) . '` created successfully'
            ]);

            $this->department_id = '';
            $this->employee_number = '';
            $this->firstname = '';
            $this->lastname = '';
            $this->middlename = '';
            $this->gender = '';
            $this->image = '';
            $this->email = '';

        } catch (\Exception $e) {

            session()->flash('flash', [
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }       
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

    public function update() {

        $model = FacultyModel::where('id', $this->id)->first();
    
        if ($model) {

            $rules = [
                'department_id' => 'required|integer|exists:afears_department,id',
                'employee_number' => [
                    'required',
                    Rule::unique('afears_faculty')->where(function($query) {
                        return $query->where('employee_number', $this->employee_number);
                    })->ignore($this->id)
                ],
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'middlename' => 'string',
                'gender' => 'required|integer|in:1,2,3',
                'email' =>  [
                    'required',
                    'string',
                    'email',
                    Rule::unique('afears_faculty')->where(function($query) {
                        return $query->where('email', $this->email);
                    })->ignore($this->id)
                ],
            ];
    
            $this->validate($rules);
            
            if($this->image instanceof TemporaryUploadedFile) {

                $rules = [
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:5000'
                ];

                $this->validate($rules);

                Storage::disk('public')->delete('images/faculty/' . $model->image);
        
                $temp_filename = time();
                $extension = $this->image->getClientOriginalExtension();
        
                $filename = $temp_filename . '.' . $extension;
        
                $this->image->storeAs('public/images/faculty', $filename);
                $this->image = $filename;
                $model->image = $filename;

            }


            try {

                $model->department_id = $this->department_id;
                $model->employee_number = $this->employee_number;
                $model->firstname = $this->firstname;
                $model->lastname = $this->lastname;
                $model->middlename = $this->middlename;
                $model->gender = $this->gender;
                $model->email = $this->email;
                $model->save();
    
                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'Faculty `' . ucwords($this->firstname . ' ' . $this->lastname) . '` updated successfully'
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

        $model = FacultyModel::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Faculty `' . ucwords($this->firstname . ' ' . $this->lastname) . '` deleted successfully'
            ]);
            return redirect()->route('accounts.faculty');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
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
