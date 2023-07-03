<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stores extends Model
{
    use HasFactory;

    // 1 toko bisa menjual produk-produk nya kebanyak wilayah
    public function regions()
    {
        // argument pertama yaitu model terkait
        // argument kedua, nama table pivot
        // argument ketiga foreign key pivot atau sesuai nama table
        // argument keempat kunci pivot terkait
        // kembalikkan classStore->milikBanyakOrang()
        return $this->belongsToMany(Regions::class, 'regions_stores', 'stores_id', 'regions_id');
    }
}
