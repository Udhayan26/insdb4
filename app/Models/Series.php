<?php
// app/Models/Series.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Series extends Model
{
    protected $fillable = ['code', 'name', 'description', 'publisher', 'start_year', 'end_year', 'total_volumes', 'is_active'];
    
    protected $casts = [
        'is_active' => 'boolean',
        'start_year' => 'integer',
        'end_year' => 'integer',
    ];
    
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
    
    public function getPublishedBooksCountAttribute()
    {
        return $this->books()->where('status', 'published')->count();
    }
}
