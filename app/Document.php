<?php namespace App;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model {

	use SoftDeletes;

	protected $dates = ['deleted_at'];

	public function scopeLatest($query) {
		return $query->orderBy('updated_at', 'desc');
	}

	public function asHTML() {
		return Markdown::convertToHtml($this->content);
	}

	public function getTitle() {
		if(!empty($this->title)) {
			return $this->title;
		}
		$content = trim($this->content);
		preg_match('/^#\s(.*)/', $content, $matches);

		if(count($matches) && isset($matches[1])) {
			return trim($matches[1]);
		}

		return 'No name';
	}

}
