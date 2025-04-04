<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\RedirectResponse;

Route::get("/", function () {
    return redirect("/api/documentation");
});
