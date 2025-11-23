<?php

namespace App\Services;
use Modules\Lms\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class GenerateCodeService
{


    function generateCourseCode($course, $category)
    {
        if (!$category || !$category->code) {
            throw new \Exception("Missing category or category code.");
        }
        $projectKey = $course->project_source;
        if (!$projectKey) {
            throw new \Exception("Missing project source for course ID {$course->id}.");
        }
        $categoryCode = strtoupper(substr($category->code, 0, 3));
        $existingCourses = Course::where('category_id', $category->id)
            ->where('project_source', $projectKey)
            ->whereNotNull('code')
            ->get();
        $maxIndex = 0;
        foreach ($existingCourses as $existingCourse) {
            $serialPart = substr($existingCourse->base_code, -3);
            if (is_numeric($serialPart)) {
                $num = intval($serialPart);
                if ($num > $maxIndex) {
                    $maxIndex = $num;
                }
            }
        }
        $nextSerial = $maxIndex + 1;
        $serial = str_pad($nextSerial, 3, '0', STR_PAD_LEFT);
        $newCode = "{$categoryCode}{$projectKey}{$serial}";

        return $newCode;
    }
}
