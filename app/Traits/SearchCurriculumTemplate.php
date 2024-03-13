<?php

namespace App\Traits;

use App\Models\CurriculumTemplateModel;

trait SearchCurriculumTemplate {

    public function find(array $data) {

        $course = $data['course'];
        $year_level = $data['year_level'];
        $semester = $data['semester'];

        $dirty = $this->run_query($course, $year_level, $semester);
        $cleaned = $this->organize($dirty);

        $search = [
            'keywords' => [
                'course' => $course,
                'year_level' => $year_level,
                'semester' => $semester
            ],
            'results' => $cleaned
        ];

        return $search;
    }

    public function run_query( $course = null,  $year_level = null,  $semester = null) {

        $data = CurriculumTemplateModel::with(['departments', 'courses', 'subjects'])
        ->when($course, function ($query) use ($course) {
            return $query->where('course_id', $course);
        })
        ->when($year_level, function ($query) use ($year_level) {
            return $query->where('year_level', $year_level);
        })
        ->when($semester, function ($query) use ($semester) {
            return $query->where('subject_sem', $semester);
        })
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'department_id' => $item->departments->id,
                'department_name' => $item->departments->name,
                'course_id' => $item->courses->id,
                'course_code' => $item->courses->code,
                'course_name' => $item->courses->name,
                'subject_code' => $item->subjects->code,
                'subject_name' => $item->subjects->name,
                'year_level' => $item->year_level,
                'semester' => $item->subject_sem,
                'date_added' => $item->date_added,
                'date_updated' => $item->date_updated,
            ];
        })
        ->toArray();
        return $data;
    } 

    public function organize($data) {

        $templates = [];

        foreach ($data as $item) {

            $department_key = $item['department_id'];
            $course_key = $item['course_id'];
            $year_level = $item['year_level'];
            $semester = $item['semester'];

            if (!isset($templates[$department_key])) {
                $templates[$department_key] = [
                    'name' => $item['department_name'],
                    'year' => []
                ];
            }

            if (!isset($templates[$department_key]['year'][$year_level])) {
                $templates[$department_key]['year'][$year_level] = [
                    'name' => to_ordinal($year_level,  'year'),
                    'course' => []
                ];
            }

            if (!isset($templates[$department_key]['year'][$year_level]['course'][$course_key])) {
                $templates[$department_key]['year'][$year_level]['course'][$course_key] = [
                    'code' => $item['course_code'],
                    'name' => $item['course_name'],
                    'semester' => []
                ];
            }

            if(!isset($templates[$department_key]['year'][$year_level]['course'][$course_key]['semester'][$semester])) {
                $templates[$department_key]['year'][$year_level]['course'][$course_key]['semester'][$semester] = [
                    'name' => to_ordinal($semester, 'semester'),
                    'subjects' => []
                ];
            }

            $templates[$department_key]['year'][$year_level]['course'][$course_key]['semester'][$semester]['subjects'][] = [
                'id' => $item['id'],
                'code' => $item['subject_code'],
                'name' => $item['subject_name'],
                'date_added' => $item['date_added'],
                'date_updated' => $item['date_updated']
            ];
                
        }

        $templates = array_map(function ($department_data) {
            $department_data['year'] = array_values($department_data['year']);
            $department_data['year'] = array_map(function ($year_data) {
                $year_data['course'] = array_values($year_data['course']);
                $year_data['course'] = array_map(function ($course_data) {
                    $course_data['semester'] = array_values($course_data['semester']);
                    return $course_data;
                }, $year_data['course']);
                return $year_data;
            }, $department_data['year']);
            return $department_data;
        }, $templates);

        $templates = array_values($templates);

        return $templates;
    }
    


}