<?php

namespace App\Http\Helper;

use Illuminate\Http\Request;
use Modules\Lms\Models\Category;
use Modules\Lms\Models\Course;


class CourseCodeHelper
{
    public function generateCode($course)
    {
        $category = $course->category;

        if (!$category || !$category->code) {
            throw new \Exception('Category code is required');
        }

        $categoryCode = strtoupper(substr($category->code, 0, 3));

        $projectKey = $course->project_source;

        if (!$projectKey) {
            throw new \Exception('Invalid project source for code generation.');
        }

        $count = Course::where('category_id', $course->category_id)->count();
        $serial = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "{$categoryCode}{$projectKey}{$serial}";
    }

}
