<?php
// app/Models/Book.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'series_id', 'volume_number', 'title', 'subtitle', 'publication_year',
        'editor', 'language', 'description', 'pdf_url', 'cover_image',
        'total_pages', 'isbn', 'status', 'published_at'
    ];
    
    protected $casts = [
        'published_at' => 'datetime',
        'publication_year' => 'integer',
    ];
    
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }
    
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }
    
    public function getCitationCountAttribute()
    {
        return $this->inscriptions->sum(function($inscription) {
            return $inscription->citationsAsSource()->count();
        });
    }
}
