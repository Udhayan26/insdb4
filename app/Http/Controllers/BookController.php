<?php
// app/Http/Controllers/BookController.php
namespace App\Http\Controllers;

use App\Models\Series;
use App\Models\Book;
use App\Imports\BooksImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('series');
        
        // Filter by series
        if ($request->has('series') && $request->series) {
            $query->where('series_id', $request->series);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('volume_number', 'like', '%' . $request->search . '%')
                  ->orWhere('editor', 'like', '%' . $request->search . '%');
            });
        }
        
        $books = $query->orderBy('created_at', 'desc')->paginate(20);
        $series = Series::all();
        
        if ($request->ajax()) {
            return view('books.partials.table', compact('books'))->render();
        }
        
        return view('books.index', compact('books', 'series'));
    }
    
    public function create()
    {
        $series = Series::where('is_active', true)->get();
        return view('books.form', compact('series'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'series_id' => 'required|exists:series,id',
            'volume_number' => 'required|string|max:20',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'editor' => 'nullable|string',
            'language' => 'required|string',
            'description' => 'nullable|string',
            'total_pages' => 'nullable|integer',
            'isbn' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
        ]);
        
        $book = Book::create($validated);
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Book created successfully', 'book' => $book]);
        }
        
        return redirect()->route('books.index')->with('success', 'Book created successfully');
    }
    
    public function edit(Book $book)
    {
        $series = Series::where('is_active', true)->get();
        return view('books.form', compact('book', 'series'));
    }
    
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'series_id' => 'required|exists:series,id',
            'volume_number' => 'required|string|max:20',
            'title' => 'required|string|max:255',
            'status' => 'required|in:draft,published,archived',
        ]);
        
        $book->update($validated);
        
        return redirect()->route('books.index')->with('success', 'Book updated successfully');
    }
    
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully');
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv'
        ]);
        
        Excel::import(new BooksImport, $request->file('excel_file'));
        
        return redirect()->route('books.index')->with('success', 'Books imported successfully');
    }
    
    public function export()
    {
        return Excel::download(new BooksExport, 'books.xlsx');
    }
    
    public function publish(Book $book)
    {
        $book->update([
            'status' => 'published',
            'published_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }
    
    public function bulkPublish(Request $request)
    {
        Book::whereIn('id', $request->book_ids)->update([
            'status' => 'published',
            'published_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }
}
