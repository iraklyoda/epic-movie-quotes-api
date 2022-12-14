<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyCreatedEmail extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public $id;

	public $url;

	public $email_body;

	public function __construct($id, $email_body)
	{
		$this->id = $id;
		$this->url = URL::temporarySignedRoute(
			'email.verification',
			now()->addDays(1),
			['user' => jwtUser()->id, 'id' => $id],
		);
		$this->email_body = $email_body;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param mixed $notifiable
	 *
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param mixed $notifiable
	 *
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		$url = url($this->url);
		$email_body = $this->email_body;

		return (new MailMessage)
			->view('verifyCreatedEmail', ['url' => $url, 'email' => $email_body])
			->from('moviequotes@redberry.ge', 'Movie Quotes')
			->action('Notification Action', $url)
			->line('Thank you for using our application!')
			->subject('Verify Email Address');
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param mixed $notifiable
	 *
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return [
		];
	}
}
