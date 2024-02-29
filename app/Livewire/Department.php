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

        $rules = [
            'branch_id' => 'required|integer|exists:afears_branch,id',
            'name' => 'required|string|min:4|unique:afears_department'
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

        } catch (\Exception $e) {

            session()->flash('flash', [
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }       
    }

    public function update(Request $request) {

        $model = DepartmentModel::where('id', $this->id)->first();
    
        if ($model) {

            $rules = [
                'name' => [
                    'required',
                    'string',
                    'min:4',
                    Rule::unique('afears_branch')->ignore($this->id),
                ],
            ];
    
            $this->validate($rules);

            if($this->image instanceof TemporaryUploadedFile) {

                $rules = [
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:5000'
                ];

                $this->validate($rules);

                Storage::disk('public')->delete('images/branches/' . $model->image);
        
                $temp_filename = time();
                $extension = $this->image->getClientOriginalExtension();
        
                $filename = $temp_filename . '.' . $extension;
        
                $this->image->storeAs('public/images/branches', $filename);
                $this->image = $filename;
                $model->image = $filename;
            }
    
            try {

                $model->name = $this->name;
                $model->save();
    
                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'Branch `' . ucwords($this->name) . '` updated successfully'
                ]);
    
            } catch (\Exception $e) {
    
                session()->flash('flash', [
                    'status' => 'failed',
                    'message' => $e->getMessage()
                ]);
            }    
        }
    }

    public function delete(Request $request) {

        $model = DepartmentModel::where('id', $this->id)->first();

        if($model) {
            Storage::disk('public')->delete('images/branches/' . $model->image);
            $model->delete();
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Branch `'.$model->name.'` deleted successfully'
            ]);
            return redirect()->route('programs.branches');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }

    }
    public function render() {
        
        $departments = BranchModel::with(['departments' => function ($query) {
            $query->when($this->select != '', function ($subQuery) {
                $subQuery->where('branch_id', $this->select);
            })
            ->when(strlen($this->search) >= 1, function ($subQuery) {
                $subQuery->where('afears_department.name', 'like', '%' . $this->search . '%');
            });
        }])
        ->when($this->select != '', function ($query) {
            $query->whereHas('departments', function($subQuery) {
                $subQuery->where('branch_id', $this->select);
            });
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
