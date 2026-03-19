<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Banco
 * 
 * @property int $banco_id
 * @property string|null $banco
 * @property string|null $descripcion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Banco extends Model
{
	protected $table = 'bancos';
	protected $primaryKey = 'banco_id';
	public $timestamps = false;

	protected $fillable = [
		'banco',
		'descripcion'
	];
	protected function casts(): array
    {
        return [
            'banco_id' => 'integer',
        ];
    }
	public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('banco', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Scope para ordenar alfabéticamente
     */
    public function scopeAlphabetical($query)
    {
        return $query->orderBy('banco', 'asc');
    }
}
