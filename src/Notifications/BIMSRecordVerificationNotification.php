<?php


namespace TETFund\BIMSOnboarding\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

use TETFund\BIMSOnboarding\Models\BIMSRecord;

class BIMSRecordVerificationNotification extends Notification implements ShouldQueue
{

    use Queueable;

    public $bIMSRecord;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(BIMSRecord $bIMSRecord)
    {
        $this->bIMSRecord = $bIMSRecord;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
       
        $url = URL::signedRoute('bims-onboarding.BIMSRecords.verify', ['BIMSRecords' => $this->bIMSRecord->id]);
        
        return (new MailMessage)->subject('BIMSRecord Verification')
                                ->markdown(
                                    'tetfund-bims-module::mail.b_i_m_s_records.verification',
                                    ['bIMSRecord' => $this->bIMSRecord, 'url' => $url]
                                );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }

}
