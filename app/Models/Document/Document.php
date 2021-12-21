<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'user_id', 'name', 'type', 'description', 'id_number', 'issue_date',  'issue_body', 'country_issuing'
    ];

    public $timestamps = true;
}
