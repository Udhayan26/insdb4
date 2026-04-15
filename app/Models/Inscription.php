<?php
// app/Models/Inscription.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Inscription extends Model
{
    protected $fillable = [
        'inscription_number', 'book_id', 'title', 'original_text',
        'translation', 'description', 'dynasty', 'king',
        'date_era', 'language', 'script'
    ];
    
    protected $casts = [
        'date_era' => 'date',
    ];
    
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
    
    public function location()
    {
        return $this->hasOne(Location::class);
    }
    
    public function citationsAsSource(): HasMany
    {
        return $this->hasMany(Citation::class, 'source_inscription_id');
    }
    
    public function citationsAsTarget(): HasMany
    {
        return $this->hasMany(Citation::class, 'target_inscription_id');
    }
    
    public function citedInscriptions()
    {
        return $this->belongsToMany(Inscription::class, 'citations', 'source_inscription_id', 'target_inscription_id')
                    ->withPivot('citation_type', 'citation_text', 'page_number');
    }
}
