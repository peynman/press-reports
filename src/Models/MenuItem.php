<?php

namespace Larapress\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Larapress\Core\Extend\SelectorObjects;

/**
 * @property int            $id
 * @property string         $name
 * @property string         $title
 * @property string         $type
 * @property string         $key
 * @property string         $icon
 * @property string         $url
 *
 * @property MenuItem       $parent
 * @property MenuItem[]     $children
 */
class MenuItem extends Model
{
    use SelectorObjects;

    protected $table = 'menu_items';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'title',
        'key',
        'icon',
        'url',
        'parent_id',
        'name',
    ];

    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id', 'id');
    }

    public function getUrl()
    {
        if (Str::startsWith($this->url, '#ROUTE')) {
            preg_match('#\((.*?)\)#', $this->url, $match);
            if (count($match) === 2) {
                $params = explode(",", $match[1]);
                if (count($params) > 0) {
                    return route($params[0], $params);
                }
            }
        }

        return $this->url;
    }
}
