<?php
namespace Kapps\Controller\Companies;

use \Kapps\Model\Auth\User as AuthUser;

class Delete {

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
     * @OA\Delete(
     *     path="/company/delete/{id}",
     *     tags={"Companies"},
     *     summary="Delete a company",
     *     description="Deletes a company with the specified ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="alphanumeric123"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
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
    public function delete($id)
    {
        $obj = new \Kapps\Model\Companies\Delete();
        return $obj->delete($id);
    }
}
?>
