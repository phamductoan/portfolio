<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vocabulary;
use Illuminate\Support\Facades\Response;

class VocabularyController extends Controller
{
    /**
     * Hiển thị biểu mẫu để thêm từ vựng mới.
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        return view('home');
    }

    /**
     * Xử lý việc thêm từ vựng mới vào cơ sở dữ liệu.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addVocabulary(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'japanese_text' => 'required|string|max:255',
            'kanji' => 'nullable|string|max:255',
            'romaji' => 'required|string|max:255',
            'significance' => 'required|string|max:255',
            'unit' => 'required|integer',
        ]);

        // Tạo đối tượng từ vựng mới và lưu vào cơ sở dữ liệu
        Vocabulary::create([
            'japanese_text' => $request->input('japanese_text'),
            'kanji' => $request->input('kanji') ?: null,
            'romaji' => $request->input('romaji'),
            'significance' => $request->input('significance'),
            'unit' => $request->input('unit'),
        ]);

        // Chuyển hướng đến danh sách từ vựng với thông báo thành công
        return redirect()->route('vocabulary.index')->with('success', 'Vocabulary added successfully!');
    }

    /**
     * Hiển thị danh sách từ vựng.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Vocabulary::query();
    
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
        // Lấy tất cả các unit duy nhất từ bảng vocabulary
        $units = Vocabulary::distinct()->pluck('unit');

        return response()->json($units);
    }

    public function reading(Request $request)
    {
        $units = $request->query('units'); // Lấy units từ query string

        if ($units) {
            $vocabularies = Vocabulary::whereIn('unit', explode(',', $units))->get();
        } else {
            $vocabularies = Vocabulary::all();
        }

        return view('reading', ['vocabularies' => $vocabularies]);
    }

    // Xem chi tiết từ vựng
    public function show($id)
    {
        $vocabulary = Vocabulary::findOrFail($id);
        return view('show', ['vocabulary' => $vocabulary]);
    }

      // Hiển thị biểu mẫu chỉnh sửa từ vựng
    public function edit($id)
    {
        $vocabulary = Vocabulary::findOrFail($id);
        return view('edit', ['vocabulary' => $vocabulary]);
    }

    // Cập nhật từ vựng
    public function update(Request $request, $id)
    {
        $request->validate([
            'japanese_text' => 'required|string|max:255',
            'romaji' => 'required|string|max:255',
            'significance' => 'required|string|max:255',
            'unit' => 'required|integer',
            'kanji' => 'nullable|string|max:255'
        ]);

        $vocabulary = Vocabulary::findOrFail($id);
        $vocabulary->update($request->all());

        return redirect()->route('vocabulary.index')->with('success', 'Vocabulary updated successfully!');
    }

    // Xóa từ vựng
    public function destroy($id)
    {
        $vocabulary = Vocabulary::findOrFail($id);
        $vocabulary->delete();

        return redirect()->route('vocabulary.index')->with('success', 'Vocabulary deleted successfully!');
    }

    public function export()
    {
        $vocabularies = Vocabulary::all();

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

        // Thêm BOM vào file để nhận diện UTF-8
        fputs($handle, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        fputcsv($handle, $csvHeader);

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return Response::download($filename)->deleteFileAfterSend(true);
    }
}
