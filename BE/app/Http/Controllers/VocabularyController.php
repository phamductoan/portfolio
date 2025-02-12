<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vocabulary;
use App\Http\Middleware\SecurityMiddleware;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class VocabularyController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');  // Thêm middleware auth
    }

    public function showForm()
    {
        return view('home');
    }

    public function addVocabulary(Request $request)
    {
        $request->validate([
            'japanese_text' => 'required|string|max:255',
            'kanji' => 'nullable|string|max:255',
            'romaji' => 'required|string|max:255',
            'significance' => 'required|string|max:255',
            'unit' => 'required|integer',
        ]);

        // Thêm user_id khi tạo vocabulary mới
        Vocabulary::create([
            'japanese_text' => $request->input('japanese_text'),
            'kanji' => $request->input('kanji') ?: null,
            'romaji' => $request->input('romaji'),
            'significance' => $request->input('significance'),
            'unit' => $request->input('unit'),
            'user_id' => Auth::id()  // Thêm user_id của user đang đăng nhập
        ]);

        return redirect()->route('vocabulary.index')->with('success', 'Vocabulary added successfully!');
    }

    public function index(Request $request)
    {
        $query = Vocabulary::where('user_id', Auth::id());  // Chỉ lấy vocabulary của user hiện tại
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('japanese_text', 'like', "%{$search}%")
                  ->orWhere('kanji', 'like', "%{$search}%")
                  ->orWhere('romaji', 'like', "%{$search}%")
                  ->orWhere('significance', 'like', "%{$search}%")
                  ->orWhere('unit', 'like', "%{$search}%");
            });
        }
    
        $vocabularies = $query->paginate(50);
        return view('index', ['vocabularies' => $vocabularies]);
    }

    public function getUnits()
    {
        // Chỉ lấy units của user hiện tại
        $units = Vocabulary::where('user_id', Auth::id())
                          ->distinct()
                          ->pluck('unit');
        return response()->json($units);
    }

    public function reading(Request $request)
    {
        $query = Vocabulary::where('user_id', Auth::id());  // Chỉ lấy vocabulary của user hiện tại
        
        if ($request->has('units')) {
            $units = explode(',', $request->query('units'));
            $query->whereIn('unit', $units);
        }

        $vocabularies = $query->get();
        return view('reading', ['vocabularies' => $vocabularies]);
    }

    public function show($id)
    {
        $vocabulary = Vocabulary::where('user_id', Auth::id())  // Chỉ cho phép xem vocabulary của user hiện tại
                               ->findOrFail($id);
        return view('show', ['vocabulary' => $vocabulary]);
    }

    public function edit($id)
    {
        $vocabulary = Vocabulary::where('user_id', Auth::id())  // Chỉ cho phép edit vocabulary của user hiện tại
                               ->findOrFail($id);
        return view('edit', ['vocabulary' => $vocabulary]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'japanese_text' => 'required|string|max:255',
            'romaji' => 'required|string|max:255',
            'significance' => 'required|string|max:255',
            'unit' => 'required|integer',
            'kanji' => 'nullable|string|max:255'
        ]);

        $vocabulary = Vocabulary::where('user_id', Auth::id())  // Chỉ cho phép update vocabulary của user hiện tại
                               ->findOrFail($id);
        $vocabulary->update($request->all());

        return redirect()->route('vocabulary.index')->with('success', 'Vocabulary updated successfully!');
    }

    public function destroy($id)
    {
        $vocabulary = Vocabulary::where('user_id', Auth::id())  // Chỉ cho phép xóa vocabulary của user hiện tại
                               ->findOrFail($id);
        $vocabulary->delete();

        return redirect()->route('vocabulary.index')->with('success', 'Vocabulary deleted successfully!');
    }

    public function export()
    {
        $vocabularies = Vocabulary::where('user_id', Auth::id())  // Chỉ export vocabulary của user hiện tại
                                ->get();

        $csvHeader = ['ID', 'Japanese Text', 'Kanji', 'Romaji', 'Significance', 'Unit'];
        $csvData = [];

        foreach ($vocabularies as $vocabulary) {
            $csvData[] = [
                $vocabulary->id,
                $vocabulary->japanese_text,
                $vocabulary->kanji,
                $vocabulary->romaji,
                $vocabulary->significance,
                $vocabulary->unit,
            ];
        }

        $filename = "vocabularies_" . date('Y-m-d_H-i-s') . ".csv";
        $handle = fopen($filename, 'w+');
        fputs($handle, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        fputcsv($handle, $csvHeader);

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return Response::download($filename)->deleteFileAfterSend(true);
    }
}