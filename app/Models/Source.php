<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use App\ApiDrivers\AlsoDriver;

class Source extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'content_type', 'api_url'];
    protected $apiCacheTime = 3600 * 24;

    public function apiDriver()
    {
        return new AlsoDriver($this);
    }

    public function apiData()
    {
        return $this->apiDriver()->{$this->content_type}();
    }

    public function getLocalApiUrlAttribute()
    {
        return route('source', ['source' => $this->slug]);
    }

    public function fetchApiUrl()
    {
        return cache()->remember("api_response_{$this->api_url}", $this->apiCacheTime, function () {
            $response = Http::get($this->attributes['api_url']);
            return $response->body();
        });
    }
}
