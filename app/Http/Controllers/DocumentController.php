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
		return view('documents.index', ['documents' => $docs->latest()]);
	}

	/**
	 * @param DocumentRepository $repo
	 * @return \Illuminate\View\View
	 */
	public function getTrashed(DocumentRepository $repo) {
		return view('documents.trashed', ['documents' => $repo->trashed()]);
	}

	/**
	 * @param int $id
	 * @param DocumentRepository $repo
	 * @return mixed
	 */
	public function getRestore($id, DocumentRepository $repo) {
		$doc = $repo->find($id, true);
		if (!$doc) {
			App::abort(404, 'Not found');
		}
		$doc->restore();
		return Redirect::action('DocumentController@getTrashed');
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
	 * @param int $id
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
	 * @param Request $request
	 * @return Response
	 */
	public function postUpdate($id = null, DocumentRepository $repo, Request $request)
	{
		$doc = Document::findOrNew($id);
		$doc->content = Input::get('content');
		$doc->save();
		if ($request->ajax())
		{
			return json_encode(['status' => 'ok']);
		}
		return Redirect::action('DocumentController@getEdit', $doc->id)->with('message', 'Saved.')->withInput();
	}

	public function postCreate(DocumentRepository $repo)
	{
		$doc = Document::create([]);
		$doc->content = Input::get('content');
		$doc->save();

		return Redirect::action('DocumentController@getEdit', $doc->id)->with('message', 'Saved.')->withInput();
	}

}
