<?php
namespace App\Services;

use Modules\Lms\Models\ExternalCourse;
use Illuminate\Support\Str;

class GenerateExternalCourseCodeService
{
    public function generate(ExternalCourse $course)
    {
        $category = $course->category;

        if (!$category || !$category->code) {
            throw new \Exception("Missing category or category code.");
        }

        $projectKey = 'C1';
        $categoryCode = strtoupper(substr($category->code, 0, 3));

        $existingCourses = ExternalCourse::where('category_id', $category->id)
            // ->where('project_source', $projectKey)
            ->whereNotNull('base_code')
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
