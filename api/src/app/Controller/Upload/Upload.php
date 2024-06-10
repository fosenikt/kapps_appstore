<?php
namespace Kapps\Controller\Upload;

use \Kapps\Model\Auth\User as AuthUser;

class Upload {

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
     *     path="/company/logo/upload",
     *     tags={"Upload"},
     *     summary="Upload company logo",
     *     description="Uploads a logo for a company",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="public_id", type="string", description="The public ID of the company", example="companyId"),
     *                 @OA\Property(property="image", type="string", format="binary", description="The company logo image file")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company logo uploaded successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Logo uploaded successfully"),
     *             @OA\Property(property="logo_url", type="string", example="http://example.com/company_logo.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or image upload failed"
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
    public function upload_company_logo()
    {
        $obj = new \Kapps\Model\Upload\Upload();
        return $obj->upload_company_logo($_POST, $_FILES['image']);
    }
}
?>
