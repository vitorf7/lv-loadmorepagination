# Laravel Load More Pagination

[![Build Status](https://travis-ci.org/vitorf7/lv-loadmorepagination.svg?branch=master)](https://travis-ci.org/vitorf7/lv-loadmorepagination)

A package that will give you access to a LoadMorePagination trait where you can paginate your model's results with an initial number of items and then a different number of items on subsequent pages.

This package does not implement any paginator interfaces or anything like that it simply returns a similar array to a paginator with the usual information such as last_page, current_page, data, etc.

## Usage

To install this package do the following:

```
composer require vitorf7/lv-loadmorepagination
```

Once installed you can use this in two ways. Either by imporating this into your Eloquent Model or using it in your Controller or Repository, etc.

This package has an initial load of 9 items and subsequent load of 3.

In a Model:
```php
<?php

namespace App;

use VitorF7\LoadMorePagination\LoadMorePagination;

class Post extends Model
{
    use LoadMorePagination;
}

// in a controller action or something you can use it like so
Post::paginateLoadMore(); // Loads 9 on first page and 3 every page after that
Post::paginateLoadMore(8, 4); // Loads 8 on first page and 4 every page after that
Post::paginateLoadMore(4, 4); // Loads 4 on first page and 4 every page after that. However at this point you could just simply use Post::paginate(4). This package is better used when you need to load different amount of items from the first page

// You can use it after you do an eager load of relationship too. At least simple loads for now as it has not been tested with something more complex
Post::with('categories')->paginateLoadMore();
```

If you want to use it in a Controller, Repository, etc you need to pass 3 arguments.
- 1st Argument is the inital load of items
- 2nd Argument is the load of subsequent items
- 3rd Argument is the Model you are trying to paginate

Like so:
```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use VitorF7\LoadMorePagination\LoadMorePagination;

class PostsController extends Controller
{
    use LoadMorePagination;

    public function index()
    {
        $posts = $this->paginatedLoadMore(9, 3, new Post);

        return view('posts.index', compact('posts'));
    }
}
```

If you do not pass a model you will get a ``` VitorF7\LoadMorePagination\ModelClassRequiredException```