<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>LiveShop</title>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@400;700&display=swap');

            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }

            body {
                background-color: #333;
                color: #ccc;
                font-family: 'Source Code Pro', monospace;
            }

            a {
                color: #CF0;
            }

            .layout {
                display: flex;
                justify-content: stretch;
                height: 100%;
            }

            .layout > * {
                overflow-y: auto;
            }

            .layout > nav {
                flex: none;
                background-color: #222;
                padding-top: 1rem;
            }

            .rounded {
                margin: 1rem;
                border: 1px solid #ccc;
                border-radius: .2rem;
                padding: .2rem .4rem;
            }

            .layout > .cats, .flex-head .name {
                flex: 1 1 0%;
            }

            .layout > .prods {
                background-color: #eee;
                color: #333;
                flex: 50%;
            }

            .layout > .prods .title {
                background-color: #333;
                color: #fff;
                text-align: center;
                margin: 0;
                padding: 1rem;
            }

            .layout > .prods .price {
                color: #f30;
                text-align: right;
                flex: none;
            }

            .flex-2, .flex-head {
                display: flex;
            }

            .flex-2 > * {
                flex: 50%;
            }

            .flex-head {
                background-color: #fff;
                gap: 1rem;
                justify-content: stretch;
                align-items: start;
            }

            .btn {
                display: block;
                padding: .5rem 1rem;
                cursor: pointer;
            }

            .btn:hover {
                background-color: rgba(255, 255, 255, .25);
            }
        </style>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body>
        <div class="layout" x-data="{
                apiSrc: null,
                cats: {},
                cats2: {},
                cats3: {},
                title: null,
                prods: [],
                getCats(url) {
                    if (url) apiSrc = url;
                    if (!apiSrc || !!Object.keys(this.cats).length) return;
                    fetch(apiSrc)
                        .then((response) => response.json())
                        .then((json) => this.cats = json)
                        .finally(() => { this.cats2 = {}; this.cats3 = {}; this.prods = []; this.title = null; });
                },
                hasCats2() { return !!Object.keys(this.cats2).length; },
                hasCats3() { return !!Object.keys(this.cats3).length; },
                getProds(title, url) {
                    if (!url) return;
                    this.title = title;
                    fetch(url)
                        .then((response) => response.json())
                        .then((json) => this.prods = json)
                        .finally(() => { if (!this.prods.length) alert('В тази категория няма продукти.'); });
                },
                formatPrice(number) {
                    return parseFloat(number).toFixed(2);
                },
            }">
            <nav>
                <code class="rounded">LiveShop</code>
                @foreach ($sources as $source)
                <p>
                    <a class="btn" x-on:click="getCats('{{ $source->local_api_url }}');">
                        {{ $source->name }}
                    </a>
                </p>
                @endforeach
            </nav>
            <div class="cats">
                <template x-for="(cat, c1) in cats">
                    <a class="btn" :class="{ 'rounded': cat === cats2 }" x-text="c1" x-on:click="cats2 = cat; cats3 = {}; prods = []; title = null;"></a>
                </template>
            </div>
            <div class="cats" x-show="hasCats2()">
                <template x-for="(cat, c2) in cats2">
                    <a class="btn" :class="{ 'rounded': cat === cats3 }" x-text="c2" x-on:click="cats3 = cat; prods = []; title = null;"></a>
                </template>
            </div>
            <div class="cats" x-show="hasCats3()">
                <template x-for="(url, c3) in cats3">
                    <a class="btn" :class="{ 'rounded': c3 === title }" x-text="c3" x-on:click="getProds(c3, url)"></a>
                </template>
            </div>
            <div class="prods" x-show="!!prods.length">
                <h3 class="title" x-text="title"></h3>
                <template x-for="prod in prods">
                    <div class="rounded">
                        <div class="flex-head">
                            <div class="name">
                                <b x-text="prod.name"></b>
                            </div>
                            <div class="price">
                                <b x-text="formatPrice(prod.price)"></b>
                                <span x-text="prod.currency"></span>
                            </div>
                        </div>
                        <div class="flex-2">
                            <div>
                                Гаранция:
                                <span x-text="prod.warrantyQty"></span>
                                <span x-text="prod.warrantyUnit == 2 ? 'м.' : 'г.'"></span>
                            </div>
                            <div>
                                Наличност:
                                <span x-text="prod.stockInfo"></span>
                            </div>
                        </div>
                        <div class="flex-2">
                            <div>
                                Product ID:
                                <span x-text="prod.productId"></span>
                            </div>
                            <div>
                                Code ID:
                                <span x-text="prod.codeId"></span>
                            </div>
                        </div>
                        <div class="flex-2">
                            <div>
                                Group ID:
                                <span x-text="prod.groupId"></span>
                            </div>
                            <div>
                                Vendor:
                                <span x-text="prod.vendor"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </body>
</html>
