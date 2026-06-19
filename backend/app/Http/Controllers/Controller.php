<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(version: "1.0.0", title: "Books API", description: "API documentation for the Books application")]
#[OA\Get(path: "/api/health", summary: "Health check", tags: ["System"], responses: [new OA\Response(response: 200, description: "OK")])]
abstract class Controller
{
    //
}
