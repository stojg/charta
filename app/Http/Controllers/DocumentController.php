<?php namespace App\Http\Controllers;

use App\Document;
use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class DocumentController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('auth');
        $this->user = Auth::User();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('documents.index', ['documents' => Document::byUser($this->user)->latest()->get()]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getTrashed()
    {
        return view('documents.trashed',
            ['documents' => Document::byUser($this->user)->latest()->onlyTrashed()->get()]);
    }

    /**
     * @param int $id
     * @param DocumentRepository $repo
     * @return mixed
     */
    public function getRestore($id)
    {
        $doc = Document::byUser($this->user)->withTrashed()->where('id', $id);
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
     * @return Response
     */
    public function getShow($id)
    {

        $doc = Document::byUser($this->user)->find($id);
        if (!$doc) {
            App::abort(404, 'Not found');
        }
        return view('documents.show', ['document' => $doc]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function getEdit($id)
    {
        $doc = Document::byUser($this->user)->find($id);
        if (!$doc) {
            App::abort(404, 'Not found');
        }
        return view('documents.edit', ['document' => $doc]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getDelete($id)
    {
        $doc = Document::byUser($this->user)->find($id);
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
     * @param Request $request
     * @return Response
     */
    public function postUpdate($id = null, Request $request)
    {

        $doc = Document::byUser($this->user)->findOrNew($id);
        $doc->creator = $this->user->email;
        $doc->content = Input::get('content');
        $doc->save();
        if ($request->ajax()) {
            return json_encode(['status' => 'ok']);
        }
        return Redirect::action('DocumentController@getEdit', $doc->id)->with('message', 'Saved.')->withInput();
    }

    public function postCreate()
    {

        $doc = Document::create([
            'content' => Input::get('content'),
            'creator' => $this->user->email
        ]);

        return Redirect::action('DocumentController@getEdit', $doc->id)->with('message', 'Saved.')->withInput();
    }

}
