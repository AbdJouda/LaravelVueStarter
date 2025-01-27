<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletionLog extends Model
{
    use HasFactory, HasUuids;

   /**
    * The attributes that aren't mass assignable.
    *
    * @var array<string>|bool
    */
    protected $guarded = ['id'];
}
