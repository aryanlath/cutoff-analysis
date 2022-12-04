<?php

use App\Models\Branch;
use App\Models\Institute;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/view-branch-cutoff-ranks', function () {
    return view('search-by-branch');
})->name('search-by-branch');

// NOTE: Deprecated route
Route::get('/search-by-branch', function () {
    return redirect(status: 301)->route('search-by-branch', request()->all());
});

Route::get('/view-institute-cutoff-ranks', function () {
    return view('search-by-institute');
})->name('search-by-institute');

// NOTE: Deprecated route
Route::get('/search-by-institute', function () {
    return redirect(status: 301)->route('search-by-institute', request()->all());
});

Route::get('/analyse-institute-cutoff-trends', function () {
    return view('institute-trends');
})->name('institute-trends');

// NOTE: Deprecated route
Route::get('/institute-trends', function () {
    return redirect(status: 301)->route('institute-trends', request()->all());
});

Route::get('/analyse-round-wise-cutoff-trends', function () {
    return view('round-trends');
})->name('round-trends');

// NOTE: Deprecated route
Route::get('/round-trends', function () {
    return redirect(status: 301)->route('round-trends', request()->all());
});

Route::get('/analyse-branch-cutoff-trends', function () {
    return view('branch-trends');
})->name('branch-trends');

// NOTE: Deprecated route
Route::get('/branch-trends', function () {
    return redirect(status: 301)->route('branch-trends', request()->all());
});

Route::prefix('/news')->group(function () {
    Route::get('/', function () {
        return view('news');
    })->name('news');

    Route::get('/using-the-josaa-analysis-tool', function () {
        return view('news.using-the-josaa-analysis-tool');
    })->name('news.using-the-josaa-analysis-tool');

    Route::prefix('/amp')->group(function () {
        Route::get('/using-the-josaa-analysis-tool', function () {
            return view('news.amp.using-the-josaa-analysis-tool');
        })->name('news.amp.using-the-josaa-analysis-tool');
    });
});

Route::prefix('/institutes')->group(function () {
    Route::get('/', function () {
        return view('institute-list');
    })->name('institute-list');

    Route::get('/{institute:slug}', function (Institute $institute) {
        return view('institute-details', ['institute' => $institute]);
    })->name('institute-details');
});

Route::prefix('/branches')->group(function () {
    Route::get('/', function () {
        return view('branch-list');
    })->name('branch-list');

    Route::get('/{branch:slug}', function (Branch $branch) {
        return view('branch-details', ['branch' => $branch]);
    })->name('branch-details');
});
