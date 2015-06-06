<?php namespace App\Repositories;

use App\Document;
use App\User;

class DocumentRepository {

	public function latest($limit = 10) {
		return Document::limit(10)->latest()->get();
	}

	public function find($id) {
		return Document::find($id);
	}
}