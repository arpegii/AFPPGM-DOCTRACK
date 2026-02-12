<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentForwardHistory extends Model
{
    use HasFactory;

    protected $table = 'document_forward_history';

    protected $fillable = [
        'document_id',
        'from_unit_id',
        'to_unit_id',
        'forwarded_by_user_id',
        'notes',
    ];

    /**
     * Get the document this forward belongs to
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the unit this was forwarded from
     */
    public function fromUnit()
    {
        return $this->belongsTo(Unit::class, 'from_unit_id');
    }

    /**
     * Get the unit this was forwarded to
     */
    public function toUnit()
    {
        return $this->belongsTo(Unit::class, 'to_unit_id');
    }

    /**
     * Get the user who forwarded this document
     */
    public function forwardedBy()
    {
        return $this->belongsTo(User::class, 'forwarded_by_user_id');
    }
}