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
    protected $apiDriver;

    public function apiDriver()
    {
        if (!$this->apiDriver) {
            $this->apiDriver = new AlsoDriver($this->getApiResponse());
        }
        return $this->apiDriver;
    }

    public function apiData()
    {
        $data = ['error' => $this->getApiResponse()];
        try {
            $data = call_user_func([$this->apiDriver(), $this->content_type]);
        } catch (\Exception $e) {
        }
        return $data;
    }

    public function getLocalApiUrlAttribute()
    {
        return route('source', ['source' => $this->slug]);
    }

    public function getApiResponse()
    {
        return cache()->rememberForever("api_response_{$this->api_url}", function () {
            $response = Http::get($this->api_url);
            return $response->body();
        });
    }
}
