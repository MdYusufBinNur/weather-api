<?php

namespace App\Service;

use Illuminate\Http\Request;

interface BaseServices
{
    public function index();

    public function store(Request $request);

    public function update(Request $request, $id);

    public function destroy($id);
}
