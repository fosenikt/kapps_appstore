<?php
namespace Kapps\Controller\Delivery;

use \Kapps\Model\Auth\User as AuthUser;

class Methods {

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
     *     path="/delivery/methods/get",
     *     tags={"Delivery"},
     *     summary="Get delivery methods",
     *     description="Retrieves a list of all delivery methods",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Delivery methods retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Standard Delivery")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function get_methods()
    {
        $obj = new \Kapps\Model\Delivery\Methods();
        return $obj->get_methods();
    }
}
?>
