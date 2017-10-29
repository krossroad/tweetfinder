<?php 

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class History extends Model
{
    protected $table = 'search_history';

    protected $guarded = ['_id'];
}
