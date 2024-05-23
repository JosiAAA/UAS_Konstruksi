<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $table = 'follow_tabel';

    protected $fillable = [
        'current_user_id',
        'targeted_user_id',
    ];
}
