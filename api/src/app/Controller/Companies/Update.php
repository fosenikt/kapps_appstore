<?php
namespace Kapps\Controller\Companies;

use \Kapps\Model\Auth\User as AuthUser;

class Update {

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
     *     path="/company/update",
     *     tags={"Companies"},
     *     summary="Update a company",
     *     description="Updates a company's information with the provided details",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="public_id", type="string", example="8charId", description="The unique public ID of the company"),
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
     *         description="Company updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found"
     *     )
     * )
     */
    public function update()
    {
        $obj = new \Kapps\Model\Companies\Update();
        return $obj->update($_POST);
    }
}
?>
