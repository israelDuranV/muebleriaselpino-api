<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 * 
 * @property int $Id
 * @property string|null $item
 * @property string|null $icon
 * @property string|null $url_menu
 *
 * @package App\Models
 */
class Menu extends Model
{
	protected $table = 'menu';
	protected $primaryKey = 'Id';
	public $timestamps = false;

	protected $fillable = [
		'item',
		'icon',
		'url_menu'
	];
}
