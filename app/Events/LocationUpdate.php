<?php
/***
 * Author: Arslan Arshad
 */


namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LocationUpdate implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;


	public $serialNumber;
	public $lat;
	public $long;
	public $nearestStop;
	public $currentRoute;
	public $orignalRoute;
	public $nearestPoint;
	public $currentPoint;
	public $pastPoint;
	public $predictedPoint;
	public $message;

	/***
	 * LocationUpdate constructor.
	 * @param $serialNumber
	 */
	public function __construct($serialNumber, $lat=null,$long=null,$nearestStop=null,$currentRoute=null,$orignalRoute=null,$nearestPoint=null,$message=null)
	{
		$this->serialNumber = $serialNumber;
		$this->lat = $lat;
		$this->long = $long;
		$this->nearestStop = $nearestStop;
		$this->currentRoute = $currentRoute;
		$this->orignalRoute = $orignalRoute;
		$this->nearestPoint = $nearestPoint;
		$this->message = $message;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return Channel
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('location.'.$this->serialNumber);
	}
}