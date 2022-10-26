<?php

namespace App\ApiDrivers;

use App\Models\Source;
use DOMDocument;

class AlsoDriver
{
    protected $dom;

    public function __construct(protected Source $source)
    {
        $xml = $source->fetchApiUrl();
        $this->dom = new DOMDocument;
        $this->dom->loadXML($xml);
    }

    public function categories()
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

    public function products()
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

    public function product()
    {

    }
}
