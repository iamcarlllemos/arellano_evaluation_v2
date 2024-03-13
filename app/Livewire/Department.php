<?php

namespace App\Livewire;

use App\Models\BranchModel;
use App\Models\DepartmentModel;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class Department extends Component
{

    use WithFileUploads;

    public $form;
    public $select;
    public $search;

    public $id;
    public $status;

    public $branch_id;
    public $name;

    public function mount(Request $request) {
        $id = $request->input('id');
        $data = DepartmentModel::find($id);

        $this->id = $id;
        $this->branch_id = $data->branch_id ?? '';
        $this->name = $data->name ?? '';
    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function create() {

        $branch_id = $this->branch_id;

        $rules = [
            'branch_id' => 'required|integer|exists:afears_branch,id',
            'name' => [
                'required',
                'string',
                'min:4',
                Rule::unique('afears_department')->where(function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })
            ]
        ];


        $this->status = 'failed';

        $this->validate($rules);


        $data = [
            'branch_id' => htmlspecialchars($this->branch_id),
            'name' =>  $this->name
        ];

        try {

            DepartmentModel::create($data);

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Department `' . ucwords($this->name) . '` created successfully'
            ]);

            $this->branch_id = '';
            $this->name = '';

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


        $departments = DepartmentModel::with(['branches'])
            ->when(strlen($this->search) >= 1, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->select != '', function ($query) {
                $query->where('branch_id', $this->select);
            })
            ->get();
    
        $departments = $departments->isEmpty() ? [] : $departments;


        $data = [
            'branches' => BranchModel::with('departments')->get(),
            'departments' => $departments
        ];

        return view('livewire.department', compact('data'));
    }
}
