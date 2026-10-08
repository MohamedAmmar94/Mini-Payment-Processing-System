<?php
namespace App\Http\Controllers;

use App\Jobs\ProcessTapWebhook;

class HomeController extends Controller {
    public function index() {
        // dd('ss');
        ProcessTapWebhook::dispatch(4);

    }
}
