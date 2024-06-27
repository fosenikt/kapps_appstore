<?php
namespace Kapps\Controller\Stats;

use \Kapps\Model\Auth\User as AuthUser;

class Stats {

    private $AuthUser;

    public function __construct()
    {
        /* $this->AuthUser = new AuthUser;
        if (!$this->AuthUser->isAuthenticated()) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            exit;
        } */
    }

    /**
     * @OA\Get(
     *     path="/stats/apps/count/published",
     *     tags={"Stats"},
     *     summary="Get number of published apps",
     *     description="Retrieves the total number of published apps",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Number of published apps retrieved successfully",
     *         @OA\JsonContent(
     *             type="integer",
     *             example=42
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function num_published()
    {
        $obj = new \Kapps\Model\Stats\Stats();
        return $obj->num_published();
    }

    /**
     * @OA\Get(
     *     path="/stats/apps/latest",
     *     tags={"Stats"},
     *     summary="Get latest published apps",
     *     description="Retrieves a list of the latest published apps",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Latest published apps retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="time_created", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *                 @OA\Property(property="title", type="string", example="App Title"),
     *                 @OA\Property(property="short_description", type="string", example="Short description of the app"),
     *                 @OA\Property(property="primary_image", type="object",
     *                     @OA\Property(property="image", type="string", example="http://example.com/image.jpg"),
     *                     @OA\Property(property="thumb", type="string", example="http://example.com/thumb.jpg")
     *                 ),
     *                 @OA\Property(property="company", type="object",
     *                     @OA\Property(property="public_id", type="string", example="companyId"),
     *                     @OA\Property(property="name", type="string", example="Company Name"),
     *                     @OA\Property(property="logo", type="object",
     *                         @OA\Property(property="image", type="string", example="http://example.com/company_logo.jpg"),
     *                         @OA\Property(property="thumb", type="string", example="http://example.com/company_logo_thumb.jpg")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function last_published()
    {
        $obj = new \Kapps\Model\Stats\Stats();
        return $obj->last_published();
    }

    /**
     * @OA\Get(
     *     path="/stats/apps/popular",
     *     tags={"Stats"},
     *     summary="Get most popular apps",
     *     description="Retrieves a list of the most popular apps",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Most popular apps retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="time_created", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *                 @OA\Property(property="title", type="string", example="App Title"),
     *                 @OA\Property(property="short_description", type="string", example="Short description of the app"),
     *                 @OA\Property(property="primary_image", type="object",
     *                     @OA\Property(property="image", type="string", example="http://example.com/image.jpg"),
     *                     @OA\Property(property="thumb", type="string", example="http://example.com/thumb.jpg")
     *                 ),
     *                 @OA\Property(property="company", type="object",
     *                     @OA\Property(property="public_id", type="string", example="companyId"),
     *                     @OA\Property(property="name", type="string", example="Company Name"),
     *                     @OA\Property(property="logo", type="object",
     *                         @OA\Property(property="image", type="string", example="http://example.com/company_logo.jpg"),
     *                         @OA\Property(property="thumb", type="string", example="http://example.com/company_logo_thumb.jpg")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function most_popular_apps()
    {
        $obj = new \Kapps\Model\Stats\Stats();
        return $obj->most_popular_apps();
    }
}
?>
