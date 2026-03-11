<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewReportSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $report;
    public $reportUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($report, $reportUrl)
    {
        $this->report = $report;
        $this->reportUrl = $reportUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
       
        
        return $this->subject('🔔 New Report Submitted - Action Required')
            ->view('emails.new-report-submitted')
            ->with([
                'report' => $this->report,
                'reportUrl' => $this->reportUrl,
            ]);
    }
}
