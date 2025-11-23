<?php
namespace App\Mail;

use Storage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Modules\Lms\Models\{Classe};
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\Mime\Header\UnstructuredHeader;

class CourseAnnouncements extends Mailable
{
    use Queueable, SerializesModels;

    public $course;
    public $trainer;
    public $url;
    public $class;
    public $image;
    public $files;

    public $sent_by;
    public $customBody;
    public $subject;

    public function __construct($course, Classe $class, User $trainer, $image=null,$files , $sent_by, $customBody = null, $subject = null)
    {
        $this->course = $course;
        $this->class = $class;
        $this->trainer = $trainer;
        $this->image = $image;
        $this->files = $files;
        $this->sent_by = $sent_by;
        $this->customBody = $customBody;
        $this->subject = $subject;

        // $this->url=env('SPACE_URL');
        $this->url = env('PROJECT_FRONTEND_LMS');
    }

    public function build()
    {
        // return $this->subject('Course Announcement')
        //             ->view('email.course_announcement')
        //             ->with([
        //                 'course' => $this->course,
        //                 'trainer' => $this->trainer,
        //                 'class' => $this->class,
        //                 'url' => $this->url
        //             ]);
        $email = $this->subject($this->subject)
            ->view('dealmanagement::emails.invoice')
            ->with([
                'course' => $this->course,
                'trainer' => $this->trainer,
                'class' => $this->class,
                'url' => $this->url,
                'image' => $this->image,
                'customBody' => $this->customBody,

            ])->withSymfonyMessage(function ($message) {
                // Attach the mailable class name to the headers
                $message->getHeaders()->add(new UnstructuredHeader('X-Mailable-Class', static::class));
                $message->getHeaders()->addTextHeader('X-User-ID', $this->sent_by);
            });

        // If image exists and is a file path, attach it
        if ($this->image) {
            $imagePath = public_path($this->image);

            if (file_exists($imagePath)) {
                $email->attach($imagePath, [
                    'as' => basename($this->image),
                    'mime' => mime_content_type($imagePath),
                ]);
            }
        }
        if (!empty($this->files)) {
            foreach ($this->files as $filePath) {
                $fullPath = public_path($filePath);

                if (file_exists($fullPath)) {
                    $email->attach($fullPath, [
                        'as' => basename($filePath),
                        'mime' => mime_content_type($fullPath),
                    ]);
                }
            }
        }

        return $email;
    }
}
