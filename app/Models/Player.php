<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasUuids;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'manager';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'players';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'username',
        'nickname',
        'language',
        'tagid',
        'ip',
        'country',
        'version',
        'firstlogin',
        'lastlogin',
        'lastlogout',
        'online',
        'playtime',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'language' => 'integer',
        'tagid' => 'integer',

        'online' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'firstlogin',
        'lastlogin',
        'lastlogout',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function version(): Attribute
    {
        return Attribute::make(get: fn (int $value) => ProtocolVersion::tryFrom($value) ?? ProtocolVersion::SNAPSHOT);
    }

    public static function getName($uuid)
    {
        if ($uuid == null) {
            return null;
        }
        if ($uuid == 'f78a4d8d-d51b-4b39-98a3-230f2de0c670') {
            return 'CONSOLE';
        }
        $player = Player::select('username')
            ->where('uuid', $uuid)
            ->first();
        if ($player == null) {
            return $player;
        }

        return $player->username;
    }

    public static function getUUID($username)
    {
        if ($username == null) {
            return null;
        }
        if ($username == 'CONSOLE') {
            return 'f78a4d8d-d51b-4b39-98a3-230f2de0c670';
        }
        $player = Player::select('uuid')
            ->where('username', $username)
            ->first();
        if ($player == null) {
            return $player;
        }

        return $player->uuid;
    }

    public static function getIP($uuid): ?string
    {
        $player = Player::select('ip')
            ->where('uuid', $uuid)
            ->first();
        if ($player == null) {
            return null;
        }

        return $player->ip;
    }
}
