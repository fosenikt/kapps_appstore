<?php
namespace Kapps\Controller\Companies;

use \Kapps\Model\Auth\User as AuthUser;

class Create {

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
     * @OA\Post(
     *     path="/companies/create",
     *     tags={"Companies"},
     *     summary="Create a new company",
     *     description="Creates a new company with the provided details",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="domain", type="string", example="example.com"),
     *                 @OA\Property(property="title", type="string", example="Example Company"),
     *                 @OA\Property(property="county", type="string", example="County Name"),
     *                 @OA\Property(property="type_id", type="integer", example=1),
     *                 @OA\Property(property="org_numb", type="string", example="123456789"),
     *                 @OA\Property(property="website", type="string", example="http://www.example.com"),
     *                 @OA\Property(property="type", type="string", example="LLC")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="public_id", type="string", example="8charId")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function create()
    {
        $obj = new \Kapps\Model\Companies\Create();
        return $obj->create($_POST);
    }
}
?>
