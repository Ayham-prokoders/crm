<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SyncedFormsExport implements FromCollection, WithHeadings
{
    protected $forms;

    public function __construct($forms)
    {
        $this->forms = $forms;
    }

    public function collection()
    {
        return collect(array_map(function($form){
            $data = $form['data'] ?? [];

            return [
                $form['type'] ?? '',
                // User Name
                $data['full_name'] ?? ($data['First_Name'] ?? '') . ' ' . ($data['Last_Name'] ?? ''),
                $form['created_at'] ?? '',
                // Email
                $data['email'] ?? ($data['Email'] ?? ''),
                // Register Type
                $data['type'] ?? '',
                // Course Name
                $data['course_name'] ?? '',
                // City Course
                $data['course_city'] ?? '',
            
                
            ];
        }, $this->forms));
    }

    public function headings(): array
    {
        return [
            'Request Type', 'User Name', 'Date', 'Email',
            'Register Type', 'Course Name', 'City Course'
        ];
    }
}
