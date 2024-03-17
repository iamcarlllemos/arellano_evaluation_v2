<?php

namespace App\Livewire\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Models\QuestionnaireModel;
use App\Models\QuestionnaireItemModel;


use Livewire\Component;

class Evaluate extends Component
{

    public $form;
    
    public $evaluate;
    public $semester;
    public $step;
    public $faculty_name;
    public $start_time;
    public $end_time;

    public $questionnaire;
    public $responses = [];

    public function get_faculty() {

    }


    public function validate_step() {
        $data = [
            'step' => $this->step
        ];

        $rules = [
            'step' => 'required|integer|in:1,2,3,4'
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return false;
        } 

        return true;

    }

    public function move($step) {
        if($step == 2) {
            $this->get_questionnaires();
            $data = session('response');
            $data['step'] = $step;
            $this->step = $step;
            session()->put('response', $data);
        } else if($step == 3) {
            $this->store_step_3($step, $this->responses);
            $this->get_questionnaires();
        }
    }

    public function store_step_3($step, $responses) {
        $dirty_item_id = array_keys($responses);
        
        $questionnaire_id = $this->questionnaire->id;
        $data = QuestionnaireItemModel::where('questionnaire_id', $questionnaire_id)->get();

        $cleaned_item_id = $data->pluck('id')->toArray();

        $imposter = array_diff($cleaned_item_id, $dirty_item_id);

        if(!empty($imposter)) {
            session()->flash('error', $imposter);
        }
        
    }

    public function get_questionnaires() {
        $data = QuestionnaireModel::with('school_year', 'questionnaire_item.criteria')->where(function($query) {
            $query->whereHas('school_year', function($subQuery) {
                $subQuery->where('semester', 1);
            });
        })->get()[0];
        
        $sorted_item = [];

        foreach($data['questionnaire_item'] as $item) {
            $key = $item['criteria_id'];
            if(!isset($sorted_item[$key])) {
                $sorted_item[$key] = [
                    'id' => $item['id'],
                    'criteria_name' => $item['criteria']['name'],
                    'item' => []
                ];
            }

            $sorted_item[$key]['item'][] = [
                'id' => $item['id'],
                'name' => $item['item']
            ];
        }

        $data['sorted_items'] = array_values($sorted_item);

        $this->questionnaire = $data;
        

    }

    public function mount(Request $request) {

        $evaluate = $request->input('evaluate');
        $semester = $request->input('semester');

        $this->evaluate = $evaluate;
        $this->semester = $semester;

        $this->step = session('response')['step'];

        $input = [
            'id' => $evaluate,
            'semester' => $semester,
        ];

        $rules = [
            'id' => [
                'required',
                'integer',
                Rule::exists('afears_school_year')->where(function($query) use($evaluate, $semester) {
                    $query->where('id', $evaluate)
                        ->where('semester', $semester)
                        ->where('status', 1);
                })
            ],
            'semester' => [
                'required',
                'integer'
            ],
        ];

        $validate = Validator::make($input, $rules);

        if($validate->fails()) {
            return redirect()->route('user.dashboard');
        }

        if(session()->has('response')) {
            $saved = session('response');
            
            if(array_key_exists('step_1', $saved)) {
                $data = session('response.step_1');
                $this->faculty_name = $data['faculty_name'];
                $this->start_time = $data['start_time'];
                $this->end_time = $data['end_time'];
            }

        }

        if($this->step == 2) {
            $this->get_questionnaires();
        }
    }


    public function save(Request $request) {
        if($this->validate_step()) {
            switch($this->step) {
                case 1:
                    $this->step1();
                    break;
                case 2:
                    
                    break;
                case 3:
    
                    break;
                case 4:
    
                    break;
            }
        }
    }

    public function render()
    {
        // session()->forget('response');
        return view('livewire.user.evaluate');
    }
}
