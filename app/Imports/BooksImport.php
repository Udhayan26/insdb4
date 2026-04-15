<?php
// app/Imports/BooksImport.php
namespace App\Imports;

use App\Models\Book;
use App\Models\Series;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BooksImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $series = Series::where('code', $row['series_code'])->first();
        
        if (!$series) {
            return null;
        }
        
        return new Book([
            'series_id' => $series->id,
            'volume_number' => $row['volume_number'],
            'title' => $row['title'],
            'subtitle' => $row['subtitle'] ?? null,
            'publication_year' => $row['publication_year'] ?? null,
            'editor' => $row['editor'] ?? null,
            'language' => $row['language'] ?? 'Sanskrit',
            'description' => $row['description'] ?? null,
            'total_pages' => $row['total_pages'] ?? null,
            'isbn' => $row['isbn'] ?? null,
            'status' => $row['status'] ?? 'draft',
        ]);
    }
    
    public function rules(): array
    {
        return [
            'series_code' => 'required|string',
            'volume_number' => 'required|string',
            'title' => 'required|string',
        ];
    }
}
