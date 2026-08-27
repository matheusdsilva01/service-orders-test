<?php

namespace App\Controllers;

class PageController
{
    public function home(): void
    {
        view('home.php');
    }

}
