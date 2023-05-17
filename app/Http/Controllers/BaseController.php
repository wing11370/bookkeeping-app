<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="bookkeeping-app API Documentation",
 *      description="bookkeeping-app API Documentation",
 *      @OA\Contact(
 *          email="developer@example.com"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Localhost"
 * )
 */
class BaseController extends Controller
{

}
