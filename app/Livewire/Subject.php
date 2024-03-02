<?php

namespace App\Livewire;

use App\Models\CourseModel;
use App\Models\SubjectModel;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class Subject extends Component
{

    public $form;
    public $select;
    public $search;

    public $id;
    public $course_id;
    public $code;
    public $name;

    public function mount(Request $request) {

        $id = $request->input('id');

        $data = SubjectModel::find($id);

        $this->id = $id;
        $this->course_id = $data->course_id ?? '';
        $this->code = $data->code ?? '';
        $this->name = $data->name ?? '';
    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function create() {

        $rules = [
            'course_id' => 'required|integer|exists:afears_course,id',
            'code' => 'required|min:4',
            'name' => [
                'required',
                'string',
                'min:4',
                Rule::unique('afears_subject')->where(function ($query) {
                    return $query->where('course_id', $this->course_id);
                })
            ]
        ];

        $this->validate($rules);

        $data = [
            'course_id' => htmlspecialchars($this->course_id),
            'code' => htmlspecialchars($this->code),
            'name' =>  htmlspecialchars($this->name)
        ];

        try {

            SubjectModel::create($data);

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Subject `' . ucwords($this->name) . '` created successfully'
            ]);

            $this->course_id = '';
            $this->code = '';
            $this->name = '';

        } catch (\Exception $e) {

            session()->flash('flash', [
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }       
    }

    public function update() {

        $model = SubjectModel::where('id', $this->id)->first();
    
        if ($model) {

            $rules = [
                'course_id' => 'required|integer|exists:afears_course,id',
                'code' => 'required|min:4',
                'name' => [
                    'required',
                    'string',
                    'min:4',
                    Rule::unique('afears_subject')->where(function ($query) {
                        return $query->where('course_id', $this->id);
                    })->ignore($this->id)
                ]
            ];
    
            $this->validate($rules);
            
            try {

                $model->course_id = $this->course_id;
                $model->code = htmlspecialchars($this->code);
                $model->name = htmlspecialchars($this->name);

                $model->save();
    
                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'Subject `' . ucwords($this->name) . '` updated successfully'
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

        $model = SubjectModel::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Subject `'.$model->name.'` deleted successfully'
            ]);
            return redirect()->route('programs.subjects');
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
            if(in_array($view, ['courses'])) {
                $id = $request->input('id');
                $this->select = $id;
            }
        }

        $subjects = SubjectModel::with(['courses.departments.branches'])
            ->when(strlen($this->search) >= 1, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->when($this->select != '', function ($query) {
                $query->where('course_id', $this->select);
            })
            ->get();
            
        $subjects = $subjects->isEmpty() ? [] : $subjects;

        $data = [
            'courses_select' => CourseModel::with(['departments.branches'])->get(),
            'subjects' => $subjects
        ];

        return view('livewire.subject', compact('data'));
    }
}
