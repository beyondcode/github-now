<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class PreviewController extends Controller
{
    public function index()
    {
        $markdown = view('profile');
        $converter = new GithubFlavoredMarkdownConverter();

        return view('preview', [
            'profile' => $converter->convertToHtml($markdown),
        ]);
    }
}
