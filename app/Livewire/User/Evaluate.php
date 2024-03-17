<?php

namespace App\Livewire\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Models\QuestionnaireModel;
use App\Models\QuestionnaireItemModel;

use App\Models\FacultyModel;
use App\Models\FacultyTemplateModel;
use App\Models\SchoolYearModel;


use Livewire\Component;

class Evaluate extends Component
{

    public $form;
    
    public $evaluate;
    public $semester;
    public $subject;

    public $step;
    public $faculty_id;

    public $faculty = [];

    public $start_time;
    public $end_time;

    public $questionnaire;
    public $responses = [];

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
        if($step == 1) {
            $data = session('response');
            $data['step'] = $step;
            $this->step = $step;
            session()->put('response', $data);
        } else if($step == 2) {
            $this->remember_responses();
            $this->get_questionnaires();
            $this->step02();
            $data = session('response');
            $data['step'] = $step;
            $this->step = $step;
            session()->put('response', $data);
        } else if($step == 3) {
            $this->get_questionnaires();
            $this->step03($step, $this->responses);
        } else if($step == 4) {

        }
    }

    public function step02() {
        $rules = [
            'faculty_id' => 'required|exists:afears_faculty,id|integer',
            'start_time' => 'required',
            'end_time' => 'required'
        ];

        $this->validate($rules);

        $response = session('response');

        if(array_key_exists('faculty', $response)) {
            $response['faculty'] = [
                'faculty_id' => $this->faculty_id,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time
            ];
            session()->put('response', $response);
        } else {
            $data['response'] = [
                'step' => 2,
                'faculty' => [
                    'faculty_id' => $this->faculty_id,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                    'evaluate_id' => $this->evaluate
                ]
            ];

            session($data);
        }
    }

    public function step03($step, $responses) {
        $dirty_item_id = array_keys($responses);
        
        $questionnaire_id = $this->questionnaire->id;
        $data = QuestionnaireItemModel::where('questionnaire_id', $questionnaire_id)->get();

        $cleaned_item_id = $data->pluck('id')->toArray();

        $imposter = array_diff($cleaned_item_id, $dirty_item_id);

        if(!empty($imposter)) {
            session()->flash('error', $imposter);
            $data = session('response');
            $data['record'] = $responses;
            session()->put('response', $data);
        } else {
            $data = session('response');
            $data['step'] = $step;
            $data['record'] = $responses;
            session()->put('response', $data);
            $this->faculty_info(session('response.faculty'));
        }
    
    }

    public function step04($step, $responses) {
        
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

    public function remember_responses() {
        $response = session('response');
        if(array_key_exists('record', $response)) {
            foreach($response['record'] as $item => $value) {
                $this->responses[$item] = $value;
            }
        }
    }

    public function mount(Request $request) {

        $evaluate = $request->input('evaluate');
        $semester = $request->input('semester');
        $subject = $request->input('subject');

        $this->evaluate = $evaluate;
        $this->semester = $semester;
        $this->subject = $subject;

        $this->step = session('response')['step'];

        $input = [
            'id' => $evaluate,
            'semester' => $semester,
            'subject' => $subject
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
            'subject' => [
                'required',
                'integer',
                'exists:afears_subject,id'
            ] 
        ];

        $validate = Validator::make($input, $rules);

        if($validate->fails()) {
            return redirect()->route('user.dashboard');
        }

        if(session()->has('response')) {
            $saved = session('response');
            
            if(array_key_exists('faculty', $saved)) {
                $data = session('response.faculty');
                $this->faculty_id = $data['faculty_id'];
                $this->start_time = $data['start_time'];
                $this->end_time = $data['end_time'];
            }

        }

        if($this->step == 2) {
            $this->get_questionnaires();
            $this->remember_responses();
        } else if ($this->step == 3) {
            $this->get_questionnaires();
            $this->remember_responses();
            $this->faculty_info(session('response.faculty'));
        }
    }

    public function faculty_info($data) {
        $faculty_id = $data['faculty_id'];
        $evaluate_id = $this->evaluate;

        $faculty = FacultyModel::find($faculty_id);
        $subject = FacultyTemplateModel::with('faculty.templates.curriculum_template.subjects')->where(function($query) {
            $query->whereHas('faculty.templates.curriculum_template.subjects', function($subQuery) {
                $subQuery->where('id', $this->subject);
            });
        })->get()[0];
        $evaluate = SchoolYearModel::find($evaluate_id);

        $this->faculty['name'] = $faculty->firstname . ' ' . $faculty->lastname;
        $this->faculty['subject'] = $subject->faculty->templates[0]->curriculum_template[0]->subjects->name . ' (' .
            $subject->faculty->templates[0]->curriculum_template[0]->subjects->code . ')';
        $this->faculty['schedule'] = to_hour($data['start_time']) . ' - ' . to_hour($data['end_time']);
        $this->faculty['academic_year'] = $evaluate->start_year . ' - ' . $evaluate->end_year;
    }

    public function render()
    {
        // session()->forget('response');
        return view('livewire.user.evaluate');
    }
}
