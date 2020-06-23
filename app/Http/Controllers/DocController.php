<?php

namespace App\Http\Controllers;

class DocController extends Controller
{
    public function index($name)
    {
        // 仅开发环境可以访问
        if (config('app.env') !== 'dev') {
            return success();
        }

        $doc = storage_path('../../doc/');
        $config = file_get_contents($doc.'config.json');
        $config = json_decode($config, true);

        if ($name === 'index') {
            $docPath = $doc.'readme.md';
        } else {
            $name = urldecode($name);
            $name = str_replace('-', '/', $name);
            $docPath = $doc.$name;
        }

        $parseDown = new \Parsedown();
        $html = $parseDown->text(file_get_contents($docPath));

        return view('document')
            ->with('html', $html)
            ->with('config', $config);
    }
}
