<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebContent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(WebContent::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $webContent = WebContent::create($validatedData);

        return response()->json($webContent, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(WebContent $webContent)
    {
        return response()->json($webContent);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WebContent $webContent)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ]);

        $webContent->update($validatedData);

        return response()->json($webContent, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebContent $webContent)
    {
        $webContent->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
