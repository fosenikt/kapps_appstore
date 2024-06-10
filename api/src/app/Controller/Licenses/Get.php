<?php
namespace Kapps\Controller\Licenses;


/**
 * @OA\Schema(
 *   schema="LicenseDetail",
 *   type="object",
 *   @OA\Property(property="id", type="string", example="1"),
 *   @OA\Property(property="type", type="string", example="permission"),
 *   @OA\Property(property="title", type="string", example="Commercial use"),
 *   @OA\Property(property="description", type="string", example="The licensed material and derivatives may be used for commercial purposes.")
 * )
 */

/**
 * @OA\Schema(
 *   schema="License",
 *   type="object",
 *   @OA\Property(property="id", type="string", example="1"),
 *   @OA\Property(property="title", type="string", example="MIT License"),
 *   @OA\Property(property="description", type="string", example=""),
 *   @OA\Property(property="link", type="string", example="https://choosealicense.com/licenses/mit/"),
 *   @OA\Property(property="details", type="object",
 *     @OA\Property(property="permission", type="array", 
 *       @OA\Items(ref="#/components/schemas/LicenseDetail"),
 *       example={{
 *           "id": "1",
 *           "type": "permission",
 *           "title": "Commercial use",
 *           "description": "The licensed material and derivatives may be used for commercial purposes."
 *       }}
 *     ),
 *     @OA\Property(property="condition", type="array", 
 *       @OA\Items(ref="#/components/schemas/LicenseDetail"),
 *       example={{
 *           "id": "7",
 *           "type": "condition",
 *           "title": "License and copyright notice",
 *           "description": "A copy of the license and copyright notice must be included with the licensed material."
 *       }}
 *     ),
 *     @OA\Property(property="limitation", type="array", 
 *       @OA\Items(ref="#/components/schemas/LicenseDetail"),
 *       example={{
 *           "id": "11",
 *           "type": "limitation",
 *           "title": "Liability",
 *           "description": "This license includes a limitation of liability."
 *       }}
 *     )
 *   )
 * )
 */

class Get {



	/**
     * @OA\Get(
     *     path="/licenses/get",
     *     tags={"Licenses"},
     *     summary="Get all licenses",
     *     description="Retrieves all licenses",
     *     @OA\Response(
	 *         response=200,
	 *         description="Category retrieved successfully",
	 *         @OA\JsonContent(type="object", ref="#/components/schemas/License")
	 *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
	public function get_licenses()
	{		
		$obj = new \Kapps\Model\Licenses\Get();
		return $obj->get_licenses();
	}




	/**
     * @OA\Get(
     *     path="/license/get/{id}",
     *     tags={"Licenses"},
     *     summary="Get single license",
     *     description="Retrieves a single license by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the license to retrieve",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
	 *         response=200,
	 *         description="Category retrieved successfully",
	 *         @OA\JsonContent(type="object", ref="#/components/schemas/License")
	 *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
	public function get_license($id)
	{		
		$obj = new \Kapps\Model\Licenses\Get();
		return $obj->get_license($id);
	}

}