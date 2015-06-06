<?php namespace App;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;

class Document extends Model {

	public function scopeLatest($query) {
		return $query->orderBy('updated_at', 'desc');
	}

	public function asHTML() {
		return Markdown::convertToHtml($this->content);
	}

	/**
	 *
	 */
	public function ago()
	{

	}

}
