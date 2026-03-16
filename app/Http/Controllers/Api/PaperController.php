<?php

namespace App\Http\Controllers\Api;

use App\Models\Paper;
use Illuminate\Http\Request;

class PaperController
{
    public function store(Request $request){
        $request->validate([
            'title'=>'required|string|abstract|max:225',
            'abstract'=>'required|string',
            'file'=>'required|file|mimes:pdf,doc,docx|max:10204'
        ]);

        $path = $request->file('file')->store('papers','public');

        $paper = Paper::create([
            'user_id' => $request->user()->id,
            'title'=> $request->title,
            'abstract'=> $request->abstract,
            'file_path' => $path,
        ]);

        if($paper){
            return response()->json([
                'message' =>'Papper submitted successfully',
                'paper' => $paper,
            ],201);
        }
    }

    public function myPapers(Request $request){
        $papers = $request->user()->papers;

        return response()->json($papers);
    }

    public function destroy($id){
        $paper = Paper::findOrFail($id);

        $paper->delete();

        return response()->json([
            'message' =>'Papper deleted',
        ]);
    }

    // for reviewer 
    public function submittedPapers($id){
        $papers = Paper::where('status','submitted')->get();
        return response()->json($papers);
    }

    public function acceptedPapers($id){
        $papers = Paper::findOrFail($id);

        $papers->status = 'accepted';
        $papers->save();
        return response()->json([
            'message'=>'Paper accepted',
            'paper' => $papers,
        ]);
    }

    public function rejectedPapers($id){
        $papers = Paper::findOrFail($id);

        $papers->status = 'rejected';
        $papers->save();
        return response()->json([
            'message'=>'Paper rejected',
            'paper' => $papers,
        ]);
    }

    // for editor
    public function publishPaper($id)
    {
    $paper = Paper::findOrFail($id);

    if ($paper->status !== 'accepted') {
            return response()->json([
                'message' => 'Only accepted papers can be published'
            ], 400);
        }

        $paper->status = 'published';
        $paper->save();

        return response()->json([
            'message' => 'Paper published successfully',
            'paper' => $paper
        ]);
    }

    public function viewAcceptedPapers()
    {
        $papers = Paper::where('status', 'accepted')->get();

        return response()->json($papers);
    }
}
