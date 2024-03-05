<?php

namespace App\Livewire;

use App\Models\BranchModel;
use App\Models\StudentModel;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class Student extends Component
{

    use WithFileUploads;

    public $form;
    public $select;
    public $search;

    public $id;
    public $status;
    public $course_id;
    public $student_number;
    public $firstname;
    public $lastname;
    public $middlename;
    public $gender;
    public $birthday;
    public $year_level;
    public $image;
    public $email;
    public $username;
    public $password;
    public $password_repeat;

    public function mount(Request $request) {
        $id = $request->input('id');
        $data = StudentModel::find($id);

        $this->id = $id;
        $this->course_id = $data->course_id ?? '';
        $this->student_number = $data->student_number ?? '';
        $this->firstname = $data->firstname ?? '';
        $this->lastname = $data->lastname ?? '';
        $this->middlename = $data->middlename ?? '';
        $this->gender = $data->gender ?? '';
        $this->birthday = $data->birthday ?? '';
        $this->year_level = $data->year_level ?? '';
        $this->image = $data->image ?? '';
        $this->email = $data->email ?? '';
        $this->username = $data->username ?? '';
        $this->password = $data->password ?? '';
        $this->password_repeat = $data->password_repeat ?? '';
    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function create() {

        $rules = [
            'course_id' => 'required|integer|exists:afears_course,id',
            'student_number' => 'required',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'string|min:8',
            'gender' => 'required|integer|in:1,2,3,4',
            'birthday' => 'required',
            'year_level' => 'required|integer|in:1,2',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5000',
            'email' => 'required|email|unique:afears_student,email',
            'username' => 'required|string|unique:afears_student,username',
            'password' => 'required|string|min:8|same:password_repeat',
            'password_repeat' => 'required|string|min:8|same:password'
        ];


        $this->status = 'failed';

        $this->validate($rules);

        $temp_filename = time();
        $extension =$this->image->getClientOriginalExtension();

        $filename = $temp_filename . '.' . $extension;

        $data = [
            'course_id' => $this->course_id,
            'student_number' => $this->student_number,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'middlename' => $this->middlename,
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'year_level' => $this->year_level,
            'image' => $filename,
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'password_repeat' => $this->password_repeat,
        ];

        try {

            StudentModel::create($data);
            $this->image->storeAs('public/images/students', $filename);

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Student `' . ucwords($this->firstname . ' ' . $this->lastname) . '` created successfully'
            ]);

            $this->course_id = '';
            $this->student_number = '';
            $this->firstname = '';
            $this->lastname = '';
            $this->middlename = '';
            $this->gender = '';
            $this->birthday = '';
            $this->year_level = '';
            $this->image = '';
            $this->email = '';
            $this->username = '';
            $this->password = '';
            $this->password_repeat = '';

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

        $students = StudentModel::with(['courses.departments.branches'])->get();

        // dd($departments->toArray());
    
        $students = $students->isEmpty() ? [] : $students;

        $data = [
            'branches' => BranchModel::with('departments')->get(),
            'students' => $students
        ];


        return view('livewire.student', compact('data'));
    }
}
