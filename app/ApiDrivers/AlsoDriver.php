<?php

namespace App\ApiDrivers;

use App\Models\Source;
use DOMDocument;

class AlsoDriver
{
    protected $dom;

    public function __construct(protected $data)
    {
        $this->dom = new DOMDocument;
    }

    private function boot()
    {
        if (empty($this->data) || !$this->dom) return;

        $this->dom->loadXML($this->data);

        $error = $this->dom->getElementsByTagName('error');
        if ($error && $error->item(0)) {
            throw new \Exception($error->item(0)->nodeValue, 500);
        }
    }

    private function cachedResponse($method)
    {
        $this->boot();
        // TODO: Add caching of mapped model
        $data = call_user_func([$this, $method]);
    }

    public function categories()
    {
        return $this->cachedResponse('mapCategories');
    }

    public function products()
    {
        return $this->cachedResponse('mapProducts');
    }

    public function product()
    {
        return $this->cachedResponse('mapProduct');
    }

    protected function mapCategories()
    {
        $links = $this->dom->getElementsByTagName('link');
        $categories = [];

        foreach ($links as $link) {
            $url = $link->getAttribute('href');
            $url = route('source', ['source' => $this->source->slug, 'feed' => 'products', 'url' => $url]);
            $label = $link->previousSibling->previousSibling->nodeValue;
            $parent = $link->parentNode->getAttribute('name');
            $parentRoot = $link->parentNode->parentNode->getAttribute('name');
            $categories[$parentRoot][$parent][$label] = $url;
        }

        return $categories;
    }

    protected function mapProducts()
    {
        $items = $this->dom->getElementsByTagName('product');
        $products = [];

        foreach ($items as $product) {
            $result = [];
            foreach ($product->attributes as $attr) {
                $result[$attr->nodeName] = $attr->value;
            }
            foreach ($product->childNodes as $prop) {
                $value = $prop->nodeValue;
                if ($prop->localName === 'price') {
                    if (0 === (float)$value) continue;
                    $value = number_format((float)$value, 2, '.', '');
                    $result['currency'] = $prop->getAttribute('currency');
                }
                $result[$prop->localName] = $value;
            }
            $products[] = $result;
        }

        array_multisort(array_column($products, 'price'), SORT_ASC, $products);

        return $products;
    }

    protected function mapProduct()
    {
    }
}
