<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationObject extends Model
{
    use HasFactory;
    protected $fillable = [
        'evaluation_id',
        'object_id',
        'order_of',
    ];
    public $timestamps = false;
    public function object()
    {
        return $this->belongsTo(ObjectModel::class);
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
