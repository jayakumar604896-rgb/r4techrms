<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\Models\News;
use Illuminate\Support\Facades\Storage; // ← add this at the top


class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderByDesc('news_date')->get();
        return view('news.index', compact('news'));
    }


    public function create()
    {
        return view('news.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'sub_title'  => 'nullable|string|max:255',
            'news_date'  => 'required|date',
            'is_active'  => 'nullable|boolean',          // ← added validation
            'image'      => 'nullable', // ← added max size
        ]);

        $imagePath = ''; // default empty

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Ensure the directory exists
            $destinationPath = public_path('uploads/news_images');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Create a unique filename
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);

            $imagePath = 'uploads/news_images/' . $filename;
        }

        News::create([
            'title'      => $request->title,
            'sub_title'  => $request->sub_title,
            'news_date'  => $request->news_date,
            'created_by' => Auth::id(),
            'is_active'  => $request->boolean('is_active'), // returns true/false, Eloquent will cast to 1/0
            'image'      => $imagePath,
        ]);

        return redirect()->route('news.index')->with('success', 'News added successfully');
    }


    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('news.edit', compact('news'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'sub_title'  => 'nullable|string|max:255',
            'news_date'  => 'required|date',
            'is_active'  => 'nullable|boolean',
            'image'      => 'nullable',
        ]);

        $news = News::findOrFail($id);

        // Prepare data (exclude image, we'll handle separately)
        $data = $request->only('title', 'sub_title', 'news_date');
        $data['is_active'] = $request->boolean('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($news->image && file_exists(public_path($news->image))) {
                unlink(public_path($news->image));
            }

            // Ensure directory exists
            $destinationPath = public_path('uploads/news_images');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Store new image
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);

            $data['image'] = 'uploads/news_images/' . $filename;
        }

        $news->update($data);

        return redirect()->route('news.index')->with('success', 'News updated successfully');
    }


    public function destroy($id)
    {
        News::findOrFail($id)->delete();
        return redirect()->route('news.index')->with('success', 'News deleted successfully');
    }
}