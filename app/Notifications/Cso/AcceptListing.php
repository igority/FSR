<?php

namespace FSR\Notifications\Cso;

use FSR\ListingOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AcceptListing extends Notification
{
    use Queueable;

    protected $listing_offer;

    /**
     * Create a new notification instance.
     * @param ListingOffer $listing_offer
     * @return void
     */
    public function __construct(ListingOffer $listing_offer)
    {
        $this->listing_offer = $listing_offer;
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
        return (new MailMessage)
                    ->subject('Прифатена донација')
                    ->line('Вашата донација е прифатена од ' . $this->listing_offer->cso->first_name . ' ' . $this->listing_offer->cso->last_name . ' | ' . $this->listing_offer->cso->organization->name . '.')
                    ->line('Прифатена количина: ' . (($this->listing_offer->quantity == $this->listing_offer->listing->quantity) ? 'целосна.' : $this->listing_offer->quantity . ' од ' . $this->listing_offer->listing->quantity . ' ' . $this->listing_offer->listing->quantity_type->description))
                    ->line('Лице за подигнување: ' . $this->listing_offer->volunteer_pickup_name)
                    ->line('Контакт: ' . $this->listing_offer->volunteer_pickup_phone)
                    ->action('Повеќе детали', url('/donor/my_accepted_listings/' . $this->listing_offer->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
