<?php
namespace Kapps\Controller\Municipality;

use \Kapps\Model\Auth\User as AuthUser;

class Get {

    private $AuthUser;

    public function __construct()
    {
        $this->AuthUser = new AuthUser;
        if (!$this->AuthUser->isAuthenticated()) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            exit;
        }
    }

    /**
     * @OA\Get(
     *     path="/company/municipalities/get",
     *     tags={"Municipality"},
     *     summary="Get municipalities",
     *     description="Retrieves a list of all municipalities",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Municipalities retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="public_id", type="string", example="8charId"),
     *                 @OA\Property(property="title", type="string", example="Example Municipality"),
     *                 @OA\Property(property="county", type="string", example="County Name"),
     *                 @OA\Property(property="type_id", type="integer", example=1),
     *                 @OA\Property(property="org_numb", type="string", example="123456789"),
     *                 @OA\Property(property="website", type="string", example="http://www.example.com"),
     *                 @OA\Property(property="domain", type="string", example="example.com"),
     *                 @OA\Property(property="type", type="string", example="Municipality"),
     *                 @OA\Property(property="logo", type="string", example="http://example.com/logo.png")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function get_municipalities()
    {
        $obj = new \Kapps\Model\Municipality\Get();
        return $obj->get_municipalities();
    }
}
?>
