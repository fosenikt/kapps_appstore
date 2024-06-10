<?php
namespace Kapps\Controller\Stats;

use \Kapps\Model\Auth\User as AuthUser;

class Log {

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
     *     path="/stats/log",
     *     tags={"Stats"},
     *     summary="Log statistics",
     *     description="Logs a statistic entry with the given type and entity ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="type", type="string", example="view"),
     *                 @OA\Property(property="entity_id", type="string", example="123")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Log entry created successfully",
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
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function log()
    {
        $obj = new \Kapps\Model\Stats\Log();
        return $obj->log($_POST['type'], $_POST['entity_id']);
    }
}
?>
