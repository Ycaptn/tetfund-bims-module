<?php


namespace TETFund\BIMSOnboarding\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use TETFund\BIMSOnboarding\Models\BIMSRecord;

class BIMSRecordDeletedNotification extends Notification
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
        return (new MailMessage)->subject('BIMSRecord deleted successfully')
                                ->markdown(
                                    'mail.bIMSRecords.deleted',
                                    ['bIMSRecord' => $this->bIMSRecord]
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
