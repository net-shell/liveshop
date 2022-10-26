<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ env('APP_NAME') }}</title>

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

            .noselect {
                -webkit-touch-callout: none;
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }

            .loading {
                background-color: rgba(0, 0, 0, .8);
                -webkit-backdrop-filter: blur(5px);
                backdrop-filter: blur(5px);
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .layout, .flex-row {
                display: flex;
                flex-direction: row;
                justify-content: stretch;
                width: 100%;
                height: 100%;
            }

            @media (max-width: 800px) {
                .layout {
                    flex-direction: column;
                }
            }

            .flex-col {
                display: flex;
                flex-direction: column;
                width: 100%;
            }

            nav {
                background-color: #000;
                flex: 1;
            }

            .rounded {
                margin: .8rem;
                border: 1px solid #ccc;
                border-radius: .2rem;
                padding: .2rem;
            }

            .btn {
                display: block;
                padding: .4rem .8rem;
                cursor: pointer;
            }

            .btn:hover {
                background-color: rgba(255, 255, 255, .25);
            }

            .panel {
                overflow-y: auto;
            }

            .layout .cats, .flex-head .name {
                flex: 1 1 0%;
            }

            .cats {
                background-color: #333;
            }

            .cat2 {
                background-color: #444;
            }

            .cat3 {
                background-color: #555;
            }

            .cats.empty {
                background-color: #666;
                color: #fff;
            }

            .prods {
                background-color: #eee;
                color: #333;
                flex: 1 0 50%;
            }

            .prods .title {
                background-color: #888;
                color: #fff;
                text-align: center;
                margin: 0;
                padding: 1rem;
            }

            .prods .price {
                color: #f30;
                text-align: right;
                flex: none;
            }

            .prods .info {
                background-color: #ddd;
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
                padding: .2rem .4rem;
            }

            .flex-head:hover {
                background-color: #CF0;
            }
        </style>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body>
        <div class="layout" x-data="{
                loading: false,
                apiSrc: null,
                cats: {},
                cats2: {},
                cats3: {},
                empty: false,
                title: null,
                prods: [],
                getCats(url) {
                    if (url) apiSrc = url;
                    if (!apiSrc || !!Object.keys(this.cats).length) return;
                    this.loading = true;
                    fetch(apiSrc)
                        .then((response) => response.json())
                        .then((json) => { if (json.error) { alert(json.error); } else { this.cats = json; } })
                        .finally(() => { this.loading = false; this.cats2 = {}; this.cats3 = {}; this.prods = []; this.title = null; });
                },
                hasCats2() { return !!Object.keys(this.cats2).length; },
                hasCats3() { return !!Object.keys(this.cats3).length; },
                getProds(title, url) {
                    if (!url) return;
                    this.title = title;
                    this.loading = true;
                    this.empty = false;
                    fetch(url)
                        .then((response) => response.json())
                        .then((json) => this.prods = json)
                        .finally(() => { this.loading = false; this.empty = !this.prods.length; });
                },
            }" x-init="getCats('{{ $sources->first()->local_api_url }}');">
            <div class="loading noselect" x-show="loading">
                <span class="rounded">ЗАРЕЖДАНЕ...</span>
            </div>
            <div class="flex-col panel">
                <nav class="flex-row" tabindex="-1">
                    <a class="btn" href="https://netshell.bg/" target="_blank">НЕТШЕЛ</a>
                    <a class="btn" href="{{ url('/') }}">{{ env('APP_NAME') }}</a>
                    @foreach ($sources as $source)
                    <a class="btn" @click="getCats('{{ $source->local_api_url }}')" href="#">
                        {{ $source->name }}
                    </a>
                    @endforeach
                </nav>
                <div class="flex-row" tabindex="0">
                    <div class="cats" tabindex="0">
                        <template x-for="(cat, c1) in cats">
                            <a class="btn" :class="{ 'rounded': cat === cats2 }" x-text="c1" x-on:click="cats2 = cat; cats3 = {}; prods = []; title = null;" tabindex="1"></a>
                        </template>
                    </div>
                    <div class="cats cat2" x-show="hasCats2()" tabindex="0">
                        <template x-for="(cat, c2) in cats2">
                            <a class="btn" :class="{ 'rounded': cat === cats3 }" x-text="c2" x-on:click="cats3 = cat; prods = []; title = null;" tabindex="0"></a>
                        </template>
                    </div>
                    <div class="cats cat3" x-show="hasCats3()" tabindex="0">
                        <template x-for="(url, c3) in cats3">
                            <a class="btn" :class="{ 'rounded': c3 === title }" x-text="c3" x-on:click="getProds(c3, url)" tabindex="0"></a>
                        </template>
                    </div>
                    <div class="cats empty" x-show="empty">
                        <div class="rounded noselect">
                            <b>В тази категория няма продукти.</b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel prods" x-show="!!prods.length" tabindex="0">
                <h3 class="title noselect">
                    <span x-text="title"></span>
                    (<b x-text="prods.length"></b>)
                </h3>
                <template x-for="prod in prods">
                    <div :class="{ 'rounded': expanded }" x-data="{ expanded: false }">
                        <div class="flex-head" @click="expanded = !expanded" tabindex="0">
                            <div class="name">
                                <b x-text="prod.name"></b>
                            </div>
                            <div class="price">
                                <b x-text="prod.price"></b>
                                <span x-text="prod.currency"></span>
                            </div>
                        </div>
                        <div class="info" x-show="expanded">
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
                    </div>
                </template>
            </div>
        </div>
    </body>
</html>
