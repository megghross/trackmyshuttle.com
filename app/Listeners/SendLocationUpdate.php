<?php

namespace App\Listeners;

use App\Events\LocationUpdate;

class SendLocationUpdate
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/***
	 * @param LocationUpdate $locationUpdate
	 */
	public function handle(LocationUpdate $locationUpdate)
	{
		// Access the order using $event->order...
	}
}