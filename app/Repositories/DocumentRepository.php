<?php namespace App\Repositories;

use App\Document;
use App\User;

class DocumentRepository {

	public function latest($limit = 10) {
		return Document::latest()->get();
	}

	public function find($id, $trashed=false) {
		if($trashed) {
			return Document::withTrashed()->where('id', $id);
		}
		return $docs = Document::find($id);
	}

	public function trashed() {
		return Document::latest()->onlyTrashed()->get();
	}
}