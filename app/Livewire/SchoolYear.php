<?php

namespace App\Livewire;

use App\Models\SchoolYearModel;
use App\Models\SubjectModel;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class SchoolYear extends Component
{

    public $form;
    public $select;
    public $search;

    public $id;
    public $name;
    public $start_year;
    public $semester;

    public function mount(Request $request) {

        $id = $request->input('id');

        $data = SchoolYearModel::find($id);

        $this->id = $id;
        $this->name = $data->name ?? '';
        $this->start_year = $data->start_year ?? '';
        $this->semester = $data->semester ?? '';
    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function create() {

        $rules = [
            'name' => 'required|min:4',
            'start_year' => [
                'required',
                Rule::unique('afears_school_year')->where(function ($query) {
                    return $query->where('start_year', $this->start_year)
                        ->where('end_year', $this->start_year + 1)
                        ->where('semester', $this->semester);
                })
            ],
            'semester' => [
                'required',
                Rule::unique('afears_school_year')->where(function ($query) {
                    return $query->where('start_year', $this->start_year)
                        ->where('end_year', $this->start_year + 1)
                        ->where('semester', $this->semester);
                })
            ]
        ];

        $this->validate($rules);

        $data = [
            'name' =>  htmlspecialchars($this->name),
            'start_year' => htmlspecialchars($this->start_year),
            'end_year' => htmlspecialchars($this->start_year + 1),
            'semester' => htmlspecialchars($this->semester),
            'status' => '0'
        ];

        try {

            SchoolYearModel::create($data);

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'School Year `' . ucwords($this->name) . '` created successfully'
            ]);

            $this->name = '';
            $this->start_year = '';
            $this->semester = '';

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
                'name' => 'required|min:4',
                'start_year' => [
                    'required',
                    Rule::unique('afears_school_year')->where(function ($query) {
                        return $query->where('start_year', $this->start_year)
                            ->where('end_year', $this->start_year + 1)
                            ->where('semester', $this->semester);
                    })->ignore($this->id)
                ],
                'semester' => [
                    'required',
                    Rule::unique('afears_school_year')->where(function ($query) {
                        return $query->where('start_year', $this->start_year)
                            ->where('end_year', $this->start_year + 1)
                            ->where('semester', $this->semester);
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
            'subjects' => $subjects
        ];

        return view('livewire.school-year', compact('data'));
    }
}
