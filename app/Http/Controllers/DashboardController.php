<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Series;
use App\Models\Book;
use App\Models\Inscription;
use App\Models\Citation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics for dashboard
        $stats = [
            'total_series' => Series::count(),
            'total_books' => Book::count(),
            'published_books' => Book::where('status', 'published')->count(),
            'total_inscriptions' => Inscription::count(),
            'total_citations' => Citation::count(),
            'total_locations' => \App\Models\Location::count(),
        ];
        
        // Recent additions
        $recent_books = Book::with('series')->latest()->take(5)->get();
        $recent_inscriptions = Inscription::with('book.series')->latest()->take(5)->get();
        
        // Series distribution
        $series_distribution = Series::withCount('books')->get();
        
        // Citation network stats
        $citation_stats = DB::table('citations')
            ->select('citation_type', DB::raw('count(*) as count'))
            ->groupBy('citation_type')
            ->get();
        
        return view('dashboard.index', compact('stats', 'recent_books', 'recent_inscriptions', 'series_distribution', 'citation_stats'));
    }
    
    public function getChartData()
    {
        $books_by_year = Book::select(DB::raw('YEAR(created_at) as year'), DB::raw('count(*) as count'))
            ->groupBy('year')
            ->orderBy('year')
            ->get();
            
        $inscriptions_by_dynasty = Inscription::select('dynasty', DB::raw('count(*) as count'))
            ->whereNotNull('dynasty')
            ->groupBy('dynasty')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json([
            'books_by_year' => $books_by_year,
            'inscriptions_by_dynasty' => $inscriptions_by_dynasty,
        ]);
    }
}
