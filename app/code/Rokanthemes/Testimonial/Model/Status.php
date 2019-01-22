<?php
/**
* Copyright © 2015 tokitheme.com. All rights reserved.

* @author Blue Sky Team <contact@tokitheme.com>
*/

namespace Rokanthemes\Testimonial\Model;

class Status {
	const STATUS_APPROVED = 1;
	const STATUS_NOTAPPROVED = 2;
	const STATUS_PENDING = 3;

	/**
	 * get available statuses
	 * @return []
	 */
	public static function getAvailableStatuses() {
		return [
			self::STATUS_APPROVED => __('Approved'), 
			self::STATUS_NOTAPPROVED => __('Not Approved'),
			self::STATUS_PENDING => __('Pending'),
		];
	}
}
