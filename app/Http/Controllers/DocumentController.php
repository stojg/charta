<?php namespace App\Http\Controllers;

use App\Document;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\DocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class DocumentController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @param DocumentRepository $docs
	 * @return Response
	 */
	public function index(DocumentRepository $docs)
	{
		return view('documents.index', ['documents' => $docs->latest(10)]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getNew()
	{
		return view('documents.edit', ['document' => new Document()]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @param DocumentRepository $repo
	 * @return Response
	 */
	public function getShow($id, DocumentRepository $repo)
	{

		$doc = $repo->find($id);
		if (!$doc) {
			App::abort(404, 'Not found');
		}
		return view('documents.show', ['document' => $doc]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @param DocumentRepository $repo
	 * @return Response
	 */
	public function getEdit($id, DocumentRepository $repo)
	{
		$doc = $repo->find($id);
		if (!$doc) {
			App::abort(404, 'Not found');
		}
		return view('documents.edit', ['document' => $doc]);
	}

	/**
	 * @param int $id
	 * @param DocumentRepository $repo
	 * @return mixed
	 */
	public function getDelete($id, DocumentRepository $repo)
	{
		$doc = $repo->find($id);
		if (!$doc) {
			App::abort(404, 'Not found');
		}
		$doc->delete();
		return Redirect::action('DocumentController@index');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 * @param DocumentRepository $repo
	 * @return Response
	 */
	public function postUpdate($id = null, DocumentRepository $repo)
	{
		$doc = Document::findOrNew($id);
		$doc->content = Input::get('content');
		$doc->title = Input::get('title');
		$doc->save();

		return Redirect::action('DocumentController@getEdit', $doc->id)->with('message', 'Saved.')->withInput();
	}

	public function postCreate(DocumentRepository $repo)
	{
		$doc = Document::create([]);
		$doc->content = Input::get('content');
		$doc->title = Input::get('title');
		$doc->save();

		return Redirect::action('DocumentController@getEdit', $doc->id)->with('message', 'Saved.')->withInput();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function getDestroy($id)
	{
		//
	}

}
