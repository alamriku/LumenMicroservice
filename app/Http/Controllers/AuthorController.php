<?php


namespace App\Http\Controllers;


use App\Models\Author;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class AuthorController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $authors = Author::all();
        return $this->successResponse($authors);
    }
    

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'gender' => 'required|in:male,female',
            'country' => 'required',
        ];

        $this->validate($request, $rules);

        $author = Author::create($request->all());

        return $this->successResponse($author, Response::HTTP_CREATED);
    }
    
    public function show($author)
    {
        $author = Author::findOrFail($author);
        return $this->successResponse($author);
    }

    public function update(Request $request, $author)
    {
        $rules = [
            'name' => 'max:255',
            'gender' => 'in:male,female',
            'country' => 'max:255',
        ];

        $this->validate($request, $rules);

        $author = Author::findOrFail($author);

        $author->fill($request->all());
        if($author->isClean()){
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $author->save();

        return $this->successResponse($author);
    }

    public function destroy($author)
    {
        $author = Author::findOrFail($author);
        $author->delete();

        return $this->successResponse($author);
    }
}