<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReportSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $reportUrl = route('admin.reports.show', $this->report->id);
        
        return (new MailMessage)
            ->subject('🔔 New Report Submitted - Action Required')
            ->greeting('Hello Admin,')
            ->line('A new report has been submitted to the Report Management System and requires your immediate attention.')
            ->line('')
            ->line('**Report Details:**')
            ->line('👤 **Submitted By:** ' . ($this->report->individual_name ?? 'Not provided'))
            ->line('📍 **Location:** ' . ($this->report->location ?? 'Not provided'))
            ->line('📅 **Submitted On:** ' . $this->report->created_at->format('F d, Y \a\t h:i A'))
            ->line('📝 **Description:** ' . \Str::limit($this->report->narrative ?? 'No description', 100))
            ->line('')
            ->action('Review Report Now', $reportUrl)
            ->line('')
            ->line('Please review this report and take appropriate action (Approve/Reject).')
            ->line('')
            ->line('Thank you for maintaining the quality of our reporting system.')
            ->salutation('Best regards,  
Report Management System');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'individual_name' => $this->report->individual_name,
            'location' => $this->report->location,
            'submitted_at' => $this->report->created_at,
        ];
    }
}
