<?php
namespace Kapps\Controller\Apps;

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
     *     path="/app/delete/{id}",
     *     tags={"Apps"},
     *     summary="Delete an app",
     *     description="Deletes an app by its ID. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the app to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="App deleted successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="App not found"
     *     )
     * )
     */
	public function delete($id)
	{		
		$obj = new \Kapps\Model\Apps\Delete();
		return $obj->delete($id);
	}

}