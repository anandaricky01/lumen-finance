<?php

namespace App\Http\Controllers;
use App\Models\Note;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

use function PHPSTORM_META\map;

class NoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(){
        $note = Auth::user()->note;

        return response()->json([
            'message' => 'All Notes Data',
            'data' => $note,
            'user' => Auth::user()->name,
            'status' => true
        ]);
    }

    public function store(Request $request){
        $validated = $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'string|max:255',
            'count' => 'required|min:1|numeric',
            'type' => 'required|in:expense,income',
            'price' => 'required|numeric',
            'date' => 'required|date'
        ]);

        $validated['user_id'] = Auth::user()->id;

        // $validated = Validator::make($request->all(), [
        //     'title' => 'required|string|max:255',
        //     'description' => 'string|max:255',
        //     'count' => 'required|min:1|numeric',
        //     'type' => 'required|in:expense,income',
        //     'price' => 'required|numeric',
        //     'date' => 'required|date'
        // ]);

        // if($validated->fails()){
        //     return response()->json([
        //         'message' => $validated->errors()
        //     ]);
        // }

        // $request->{"user_id"} = Auth::user()->id;

        $data = Note::create($validated);
        // $data = Note::create($request->all());

        $response = [
            'message' => 'Data created successfully',
            'data' => $data,
            'status' => true
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function update(Request $request, $id){
        try {
            $validated = $this->validate($request,[
                'title' => 'required|string|max:255',
                'description' => 'string|max:255',
                'count' => 'required|min:1|numeric',
                'type' => 'required|in:expense,income',
                'price' => 'required|numeric',
                'date' => 'required|date'
            ]);

            $note = Note::where('id', $id);
            $note->update($validated);

            return response()->json([
                'message' => 'Data has been update!',
                'data id' => $id,
                'status' => true
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo
            ]);
        }

    }

    public function delete($id){
        try{
            Note::where('id', $id)->delete();

            return response()->json([
                'message' => 'Data has been deleted!'
            ]);
            
        } catch(QueryException $e){
            return response()->json([
                'message' => $e->errorInfo
            ]);
        }
    }
    //
}
