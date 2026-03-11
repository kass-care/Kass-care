<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;

class OrganizationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $organization = Organization::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Organization created successfully',
            'organization' => $organization
        ]);
    }
}

