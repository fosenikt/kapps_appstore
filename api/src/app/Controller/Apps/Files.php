<?php
namespace Kapps\Controller\Apps;

use \Kapps\Model\Auth\User as AuthUser;

class Files {

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
     *     path="/app/files/upload",
     *     tags={"Apps"},
     *     summary="Upload files for an app",
     *     description="Uploads files for a specific app. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="app_id", type="string", description="The ID of the app"),
     *                 @OA\Property(property="files", type="array", @OA\Items(type="string", format="binary"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Files uploaded successfully",
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
	public function upload()
	{
		$obj = new \Kapps\Model\Apps\Files();
		return $obj->upload($_POST, $_FILES['files']);
	}




	/**
     * @OA\Post(
     *     path="/app/file/delete",
     *     tags={"Apps"},
     *     summary="Delete a file from an app",
     *     description="Deletes a specific file from an app. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="app_id", type="string", description="The ID of the app"),
     *             @OA\Property(property="file_id", type="string", description="The ID of the file to delete")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File deleted successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="App or file not found"
     *     )
     * )
     */
	public function delete()
	{
		$obj = new \Kapps\Model\Apps\Files();
		return $obj->delete($_POST['app_id'], $_POST['file_id']);
	}

	/* public function get_file($id)
	{
		$obj = new \Kapps\Model\Apps\Files();
		return $obj->get_file($id);
	}

	public function get_app_files($id)
	{
		$obj = new \Kapps\Model\Apps\Files();
		return $obj->get_app_files($id);
	} */

}