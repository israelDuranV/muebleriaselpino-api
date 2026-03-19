<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ForgotPassword
 * 
 * @property int $id
 * @property string|null $hash
 * @property Carbon|null $fecha
 *
 * @package App\Models
 */
class ForgotPassword extends Model
{
	protected $table = 'forgot_password';
	public $timestamps = false;

	protected $casts = [
		'fecha' => 'datetime'
	];

	protected $fillable = [
		'hash',
		'fecha'
	];
}
