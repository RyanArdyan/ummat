<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Region extends Model
{
    use HasFactory;

    // Di suatu wilayah, terdapat banyak toko
    // argument pertama yaitu model terkait
    // argument kedua, nama table pivot
    // argument ketiga foreign key pivot atau sesuai nama table
    // argument keempat kunci pivot terkait
    // kembalikkan classStore->milikBanyakOrang()
    public function stores() {
        return $this->belongsToMany(Stores::class, 'regions_stores', 'regions_id', 'stores_id');
        // https://www.iankumu.com/blog/laravel-many-to-many-relationship/
    }
}
